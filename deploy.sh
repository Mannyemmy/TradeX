#!/bin/bash
# TradeXpromax cPanel Deployment Script
# Usage: bash deploy.sh
# Run this from your cPanel SSH terminal inside ~/public_html or your subfolder

set -e

echo "=============================="
echo " TradeXpromax Deployment Script"
echo "=============================="

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Check for PHP
if ! command -v php &> /dev/null; then
    echo -e "${RED}PHP is not installed. Please install PHP 7.3+|8.0${NC}"
    exit 1
fi

# Check for Composer
if ! command -v composer &> /dev/null; then
    echo -e "${RED}Composer is not installed.${NC}"
    exit 1
fi

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$DIR"

echo -e "${YELLOW}Working directory: $DIR${NC}"

# 1. Create .env if missing
if [ ! -f .env ]; then
    echo -e "${YELLOW}Creating .env from .env.example...${NC}"
    cp .env.example .env
    echo -e "${GREEN}.env created.${NC}"
    echo -e "${RED}IMPORTANT: Edit .env with your database credentials before continuing!${NC}"
    echo -e "${RED}Run: nano .env${NC}"
    exit 1
fi

# 2. Install PHP dependencies
echo -e "${YELLOW}Installing Composer dependencies (no dev)...${NC}"
composer install --no-dev --optimize-autoloader
echo -e "${GREEN}Composer dependencies installed.${NC}"

# 3. Generate app key if empty
APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ]; then
    echo -e "${YELLOW}Generating application key...${NC}"
    php artisan key:generate --force
    echo -e "${GREEN}App key generated.${NC}"
fi

# 4. Run migrations
echo -e "${YELLOW}Running database migrations...${NC}"
php artisan migrate --force
echo -e "${GREEN}Migrations complete.${NC}"

# 5. Storage link
echo -e "${YELLOW}Creating storage symlink...${NC}"
php artisan storage:link
echo -e "${GREEN}Storage link created.${NC}"

# 6. Cache optimization
echo -e "${YELLOW}Optimizing Laravel...${NC}"
php artisan optimize
echo -e "${GREEN}Optimization complete.${NC}"

# 7. Set permissions
echo -e "${YELLOW}Setting directory permissions...${NC}"
chmod -R 775 storage bootstrap/cache
chmod 600 .env
echo -e "${GREEN}Permissions set.${NC}"

# 8. Install npm and build assets
if command -v npm &> /dev/null; then
    echo -e "${YELLOW}Installing npm packages...${NC}"
    npm install
    echo -e "${YELLOW}Building production assets...${NC}"
    npm run production
    echo -e "${GREEN}Assets built.${NC}"
else
    echo -e "${RED}npm not found. Install Node.js or run npm install && npm run production manually.${NC}"
fi

echo ""
echo -e "${GREEN}==============================${NC}"
echo -e "${GREEN} Deployment complete!${NC}"
echo -e "${GREEN}==============================${NC}"
echo ""
echo "Next steps:"
echo "  1. Verify your domain points to $(realpath public)"
echo "  2. If deployed in a subfolder, set up .htaccess rewrite"
echo "  3. Set up a cron job:"
echo "     * * * * * php $(realpath artisan) schedule:run >> /dev/null 2>&1"
echo ""
