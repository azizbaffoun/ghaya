# 🚀 Production Deployment Guide

## Problem
The production server doesn't have npm installed, so you can't build assets directly on the server. You need to build locally and upload the build directory.

## Quick Fix

### Option 1: Using PowerShell Script (Windows)
```powershell
# 1. Make sure you have a fresh build
npm run build

# 2. Run the deployment script
.\deploy-assets.ps1
```

### Option 2: Manual Upload via SCP
```bash
# 1. Build assets locally
npm run build

# 2. Upload the build directory
scp -r -P 65002 public/build u935343949@82.112.232.99:/home/u935343949/domains/yakinmode.tn/public_html/public/
```

### Option 3: Using SFTP Client
1. Build assets: `npm run build`
2. Open SFTP client (FileZilla, WinSCP, etc.)
3. Connect to: `82.112.232.99:65002`
4. Navigate to: `/home/u935343949/domains/yakinmode.tn/public_html/public/`
5. Upload the entire `public/build` folder

## What Gets Uploaded

The `public/build` directory contains:
- `manifest.json` - Vite manifest file (required!)
- `assets/` - All compiled CSS and JS files

## Verification

After uploading, check that these files exist on the server:
```
/home/u935343949/domains/yakinmode.tn/public_html/public/build/manifest.json
/home/u935343949/domains/yakinmode.tn/public_html/public/build/assets/
```

## Automated Deployment Workflow

For future deployments, follow this workflow:

1. **Make code changes**
2. **Build assets locally**: `npm run build`
3. **Upload build directory** (use one of the methods above)
4. **Clear Laravel cache** (if needed):
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

## Troubleshooting

### Error: "Vite manifest not found"
- Make sure `public/build/manifest.json` exists on the server
- Check file permissions (should be readable: `chmod 644 public/build/manifest.json`)

### Error: "Assets not loading"
- Verify the `public/build/assets/` directory exists
- Check file permissions on the assets directory

### Build directory missing locally
Run `npm run build` to generate it.

## Notes

- The `public/build` directory is in `.gitignore` (correct - don't commit it)
- You must rebuild and upload after any changes to:
  - `resources/css/app.css`
  - `resources/js/*.js`
  - `vite.config.js`
  - `tailwind.config.js`


