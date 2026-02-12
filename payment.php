<?php
require_once "apple_pay_conf.php";

/* -------------------------------------------------
   READ INPUT
------------------------------------------------- */
$raw = file_get_contents('php://input');
$input = json_decode($raw, true);

if (!$input || empty($input['applePayToken'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing Apple Pay token']);
    exit;
}

/* -------------------------------------------------
   TOKEN: MUST BE OBJECT (CRITICAL)
------------------------------------------------- */
$applePayTokenObject = is_string($input['applePayToken'])
    ? json_decode($input['applePayToken'], true)
    : $input['applePayToken'];

if (!is_array($applePayTokenObject)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid Apple Pay token format']);
    exit;
}

/* -------------------------------------------------
   AMOUNT / CURRENCY
------------------------------------------------- */
$amount   = "2.00";
$currency = "SAR";

if ($amount <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

/* -------------------------------------------------
   CONFIG
------------------------------------------------- */
$url = URL;

$orderId = 'AP_' . date('YmdHis') . '_' . random_int(1000, 9999);

$terminalId  = trim((string) TERMINAL_ID);
$password    = trim((string) PASSWORD);
$merchantKey = trim((string) KEY);

$merchantIp = $_SERVER['SERVER_ADDR'] ?? '51.39.227.45';
$customerIp = $_SERVER['REMOTE_ADDR'] ?? '51.39.227.45';

/* -------------------------------------------------
   SIGNATURE (EXACTLY AS FLUTTER)
   orderId|terminalId|password|merchantKey|amount|currency
------------------------------------------------- */
$pipeSeparatedString =
    $orderId . '|' .
    $terminalId . '|' .
    $password . '|' .
    $merchantKey . '|' .
    $amount . '|' .
    $currency;

$requestHash = hash('sha256', $pipeSeparatedString);

/* -------------------------------------------------
   REQUEST PAYLOAD (MATCHES FLUTTER)
------------------------------------------------- */
$request = [
    "terminalId" => $terminalId,
    "password"  => $password,
    "signature" => $requestHash,
    "paymentType" => 1,

    "merchantIp" => $merchantIp,
    "customerIp" => $customerIp,

    "amount" => "2.00",
    "country" => "SA",
    "currency" => $currency,

    "order" => [
        "orderId" => $orderId
    ],

    "customer" => [
        "cardHolderName" => "John Deo",
        "customerEmail" => "merchant.autouser@gmail.com",
        "billingAddressStreet" => "",
        "billingAddressCountry" => "SA"
    ],

    // 🔥 MUST BE OBJECT
    "paymentToken" => $applePayTokenObject,

    "paymentInstrument" => [
        "paymentMethod" => "APPLEPAY"
    ]
];

/* -------------------------------------------------
   DEBUG LOG (VERY IMPORTANT)
------------------------------------------------- */
$logDir = __DIR__ . "/logs";
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

file_put_contents(
    $logDir . "/applepay_request_" . $orderId . ".json",
    json_encode([
        "signatureInput" => $pipeSeparatedString,
        "signatureHash" => $requestHash,
        "requestBody" => $request
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

/* -------------------------------------------------
   CURL CALL
------------------------------------------------- */
$payload = json_encode($request, JSON_UNESCAPED_SLASHES);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Content-Length: " . strlen($payload),
    ],
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
]);

$response = curl_exec($ch);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

/* -------------------------------------------------
   RESPONSE
------------------------------------------------- */
header('Content-Type: application/json');
echo $response;
