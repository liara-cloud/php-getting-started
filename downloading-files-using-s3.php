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
    // نام فایل مورد نظر برای دانلود
    $fileName = 'example.txt';

    // مسیر برای ذخیره فایل در دایرکتوری downloads
    $downloadPath = __DIR__ . '/downloads/' . $fileName;

    // دانلود فایل از سبد
    $result = $s3->getObject([
        'Bucket' => $bucketName,
        'Key'    => $fileName,
        'SaveAs' => $downloadPath,
    ]);

    echo "File '$fileName' downloaded successfully.\n";
} catch (AwsException $e) {
    echo $e->getMessage() . "\n";
}
