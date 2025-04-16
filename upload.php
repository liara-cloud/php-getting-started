<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $bucket = $_ENV['BUCKET_NAME'];
    $file = $_FILES['file'];

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
        $result = $s3Client->putObject([
            'Bucket' => $bucket,
            'Key' => $file['name'],
            'SourceFile' => $file['tmp_name'],
        ]);
        header("Location: s3-storage.php");
        exit();
    } catch (AwsException $e) {
        echo "Error uploading file: " . $e->getMessage();
    }
}
?>