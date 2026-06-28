<?php
$db_host = 'localhost'; // Usually 'localhost'
$db_name = 'kdlsqwnb_zece';
$db_user = 'kdlsqwnb_zeceadmin';
$db_pass = '89NyAfUz@BiNYEG';

// Data Source Name or DSN
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (\PDOException $e) {
    // In a real app, you would log this error, not show it to the user
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>