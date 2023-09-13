<?php

namespace App\Controller;

class DotEnvEnvironment
{
   public function load($path): void
   {
       $lines = file($path . '/.env');
       foreach ($lines as $line) {
           [$key, $value] = explode('=', $line, 2);
           $key = trim($key);
           $value = trim($value);

           putenv(sprintf('%s=%s', $key, $value));
           $_ENV[$key] = $value;
           $_SERVER[$key] = $value;
       }
   }
}

require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

(new DotEnvEnvironment)->load(__DIR__);

// Setting Env Variables 
$accessKey  = getenv("LIARA_ACCESS_KEY");
$secretKey  = getenv("LIARA_SECRET_KEY");
$endpoint   = getenv("LIARA_ENDPOINT");
$bucketName = getenv("LIARA_BUCKET_NAME");

// making connection using s3
$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1',
    'endpoint' => $endpoint,
    'credentials' => [
        'key'    => $accessKey,
        'secret' => $secretKey,
    ],
]);

try {
    // listing files of bucket
    $result = $s3->listObjectsV2([
        'Bucket' => $bucketName
    ]);

    // Check if the bucket is empty
    if (empty($result['Contents'])) {
        echo "The bucket is empty.\n";
    } else {
        // printing the result
        foreach ($result['Contents'] as $object) {
            echo $object['Key'] . PHP_EOL;
        }
    }
} catch (AwsException $e) {
    // if not:
    echo $e->getMessage() . PHP_EOL;
}
