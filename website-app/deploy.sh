#!/bin/bash

# Deploy script for GCP VM
echo "Starting deployment..."

# Stop existing containers
docker-compose down

# Pull latest images
docker-compose pull

# Build and start containers
docker-compose up -d --build

# Wait for containers to be ready
echo "Waiting for containers to start..."
sleep 30

# Run Laravel migrations
docker-compose exec app php artisan migrate --force

# Clear and cache config
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Set proper permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Deployment completed successfully!"
echo "Your Laravel application is now running on port 80"
