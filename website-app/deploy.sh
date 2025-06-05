#!/bin/bash

# Deployment script for Laravel application

echo "🚀 Deploying Laravel application..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

# Stop existing containers if running
echo "🛑 Stopping existing containers..."
docker-compose down

# Build and start containers
echo "🔨 Building and starting containers..."
docker-compose up -d --build

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 30

# Run Laravel setup commands
echo "⚙️ Setting up Laravel application..."

# Generate application key if not set
docker-compose exec app php artisan key:generate --force

# Run database migrations
echo "🗄️ Running database migrations..."
docker-compose exec app php artisan migrate --force

# Seed database (optional)
echo "🌱 Seeding database..."
docker-compose exec app php artisan db:seed --force

# Clear and cache configurations
echo "🧹 Clearing and caching configurations..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Set proper permissions
echo "🔐 Setting permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

echo "✅ Deployment completed successfully!"
echo ""
echo "🌐 Your Laravel application is now running at:"
echo "   - Main app: http://$(curl -s ifconfig.me)"
echo "   - phpMyAdmin: http://$(curl -s ifconfig.me):8080"
echo ""
echo "📊 To check container status: docker-compose ps"
echo "📋 To view logs: docker-compose logs -f"
echo "🛑 To stop: docker-compose down"
