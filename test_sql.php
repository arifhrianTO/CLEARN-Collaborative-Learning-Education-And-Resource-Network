<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
try {
    $q = App\Models\Certificate::whereHas("enrollment", function($q) {
        $q->where("enrollments.student_id", 7);
    });
    echo $q->count();
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
