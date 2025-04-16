<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$s3Client = new S3Client([
    'version' => 'latest',
    'region' => $_ENV['S3_REGION'],
    'endpoint' => $_ENV['S3_ENDPOINT'],
    'credentials' => [
        'key' => $_ENV['S3_ACCESS_KEY'],
        'secret' => $_ENV['S3_SECRET_KEY'],
    ],
]);

$bucket = $_ENV['BUCKET_NAME'];
$endpoint = rtrim($_ENV['S3_ENDPOINT'], '/'); // Remove trailing slash if present

// List files in the bucket
try {
    $result = $s3Client->listObjectsV2(['Bucket' => $bucket]);
} catch (AwsException $e) {
    echo "<p style='color:red;'>Error listing files: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S3 Storage Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .action { margin-bottom: 20px; }
        .file-list { margin-top: 20px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>S3 Storage Management</h1>

    <!-- Upload Form -->
    <div class="action">
        <h2>Upload File</h2>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            File: <input type="file" name="file" required><br><br>
            <button type="submit">Upload</button>
        </form>
    </div>

    <!-- File List -->
    <div class="file-list">
        <h2>Files in Bucket: <?php echo htmlspecialchars($bucket); ?></h2>
        <?php if (isset($result['Contents'])): ?>
            <ul>
                <?php foreach ($result['Contents'] as $object): ?>
                    <?php $fileKey = htmlspecialchars($object['Key']); ?>
                    <?php $permanentUrl = "$endpoint/$bucket/$fileKey"; ?>
                    <li>
                        <strong><?php echo $fileKey; ?></strong>
                        [<a href="delete.php?key=<?php echo urlencode($fileKey); ?>">Delete</a>]
                        [<a href="pre-signed-url.php?key=<?php echo urlencode($fileKey); ?>">Pre-Signed URL</a>]
                        [<a href="<?php echo htmlspecialchars($permanentUrl); ?>" target="_blank">Permanent URL</a>]
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No files found in the bucket.</p>
        <?php endif; ?>
    </div>
</body>
</html>