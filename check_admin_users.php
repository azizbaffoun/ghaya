<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "Fetching all admin users...\n\n";

try {
    $admins = User::where('role', 'admin')->get(['id', 'name', 'email', 'role', 'created_at']);
    
    if ($admins->isEmpty()) {
        echo "No admin users found in the database.\n";
    } else {
        echo "Found " . $admins->count() . " admin user(s):\n\n";
        foreach ($admins as $admin) {
            echo "ID: {$admin->id}\n";
            echo "Name: {$admin->name}\n";
            echo "Email: {$admin->email}\n";
            echo "Role: {$admin->role}\n";
            echo "Created: {$admin->created_at}\n";
            echo str_repeat("-", 50) . "\n\n";
        }
        
        echo "\nAdmin Emails:\n";
        foreach ($admins as $admin) {
            echo "- {$admin->email}\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Make sure your database credentials in .env are correct.\n";
}
