# Laravel Docker Deployment

This Laravel application is containerized with Docker for easy deployment to GCP VM or any cloud platform.

## 🚀 Quick Start

### For GCP VM Deployment

1. **Create a GCP VM instance:**
   ```bash
   # Create VM with appropriate specs
   gcloud compute instances create laravel-app \
     --image-family=ubuntu-2004-lts \
     --image-project=ubuntu-os-cloud \
     --machine-type=e2-medium \
     --zone=your-zone \
     --tags=http-server,https-server
   ```

2. **Configure firewall (if needed):**
   ```bash
   gcloud compute firewall-rules create allow-http-80 \
     --allow tcp:80 \
     --source-ranges 0.0.0.0/0 \
     --target-tags http-server
   ```

3. **SSH into your VM and run setup:**
   ```bash
   # Clone your repository
   git clone https://github.com/your-username/your-repo.git
   cd your-repo/website-app
   
   # Make scripts executable
   chmod +x setup.sh deploy.sh
   
   # Run initial setup (installs Docker, Docker Compose, etc.)
   ./setup.sh
   
   # Log out and log back in (or run: newgrp docker)
   
   # Deploy the application
   ./deploy.sh
   ```

4. **Access your application:**
   - Main app: `http://YOUR_VM_EXTERNAL_IP`
   - phpMyAdmin: `http://YOUR_VM_EXTERNAL_IP:8080`

## 🛠️ Local Development

### Prerequisites
- Docker
- Docker Compose

### Setup
```bash
# Clone the repository
git clone <your-repo-url>
cd website-app

# Copy environment file
cp .env.example .env

# Build and start containers
docker-compose up -d --build

# Run Laravel setup
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

### Access
- Laravel App: http://localhost
- phpMyAdmin: http://localhost:8080

## 📂 Docker Structure

```
website-app/
├── Dockerfile              # Main application container
├── docker-compose.yml      # Multi-container setup
├── docker/
│   └── apache/
│       └── 000-default.conf # Apache virtual host config
├── setup.sh               # GCP VM initial setup
├── deploy.sh              # Application deployment
└── .env                   # Environment configuration
```

## 🔧 Configuration

### Environment Variables
Key environment variables in `.env`:
- `APP_URL`: Your application URL
- `DB_HOST=db`: Database container name
- `DB_DATABASE=laravel`: Database name
- `DB_USERNAME=laravel`: Database user
- `DB_PASSWORD=secret`: Database password

### Database
- **Engine**: MySQL 8.0
- **Host**: db (container name)
- **Port**: 3306
- **Database**: laravel
- **Username**: laravel
- **Password**: secret

## 🚦 Container Management

### Start containers
```bash
docker-compose up -d
```

### Stop containers
```bash
docker-compose down
```

### View logs
```bash
docker-compose logs -f
```

### Access application container
```bash
docker-compose exec app bash
```

### Access database container
```bash
docker-compose exec db mysql -u laravel -p
```

## 🔍 Troubleshooting

### Check container status
```bash
docker-compose ps
```

### Restart specific service
```bash
docker-compose restart app
```

### Rebuild containers
```bash
docker-compose up -d --build
```

### Clear Laravel cache
```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

## 🔒 Security Notes

For production deployment:
1. Change default database passwords
2. Set `APP_DEBUG=false` in `.env`
3. Use proper SSL certificates
4. Configure firewall rules appropriately
5. Regularly update Docker images

## 📊 Monitoring

### Container health
```bash
docker-compose ps
docker stats
```

### Application logs
```bash
docker-compose logs app
```

### Database logs
```bash
docker-compose logs db
```

## 🔄 Updates

To update the application:
```bash
git pull origin main
docker-compose down
docker-compose up -d --build
docker-compose exec app php artisan migrate --force
```
