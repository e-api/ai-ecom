#!/bin/bash
# init-aws.sh - Initialize Floci AWS Services (RDS, S3, SQS)
# Run this script ONCE after starting Floci services with `docker compose up -d`
#
# How this works:
# We use the official amazon/aws-cli Docker image to send API requests to Floci.
# No need to install AWS CLI on your host machine - Docker handles it automatically.

set -e

AWS="docker run --rm --network baota_net \
  -e AWS_ACCESS_KEY_ID=test \
  -e AWS_SECRET_ACCESS_KEY=test \
  amazon/aws-cli \
  --endpoint-url=http://laravel-floci:4566"

echo "============================================"
echo "Step 1: Creating RDS PostgreSQL Instance..."
echo "============================================"
$AWS rds create-db-instance \
  --db-instance-identifier laravel-db \
  --db-instance-class db.t3.micro \
  --engine postgres \
  --master-username postgres \
  --master-user-password 123123 \
  --allocated-storage 20 \
  --db-name ecom \
  --region us-east-1 2>&1

echo ""
echo "============================================"
echo "Step 2: Creating S3 Bucket..."
echo "============================================"
$AWS s3 mb s3://ecom-bucket --region us-east-1 2>&1

echo ""
echo "============================================"
echo "Step 3: Creating SQS Queue..."
echo "============================================"
$AWS sqs create-queue --queue-name default --region us-east-1 2>&1

echo ""
echo "============================================"
echo "Verification: Checking created resources..."
echo "============================================"
echo ""
echo "--- RDS Instances ---"
$AWS rds describe-db-instances --region us-east-1 \
  --query "DBInstances[*].[DBInstanceIdentifier,DBInstanceStatus,Endpoint.Port]" \
  --output table 2>&1

echo ""
echo "--- S3 Buckets ---"
$AWS s3 ls 2>&1

echo ""
echo "--- SQS Queues ---"
$AWS sqs list-queues --region us-east-1 --output table 2>&1

echo ""
echo "============================================"
echo "✅ All AWS services initialized!"
echo "============================================"
echo ""
echo "Next: docker exec laravel-php php artisan migrate --force"
echo ""