<?php
$response = [
    'copyright' => 'Copyright (c) 2022 Yu Shi (Benjamin).',
    'contact' => 'Yu Shi (Benjamin Shi) shiyubnu@gmail.com',
    'repository' => 'https://github.com/benjamin-shi/gcplength-saas-php'
];
header('Content-Type: application/json;charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>