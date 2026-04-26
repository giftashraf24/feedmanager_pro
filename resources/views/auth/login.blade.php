<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — FeedManager Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
        --bg:#0b0e14;--bg2:#111520;--bg3:#161b27;
        --border:rgba(255,255,255,0.07);--border2:rgba(255,255,255,0.13);
        --accent:#4ade80;--accent2:#22d3ee;--accent4:#f43f5e;--accent5:#a78bfa;
        --text:#e2e8f0;--text2:#94a3b8;--text3:#64748b;
        --font-head:'Syne',sans-serif;--font-body:'DM Sans',sans-serif;
    }
    html,body{height:100%;background:var(--bg);color:var(--text);font-family:var(--font-body);font-size:14px;}
    .auth-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;position:relative;overflow:hidden;}
    .orb{position:absolute;border-radius:50%;filter:blur(80px);opacity:0.12;pointer-events:none;}
    .orb-1{width:500px;height:500px;background:var(--accent5);top:-150px;left:-100px;animation:float 9s ease-in-out infinite;}
    .orb-2{width:400px;height:400px;background:var(--accent2);bottom:-120px;right:-80px;animation:float 11s ease-in-out infinite reverse;}
    .orb-3{width:280px;height:280px;background:var(--accent);top:60%;left:55%;animation:float 7s ease-in-out infinite 1.5s;}
    @keyframes float{0%,100%{transform:translate(0,0);}50%{transform:translate(28px,-22px);}}
    .auth-card{position:relative;z-index:1;background:var(--bg2);border:1px solid var(--border2);border-radius:22px;padding:44px 38px;width:100%;max-width:420px;box-shadow:0 8px 48px rgba(0,0,0,0.55);}
    .brand{display:flex;align-items:center;gap:11px;justify-content:center;margin-bottom:32px;}
    .brand-icon{width:42px;height:42px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:21px;}
    .brand-name{font-family:var(--font-head);font-size:21px;font-weight:800;}
    .brand-name span{color:var(--accent);}
    .auth-title{font-family:var(--font-head);font-size:24px;font-weight:700;text-align:center;margin-bottom:5px;}
    .auth-sub{text-align:center;color:var(--text3);font-size:13px;margin-bottom:28px;}
    .form-group{margin-bottom:16px;}
    .form-label{display:block;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--text2);margin-bottom:6px;}
    .form-input{width:100%;background:var(--bg3);border:1px solid var(--border2);border-radius:8px;padding:11px 13px;color:var(--text);font-family:var(--font-body);font-size:14px;outline:none;transition:border-color 0.2s,box-shadow 0.2s;}
    .form-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(74,222,128,0.1);}
    .form-input::placeholder{color:var(--text3);}
    .form-input.is-invalid{border-color:var(--accent4);}
    .remember-row{display:flex;align-items:center;gap:8px;margin-bottom:20px;}
    .remember-row input[type=checkbox]{accent-color:var(--accent);width:14px;height:14px;cursor:pointer;}
    .remember-row label{font-size:13px;color:var(--text2);cursor:pointer;}
    .btn-login{width:100%;padding:12px;background:linear-gradient(135deg,var(--accent),var(--accent2));border:none;border-radius:8px;color:#0b0e14;font-family:var(--font-head);font-size:14px;font-weight:700;cursor:pointer;transition:opacity 0.2s,transform 0.15s;letter-spacing:0.3px;}
    .btn-login:hover{opacity:0.9;transform:translateY(-1px);}
    .btn-login:active{transform:translateY(0);}
    .demo-box{margin-top:24px;background:var(--bg3);border-radius:10px;padding:14px 16px;}
    .demo-box-title{font-size:10px;text-transform:uppercase;letter-spacing:0.6px;color:var(--text3);margin-bottom:10px;font-weight:600;}
    .demo-row{display:flex;align-items:center;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);}
    .demo-row:last-child{border-bottom:none;}
    .demo-cred{font-size:12px;color:var(--text2);}
    .demo-right{display:flex;align-items:center;gap:8px;}
    .role-badge{font-size:10px;padding:2px 7px;border-radius:20px;font-weight:600;text-transform:uppercase;letter-spacing:0.4px;}
    .rb-admin{background:rgba(167,139,250,0.15);color:var(--accent5);}
    .rb-staff{background:rgba(34,211,238,0.15);color:var(--accent2);}
    .fill-btn{font-size:11px;color:var(--accent);cursor:pointer;background:none;border:none;font-family:var(--font-body);padding:2px 6px;border-radius:4px;transition:background 0.15s;}
    .fill-btn:hover{background:rgba(74,222,128,0.1);}
    .alert-error{background:rgba(244,63,94,0.08);border:1px solid rgba(244,63,94,0.2);border-radius:8px;padding:10px 14px;color:#fda4af;font-size:13px;margin-bottom:16px;}
    .alert-success{background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.2);border-radius:8px;padding:10px 14px;color:#86efac;font-size:13px;margin-bottom:16px;}
    </style>
</head>
<body>
<div class="auth-wrap">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="auth-card">
        <div class="brand">
            <div class="brand-icon">🌾</div>
            <div class="brand-name">Feed<span>Manager</span></div>
        </div>
        <div class="auth-title">Welcome back</div>
        <div class="auth-sub">Sign in to your dashboard</div>

        @if($errors->any())
            <div class="alert-error">❌ {{ $errors->first() }}</div>
        @endif
        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email address</label>
                <input type="email" id="email" name="email"
                    class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password"
                    class="form-input" placeholder="••••••••" autocomplete="current-password" required>
            </div>
            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Keep me signed in</label>
            </div>
            <button type="submit" class="btn-login">Sign in →</button>
        </form>

        <div class="demo-box">
            <div class="demo-box-title">Demo credentials</div>
            <div class="demo-row">
                <span class="demo-cred">admin@feed.pro / admin123</span>
                <div class="demo-right">
                    <span class="role-badge rb-admin">Admin</span>
                    <button class="fill-btn" onclick="fill('admin@feed.pro','admin123')">Use</button>
                </div>
            </div>
            <div class="demo-row">
                <span class="demo-cred">staff@feed.pro / staff123</span>
                <div class="demo-right">
                    <span class="role-badge rb-staff">Staff</span>
                    <button class="fill-btn" onclick="fill('staff@feed.pro','staff123')">Use</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function fill(e, p) {
    document.getElementById('email').value = e;
    document.getElementById('password').value = p;
}
</script>
</body>
</html>
