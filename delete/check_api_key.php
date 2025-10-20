<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\FirstDeliverySetting;

echo "=== CHECK API KEY ===\n\n";

$settings = FirstDeliverySetting::getSettings();
echo "API Key: " . ($settings->first_delivery_key ? 'Set (' . strlen($settings->first_delivery_key) . ' chars)' : 'Not Set') . "\n";
echo "Enabled: " . ($settings->is_enabled ? 'Yes' : 'No') . "\n";
echo "Store Name: " . ($settings->store_name ?? 'N/A') . "\n";

echo "\n=== COMPLETE ===\n";