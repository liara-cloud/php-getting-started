<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Aws\S3\S3Client;

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
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $key,
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+1 hour');
        $presignedUrl = (string)$request->getUri();
        echo "<p>Pre-signed URL: <a href='$presignedUrl'>$presignedUrl</a></p>";
    } catch (Exception $e) {
        echo "Error generating pre-signed URL: " . $e->getMessage();
    }
}
?>
<a href="s3-storage.php">Back to Dashboard</a>