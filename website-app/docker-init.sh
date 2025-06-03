#!/bin/bash

echo "🐳 Initializing Docker environment for Laravel Puskesmas..."

# Copy environment file
if [ ! -f .env ]; then
    echo "📄 Copying environment file..."
    cp .env.docker .env
fi

# Generate application key if not exists
echo "🔐 Generating application key..."
docker-compose exec app php artisan key:generate

# Run database migrations
echo "🗄️ Running database migrations..."
docker-compose exec app php artisan migrate

# Seed database
echo "🌱 Seeding database..."
docker-compose exec app php artisan db:seed

# Clear and cache configurations
echo "🔄 Clearing and caching configurations..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Create storage link
echo "🔗 Creating storage link..."
docker-compose exec app php artisan storage:link

# Set permissions
echo "📝 Setting permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

echo "✅ Docker environment initialized successfully!"
echo "🌐 Application is running at: http://localhost:8000"
