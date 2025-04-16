<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$bucket = $_GET['bucket'] ?? $_ENV['BUCKET_NAME']; // Use GET or default

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
    if ($bucket) {
        $result = $s3Client->listObjectsV2(['Bucket' => $bucket]);
        echo "<h1>Files in Bucket: $bucket</h1>";
        foreach ($result['Contents'] as $object) {
            echo "- {$object['Key']}<br>";
        }
    } else {
        echo "<h1>List Files</h1>";
        echo "<form method='GET'>";
        echo "Bucket Name (Default: {$_ENV['BUCKET_NAME']}): ";
        echo "<input type='text' name='bucket' value='{$_ENV['BUCKET_NAME']}'><br><br>";
        echo "<button type='submit'>List Files</button>";
        echo "</form>";
    }
} catch (AwsException $e) {
    echo "Error listing files: " . $e->getMessage();
}
?>
<a href