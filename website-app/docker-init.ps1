Write-Host "🐳 Initializing Docker environment for Laravel Puskesmas..." -ForegroundColor Green

# Copy environment file
if (!(Test-Path ".env")) {
    Write-Host "📄 Copying environment file..." -ForegroundColor Yellow
    Copy-Item ".env.docker" ".env"
}

# Generate application key if not exists
Write-Host "🔐 Generating application key..." -ForegroundColor Yellow
docker-compose exec app php artisan key:generate

# Run database migrations
Write-Host "🗄️ Running database migrations..." -ForegroundColor Yellow
docker-compose exec app php artisan migrate

# Seed database
Write-Host "🌱 Seeding database..." -ForegroundColor Yellow
docker-compose exec app php artisan db:seed

# Clear and cache configurations
Write-Host "🔄 Clearing and caching configurations..." -ForegroundColor Yellow
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Create storage link
Write-Host "🔗 Creating storage link..." -ForegroundColor Yellow
docker-compose exec app php artisan storage:link

# Set permissions
Write-Host "📝 Setting permissions..." -ForegroundColor Yellow
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

Write-Host "✅ Docker environment initialized successfully!" -ForegroundColor Green
Write-Host "🌐 Application is running at: http://localhost:8000" -ForegroundColor Cyan
