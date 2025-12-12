#!/bin/bash
# Bash script to deploy Vite assets to production server
# Usage: ./deploy-assets.sh

SERVER="u935343949@82.112.232.99"
PORT="65002"
REMOTE_PATH="/home/u935343949/domains/yakinmode.tn/public_html/public"
LOCAL_BUILD_PATH="public/build"

echo "🚀 Deploying Vite assets to production..."

# Check if build directory exists
if [ ! -d "$LOCAL_BUILD_PATH" ]; then
    echo "❌ Build directory not found! Run 'npm run build' first."
    exit 1
fi

# Check if manifest.json exists
if [ ! -f "$LOCAL_BUILD_PATH/manifest.json" ]; then
    echo "❌ manifest.json not found! Run 'npm run build' first."
    exit 1
fi

echo "✅ Build directory found"
echo "📦 Uploading build directory to server..."

# Use scp to upload the build directory
scp -r -P $PORT "$LOCAL_BUILD_PATH" "${SERVER}:${REMOTE_PATH}/"

if [ $? -eq 0 ]; then
    echo "✅ Assets deployed successfully!"
    echo "🌐 Your site should now work at https://yakinmode.tn"
else
    echo "❌ Upload failed. Check your SSH connection."
    exit 1
fi


