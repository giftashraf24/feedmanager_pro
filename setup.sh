#!/bin/bash
# ─────────────────────────────────────────────
#  FeedManager Pro — One-command Setup Script
# ─────────────────────────────────────────────
set -e

GREEN='\033[0;32m'
CYAN='\033[0;36m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${CYAN}"
echo "  ███████╗███████╗███████╗██████╗ "
echo "  ██╔════╝██╔════╝██╔════╝██╔══██╗"
echo "  █████╗  █████╗  █████╗  ██║  ██║"
echo "  ██╔══╝  ██╔══╝  ██╔══╝  ██║  ██║"
echo "  ██║     ███████╗███████╗██████╔╝"
echo "  ╚═╝     ╚══════╝╚══════╝╚═════╝ "
echo -e "  ${GREEN}FeedManager Pro — Setup${NC}"
echo ""

echo -e "${YELLOW}[1/5]${NC} Installing Composer dependencies..."
composer install --no-interaction --prefer-dist

echo -e "${YELLOW}[2/5]${NC} Copying environment file..."
cp -n .env.example .env || true

echo -e "${YELLOW}[3/5]${NC} Generating application key..."
php artisan key:generate --ansi

echo -e "${YELLOW}[4/5]${NC} Setting up SQLite database..."
touch database/database.sqlite
php artisan migrate --force --ansi

echo -e "${YELLOW}[5/5]${NC} Seeding demo data..."
php artisan db:seed --ansi

echo ""
echo -e "${GREEN}✅ Setup complete!${NC}"
echo ""
echo -e "  Start server:  ${CYAN}php artisan serve${NC}"
echo -e "  Open browser:  ${CYAN}http://localhost:8000${NC}"
echo ""
echo -e "  Demo accounts:"
echo -e "  ${GREEN}admin@feed.pro${NC} / admin123  (Administrator)"
echo -e "  ${CYAN}staff@feed.pro${NC} / staff123  (Staff)"
echo ""
