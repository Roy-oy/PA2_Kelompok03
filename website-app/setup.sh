#!/bin/bash

# Setup script for deploying Laravel application on GCP VM

echo "🚀 Starting Laravel Docker deployment setup..."

# Update system packages
echo "📦 Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install Docker
echo "🐳 Installing Docker..."
sudo apt install -y apt-transport-https ca-certificates curl software-properties-common
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
sudo apt update
sudo apt install -y docker-ce

# Install Docker Compose
echo "📋 Installing Docker Compose..."
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Add current user to docker group
echo "👤 Adding user to docker group..."
sudo usermod -aG docker $USER

# Install Git if not installed
echo "📥 Installing Git..."
sudo apt install -y git

echo "✅ Basic setup completed!"
echo ""
echo "🔄 Please log out and log back in (or run 'newgrp docker') to use Docker without sudo"
echo ""
echo "📋 Next steps:"
echo "1. Clone your repository: git clone <your-repo-url>"
echo "2. Navigate to the project: cd <project-directory>/website-app"
echo "3. Run: ./deploy.sh"
echo ""
echo "🌐 Your Laravel app will be available at: http://YOUR_VM_IP"
