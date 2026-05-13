<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::find(1);
if ($user) {
    $user->username = 'AdminRoren';
    $user->password = Illuminate\Support\Facades\Hash::make('B10fLok@ror3n');
    $user->save();
    echo "User updated successfully";
} else {
    echo "User not found";
}
