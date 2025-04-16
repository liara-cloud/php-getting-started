<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$key = $_GET['key'] ?? null;

if ($key) {
    $bucket = $_ENV['BUCKET_NAME'];

    $s3Client = new S3Client([
        'version' => 'latest',
        'region' => $_ENV['S3_REGION'],
        'endpoint' => $_ENV['S3_ENDPOINT'],
        'credentials' => [
            'key' => $_ENV['S3_ACCESS_KEY'],
            'secret' => $_ENV['S3_SECRET_KEY'],
        ],
    ]);

    try {
        $s3Client->deleteObject([
            'Bucket' => $bucket,
            'Key' => $key,
        ]);
        header("Location: s3-storage.php");
        exit();
    } catch (AwsException $e) {
        echo "Error deleting file: " . $e->getMessage();
    }
}
?>