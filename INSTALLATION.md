# Floci Installation & Setup Guide

## Complete Guide to Setting Up Local AWS Services (RDS, S3, SQS) with Floci

This guide will walk you through setting up a complete local development environment for your Laravel application using Floci in Docker, providing AWS services (RDS PostgreSQL, S3, SQS) without needing a real AWS account.

---

## Quick Start

### 1. Configure Domains in `.env`

Copy `.env.example` to `.env` and edit the domain values:

```bash
cp .env.example .env
```

Two separate domains are used (defaults are `localhost` for safety):

| Variable | Purpose | Example |
|----------|---------|---------|
| `APP_URL` | Your Laravel app + S3 bucket URL | `https://stg.example.com` |
| `FLOCI_DOMAIN` | Floci UI console (separate subdomain) | `floci.example.com` |

All other variables (`AWS_URL`, `AWS_ENDPOINT`, `ASSET_URL`, etc.) auto-resolve from these values.

All config files use `localhost` as defaults — no real domains exposed in git.

### 2. Start Services

```bash
docker compose up -d
```

### 3. Initialize AWS Services

```bash
bash init-aws.sh
```

### 4. Run Database Migrations

```bash
docker exec laravel-php php artisan migrate --force
```

---

## Table of Contents

1. [Prerequisites](#1-prerequisites)
2. [Configure Laravel Environment (.env)](#2-configure-laravel-environment-env)
3. [Docker Compose Configuration](#3-docker-compose-configuration)
4. [Start Services](#4-start-services)
5. [Initialize AWS Services](#5-initialize-aws-services)
6. [Configure Nginx Reverse Proxy](#6-configure-nginx-reverse-proxy)
7. [Troubleshooting](#7-troubleshooting)

---

## 1. Prerequisites

- Docker & Docker Compose installed
- An existing Laravel project
- Nginx installed on host (for reverse proxy)
- Basic Linux command line knowledge

---

## 2. Configure Laravel Environment (.env)

Add the following to your `.env` file. **Only `APP_URL` and `FLOCI_DOMAIN` need changing** for different domains:

```bash
# ============================================
# DOMAIN CONFIGURATION - Change these two lines
# ============================================
APP_URL=https://your-app-domain.com           # Your Laravel app + S3
FLOCI_DOMAIN=floci.your-domain.com            # Floci UI console (separate subdomain)

# ============================================
# Laravel Application
# ============================================
APP_NAME=laravel
APP_ENV=local
APP_KEY=base64:YOUR_APP_KEY_HERE      # Generate with: php artisan key:generate
APP_DEBUG=true
ASSET_URL="${APP_URL}"
FILAMENT_ASSETS_PATH="${APP_URL}"

# ============================================
# Database - Connect to Floci RDS
# ============================================
DB_CONNECTION=pgsql
DB_HOST="${APP_NAME}-floci"
DB_PORT=7003
DB_DATABASE=ecom
DB_USERNAME=postgres
DB_PASSWORD=123123

FILESYSTEM_DISK=s3

# ============================================
# S3 - Uses APP_URL automatically
# ============================================
AWS_DEFAULT_REGION=us-east-1
AWS_ACCESS_KEY_ID=test
AWS_SECRET_ACCESS_KEY=test
AWS_BUCKET=ecom-bucket
AWS_URL="${APP_URL}/s3/ecom-bucket"    # Auto-generated from APP_URL
AWS_ENDPOINT="${APP_URL}/s3"           # Auto-generated from APP_URL
AWS_USE_PATH_STYLE_ENDPOINT=true

# ============================================
# SQS Queue
# ============================================
QUEUE_CONNECTION=sqs
SQS_ENDPOINT=http://${APP_NAME}-floci:4566
SQS_PREFIX=${SQS_ENDPOINT}/000000000000
SQS_QUEUE=default

# ============================================
# Floci Core Settings
# ============================================
FLOCI_PORT=4566
RDS_INSTANCE_IDENTIFIER="${APP_NAME}-db"
RDS_ENGINE="${POSTGRES_USER}"
RDS_ENGINE_VERSION=18.4
RDS_ALLOCATED_STORAGE=20
RDS_BACKUP_RETENTION=7
```

### How Variables Flow

```
.env                      docker-compose.yml          Result
─────                     ─────────────────           ─────────────
APP_URL=https://...   ──► ${APP_URL}/s3           ──► AWS_ENDPOINT (Laravel app)
                      ──► ${APP_URL}/s3/ecom-bucket─► AWS_URL (S3 public URL)
FLOCI_DOMAIN=floci... ──► ${FLOCI_DOMAIN}          ──► VITE_API_URL, VITE_APP_URL (Floci UI)
```

**To change domains**, edit 2 lines in `.env`:
```bash
APP_URL=https://new-app-domain.com         # Changes S3, assets, app URL
FLOCI_DOMAIN=floci.new-domain.com          # Changes Floci UI URL
```

---

## 3. Docker Compose Configuration

### Service Overview

```yaml
services:
  php:          # Laravel PHP Application (port 9000)
  floci:        # Floci Core - AWS Emulator (port 4566)
  floci-api:    # Floci REST API (port 4501)
  floci-ui:     # Floci Web Console (port 4500)
  postgres:     # Optional - Standalone PostgreSQL
```

### Key Configuration Points

**Floci Core** requires Docker socket access for RDS:
```yaml
volumes:
  - /var/run/docker.sock:/var/run/docker.sock:rw
environment:
  - DOCKER_HOST=unix:///var/run/docker.sock
```

**PHP** service uses variable references from `.env`:
```yaml
environment:
  AWS_URL: ${APP_URL}/s3/ecom-bucket
  AWS_ENDPOINT: ${APP_URL}/s3
```

**Floci UI** uses `FLOCI_DOMAIN` from `.env`:
```yaml
environment:
  - VITE_API_URL=https://${FLOCI_DOMAIN}/api
  - VITE_APP_URL=https://${FLOCI_DOMAIN}
```

---

## 4. Start Services

```bash
# Start all services
docker compose up -d

# Check health
curl -s http://127.0.0.1:4566/health | grep -o '"rds":"running"'

# View logs
docker logs laravel-floci --tail 20
```

---

## 5. Initialize AWS Services

Run the initialization script **once** after starting Floci:

```bash
bash init-aws.sh
```

This script uses the Docker `amazon/aws-cli` image (no local installation needed) to:

1. **Create RDS Instance** - Floci spawns a PostgreSQL 16.3 container
2. **Create S3 Bucket** - `ecom-bucket`
3. **Create SQS Queue** - `default`

Then run Laravel migrations:

```bash
docker exec laravel-php php artisan migrate --force
```

---

## 6. Configure Nginx Reverse Proxy

Add a `/s3/` location block to your Laravel app's Nginx config:

```nginx
server {
    server_name your-domain.com;
    
    # Laravel PHP config...
    
    # S3 Proxy - Routes /s3/* to Floci (port 4566)
    location ^~ /s3/ {
        allow all;
        modsecurity off;
        rewrite ^/s3/(.*) /$1 break;
        proxy_pass http://127.0.0.1:4566;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        
        # CORS headers
        add_header Access-Control-Allow-Origin $http_origin always;
        add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS" always;
        add_header Access-Control-Allow-Headers "Content-Type, Authorization, X-Amz-*, X-Requested-With" always;
        add_header Access-Control-Expose-Headers "ETag, X-Amz-*" always;
        if ($request_method = OPTIONS) { return 204; }
        
        client_max_body_size 100M;
    }
}
```

Also configure a separate Nginx server block for Floci UI:

```nginx
server {
    server_name floci.your-domain.com;
    
    # Proxy API requests to floci-api (port 4501)
    location ^~ /api/ {
        proxy_pass http://127.0.0.1:4501;
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_connect_timeout 60s;
        proxy_send_timeout 600s;
        proxy_read_timeout 600s;
        proxy_http_version 1.1;
    }
    
    # Proxy all other requests to floci-ui (port 4500)
    location ^~ / {
        proxy_pass http://127.0.0.1:4500;
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }
}
```

---

## 7. Troubleshooting

### RDS Fails with Socket Error

```
java.net.SocketException: No such file or directory
```

**Fix:** Add Docker socket mount to `floci` service:
```yaml
volumes:
  - /var/run/docker.sock:/var/run/docker.sock:rw
environment:
  - DOCKER_HOST=unix:///var/run/docker.sock
```

### Database Connection Refused

```
SQLSTATE[08006] [7] connection refused
```

**Fix:** Floci RDS uses port **7003**, not 5432. Check `DB_PORT=7003` in `.env`.

### S3 Upload Fails in Browser

Laravel LIVE upload (Filament/Livewire) sends to the `AWS_ENDPOINT` URL. Make sure:
1. `AWS_ENDPOINT` in `.env` uses your public domain (`https://your-domain.com/s3`)
2. Nginx has the `/s3/` proxy location block
3. Content-Security-Policy allows `connect-src` to your domain

### Floci UI Shows "Not Connected"

1. Check env vars: `docker exec laravel-floci-ui env | grep VITE`
   - `VITE_API_URL` must be `https://floci.your-domain.com/api`
2. Check `/api/` is proxied to port 4501 in Nginx

### CSP Errors in Browser Console

Add to your Nginx `Content-Security-Policy` header's `connect-src`:
```
connect-src 'self' https://your-domain.com https://cdn.tailwindcss.com ...
```

---

## Architecture Overview

```
Browser ──https──► Nginx (port 443)
                      │
                      ├── /s3/* ──► Floci Core (127.0.0.1:4566) ──► S3 Bucket (ecom-bucket)
                      │
                      ├── / (Laravel) ──► PHP-FPM (127.0.0.1:9000)
                      │                       │
                      │                       ├── RDS ──► Floci Core ──► PostgreSQL container
                      │                       ├── S3  ──► Floci Core (internal)
                      │                       └── SQS ──► Floci Core
                      │
                      └── floci.your-domain.com
                              ├── /api/* ──► floci-api (127.0.0.1:4501)
                              └── /     ──► floci-ui  (127.0.0.1:4500)
```

---

**Done!** Your Laravel application now has a complete local AWS service environment.