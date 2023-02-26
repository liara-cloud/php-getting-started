<?php
$name = basename($_GET['name']);
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Content-Disposition: attachment; filename=\"" . $name . "\";");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize("uploads/" . $name));
ob_clean();
flush();
readfile("uploads/" . $name);
exit;
