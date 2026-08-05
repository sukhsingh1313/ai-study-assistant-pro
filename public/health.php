<?php

header('Content-Type: application/json');
http_response_code(200);

echo json_encode([
    'status' => 'OK',
    'uptime' => 'healthy',
    'timestamp' => date('c'),
]);
exit(0);
