<?php
$api_url = "https://api.binance.com/api/v3/ticker/24hr";
$coins = ['BTCUSDT', 'ETHUSDT'];

$data = [];
foreach ($coins as $symbol) {
    $response = file_get_contents("$api_url?symbol=$symbol");

    // Check if the request was successful
    if ($response !== false) {
        $data[$symbol] = json_decode($response, true);
    } else {
        $data[$symbol] = ['error' => 'Failed to fetch data'];
    }
}

header('Content-Type: application/json');
echo json_encode($data);
