# Laravel Docker Setup

This Laravel application is containerized using Docker for easy deployment.

## Prerequisites

- Docker
- Docker Compose

## Local Development

1. Clone the repository
2. Navigate to the project directory
3. Copy environment file:
   ```bash
   cp .env.example .env
   ```
4. Build and run the containers:
   ```bash
   docker-compose up -d --build
   ```
5. Install dependencies and set up the application:
   ```bash
   docker-compose exec app composer install
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate
   ```

The application will be available at `http://localhost`

## GCP VM Deployment

1. **Prepare your GCP VM:**
   - Create a VM instance with Ubuntu 20.04 or later
   - Install Docker and Docker Compose:
     ```bash
     # Install Docker
     curl -fsSL https://get.docker.com -o get-docker.sh
     sh get-docker.sh
     
     # Install Docker Compose
     sudo curl -L "https://github.com/docker/compose/releases/download/v2.20.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
     sudo chmod +x /usr/local/bin/docker-compose
     ```

2. **Deploy the application:**
   ```bash
   # Clone your repository
   git clone <your-repository-url>
   cd website-app
   
   # Copy the production environment file
   cp .env.production .env
   
   # Make deploy script executable
   chmod +x deploy.sh
   
   # Run deployment
   ./deploy.sh
   ```

3. **Configure GCP Firewall:**
   - Allow HTTP traffic (port 80) in your GCP firewall rules
   - The application will be accessible via your VM's external IP address

## Environment Variables

Update the `.env` file with your production settings:

- `APP_URL`: Your domain or IP address
- `APP_ENV`: Set to `production`
- `APP_DEBUG`: Set to `false`
- `DB_*`: Database configuration (uses Docker MySQL by default)

## Services

- **Web Server**: Apache with PHP 8.2
- **Database**: MySQL 8.0
- **Port**: 80 (HTTP)

## Useful Commands

```bash
# View logs
docker-compose logs -f app

# Access application container
docker-compose exec app bash

# Run Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear

# Stop containers
docker-compose down

# Restart containers
docker-compose restart
```

## File Structure

```
├── Dockerfile                 # Main application container
├── docker-compose.yml         # Docker services configuration
├── docker/
│   └── apache-laravel.conf   # Apache virtual host configuration
├── .dockerignore             # Files to ignore in Docker build
├── .env.production           # Production environment template
└── deploy.sh                 # Deployment script
```
