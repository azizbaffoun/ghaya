# PowerShell script to deploy Vite assets to production server
# Usage: .\deploy-assets.ps1

$server = "u935343949@82.112.232.99"
$port = "65002"
$remotePath = "/home/u935343949/domains/yakinmode.tn/public_html/public"
$localBuildPath = "public\build"

Write-Host "🚀 Deploying Vite assets to production..." -ForegroundColor Cyan

# Check if build directory exists
if (-not (Test-Path $localBuildPath)) {
    Write-Host "❌ Build directory not found! Run 'npm run build' first." -ForegroundColor Red
    exit 1
}

# Check if manifest.json exists
if (-not (Test-Path "$localBuildPath\manifest.json")) {
    Write-Host "❌ manifest.json not found! Run 'npm run build' first." -ForegroundColor Red
    exit 1
}

Write-Host "✅ Build directory found" -ForegroundColor Green
Write-Host "📦 Uploading build directory to server..." -ForegroundColor Yellow

# Use scp to upload the build directory
scp -r -P $port "$localBuildPath" "${server}:${remotePath}/"

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Assets deployed successfully!" -ForegroundColor Green
    Write-Host "🌐 Your site should now work at https://yakinmode.tn" -ForegroundColor Cyan
} else {
    Write-Host "❌ Upload failed. Check your SSH connection." -ForegroundColor Red
    exit 1
}


