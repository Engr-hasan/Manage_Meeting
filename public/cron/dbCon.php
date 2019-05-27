<?php

require '../../vendor/autoload.php';
$app = require_once '../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

\Dotenv::load($app->environmentPath(), $app->environmentFile());

$DB_HOST=env('DB_HOST');
$DB_DATABASE=env('DB_DATABASE');
$DB_USERNAME=env('DB_USERNAME');
$DB_PASSWORD=env('DB_PASSWORD');
$mysqli = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>