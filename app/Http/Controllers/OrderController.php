<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'user']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        $orders = $query->latest()->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::orderBy('type')->orderBy('name')->get();
        return view('orders.pos', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'    => ['required', 'string', 'max:255'],
            'customer_phone'   => ['nullable', 'string', 'max:50'],
            'customer_address' => ['nullable', 'string', 'max:500'],
            'products'         => ['required', 'array'],
        ]);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'total_price'      => 0,
                'user_id'          => auth()->id(),
            ]);

            $total    = 0;
            $hasItems = false;

            foreach ($request->products as $productId => $data) {
                $qty = (int) ($data['quantity'] ?? 0);
                if ($qty <= 0) continue;

                $product = Product::lockForUpdate()->find($productId);
                if (!$product) continue;

                if ($product->stock_quantity < $qty) {
                    DB::rollBack();
                    return back()->withInput()
                        ->with('error', "Insufficient stock for \"{$product->name}\". Available: {$product->stock_quantity}");
                }

                $product->decrement('stock_quantity', $qty);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $qty,
                    'price'      => $product->price,
                ]);

                $total    += $product->price * $qty;
                $hasItems  = true;
            }

            if (!$hasItems) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Please add at least one product to the order.');
            }

            $order->update(['total_price' => $total]);

            DB::commit();

            return redirect()->route('orders.invoice', $order)
                ->with('success', "Order #{$order->id} placed successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'An error occurred while creating the order. Please try again.');
        }
    }

    public function invoice(Order $order)
    {
        $order->load('items.product', 'user');
        return view('orders.invoice', compact('order'));
    }

    public function destroy(Order $order)
    {
        $id = $order->id;
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', "Order #{$id} deleted.");
    }
}
