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
    // لیست کردن فایل‌های درون سبد
    $result = $s3->listObjectsV2([
        'Bucket' => $bucketName
    ]);

    // چک کردن آیا فایلی وجود دارد یا خیر
    if (!empty($result['Contents'])) {
        foreach ($result['Contents'] as $object) {
            $objectKey = $object['Key'];
            $command = $s3->getCommand('GetObject', [
                'Bucket' => $bucketName,
                'Key' => $objectKey
            ]);

            $request = $s3->createPresignedRequest($command, '+1 hour');

            // دریافت لینک موقت
            $presignedUrl = (string)$request->getUri();
            echo "Presigned URL for '$objectKey':\n$presignedUrl\n\n";
        }
    } else {
        echo "The bucket is empty.\n";
    }
} catch (AwsException $e) {
    echo $e->getMessage() . "\n";
}
