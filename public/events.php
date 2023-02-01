<?php
$is_cli = PHP_SAPI === 'cli';

if (!$is_cli) {
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-store, no-cache');
    header('Access-Control-Allow-Origin: *');
}



$startNum = $_SERVER['HTTP_LAST_EVENT_ID'] ?? 0;
//printf("event: test\ndata: %s\n\n", $startNum);

while(true) {
    $_A = $startNum;
    $lineNumber = 0;
    $fp = fopen('database', 'r');
    while($line = fgets($fp))
    {
        $lineNumber++;
        if ($startNum < $lineNumber) {
            echo sprintf("data: %s\n", trim($line));
            echo "id: $lineNumber\n\n";
            if(!$is_cli) {
                ob_flush();
            }
            flush();
        }
    }
    fclose($fp);
    $startNum = $lineNumber;
    // if ($_A < $startNum) {
    //     echo sprintf("event: test\ndata: %s\n\n", $startNum);
    // }
    if(!$is_cli) {
        ob_flush();
    }
    flush();
    usleep(500000);
}
