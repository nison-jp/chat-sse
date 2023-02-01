<?php
header('Access-Control-Allow-Origin: *');
$content = json_decode(file_get_contents('php://input'), true);

if (!isset($content['name'], $content['content'])) {
    header('HTTP', true, 400);
    var_dump($content);
    exit();
}

$write = json_encode([
    'name' => $content['name'],
    'content' => $content['content'],
    'ipaddr' => md5((new DateTime('now', new DateTimeZone('Asia/Tokyo')))->format('Y-m-d') . gethostbyaddr($_SERVER['REMOTE_ADDR'])),
    'created_at' => (new DateTime('now', new DateTimeZone('Asia/Tokyo')))->format('Y-m-d H:i:s.u')
]);
$database = fopen('database', 'a');

@fwrite($database, $write . "\n");

fclose($database);

header('Content-Type: application/json');

echo $write;
