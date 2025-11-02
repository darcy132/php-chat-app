#!/bin/bash

echo "Starting PHP Chat Application with Docker..."

# 检查 Docker 是否安装
if ! command -v docker &> /dev/null; then
    echo "Docker is not installed. Please install Docker first."
    exit 1
fi




# 启动服务
docker compose up -d

echo "Waiting for services to start..."
sleep 30

# 检查服务状态
echo "Service status:"
docker compose ps

echo ""
echo "Application is running!"
echo "- Chat App: http://localhost:8080"
echo "- PHPMyAdmin: http://localhost:8081"
echo ""
echo "Default users:"
echo "- alice / password"
echo "- bob / password"
echo "- charlie / password"