<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';
require_once 'api/bootstrap.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit;
}

$productId = $_POST['product_id'] ?? '';
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$cpf = cleanCPF($_POST['cpf'] ?? '');
$phone = cleanPhone($_POST['phone'] ?? '');

// Validações
if (empty($productId) || empty($name) || empty($email) || empty($cpf)) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

// Carrega produto
$products = loadProducts();
if (!isset($products[$productId])) {
    echo json_encode(['success' => false, 'message' => 'Produto não encontrado']);
    exit;
}

$product = $products[$productId];

// Cria ou atualiza usuário
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    if (userExists($cpf)) {
        $user = getUserByCPF($cpf);
        $userId = $user['id'];
    } else {
        $user = registerUser($name, $email, $cpf, $phone, '123456');
        $userId = $user['id'];
    }
}

// Salva dados na sessão
$_SESSION['checkout_data'] = [
    'user_id' => $userId,
    'product_id' => $productId,
    'product' => $product
];

try {
    $amountValue = floatval($product['final_price']);
    $formattedAmount = number_format($amountValue, 2, '.', '');
    
    $cleanPhone = only_digits($phone);
    // Se não começar com 55, adiciona o DDI do Brasil
    if (strlen($cleanPhone) === 10 || strlen($cleanPhone) === 11) {
        $cleanPhone = '55' . $cleanPhone;
    }
    
    $cleanCpf = only_digits($cpf);
    
    $logFile = __DIR__ . '/data/payment_debug.log';
    $debugInfo = [
        'timestamp' => date('Y-m-d H:i:s'),
        'raw_data' => [
            'product_id' => $productId,
            'name' => $name,
            'email' => $email,
            'cpf_original' => $_POST['cpf'] ?? '',
            'phone_original' => $_POST['phone'] ?? '',
            'product_price' => $product['final_price']
        ],
        'processed_data' => [
            'amount_value' => $amountValue,
            'formatted_amount' => $formattedAmount,
            'clean_phone' => $cleanPhone,
            'phone_length' => strlen($cleanPhone),
            'clean_cpf' => $cleanCpf,
            'cpf_length' => strlen($cleanCpf)
        ]
    ];
    
    file_put_contents($logFile, "\n\n=== DEBUG LOG ===\n" . json_encode($debugInfo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), FILE_APPEND);
    
    $payload = [
        'amount' => $formattedAmount,
        'description' => 'Entrada - ' . $product['name'],
        'customer' => [
            'name' => $name,
            'email' => $email,
            'phone' => $cleanPhone,
            'document' => [
                'type' => 'cpf',
                'number' => $cleanCpf
            ]
        ]
    ];
    
    file_put_contents($logFile, "\n\nPAYLOAD ENVIADO:\n" . json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
    
    log_create("Criando pagamento PIX: " . json_encode($payload));
    
    // Chama API Anubis Pay
    list($code, $json, $body, $errno, $err) = curl_json('POST', API_ENDPOINT_CREATE, $payload);
    
    file_put_contents($logFile, "\nRESPOSTA API (HTTP $code):\n" . $body . "\n", FILE_APPEND);
    
    log_create("Resposta API (HTTP $code): $body");
    
    if ($errno) {
        throw new Exception("Erro na comunicação com gateway: $err");
    }
    
    if ($code < 200 || $code >= 300) {
        $errorMsg = $json['message'] ?? $json['error'] ?? "Erro HTTP $code";
        throw new Exception("Gateway retornou erro: $errorMsg");
    }
    
    $data = $json['data'] ?? $json['charge'] ?? $json;
    
    if (!is_array($data) || empty($data['id'])) {
        throw new Exception("Resposta inválida do gateway");
    }
    
    // Salva snapshot da transação
    $txid = $data['id'];
    save_snapshot($txid, $json);
    
    $pixCode = $data['pix']['qrcode'] ?? $data['qrcode'] ?? $data['copiaECola'] ?? '';
    
    // Salva transação no sistema
    $transaction = [
        'id' => $txid,
        'user_id' => $userId,
        'product_id' => $productId,
        'value' => $product['final_price'],
        'status' => 'pending',
        'pix_code' => $pixCode,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    saveTransaction($transaction);
    
    log_create("Transação salva com sucesso: $txid");
    
    // Retorna sucesso
    echo json_encode([
        'success' => true,
        'transaction_id' => $txid,
        'qrcode' => SITE_URL . '/api/pix/qrcode.php?id=' . urlencode($txid),
        'pix_code' => $pixCode,
        'value' => number_format($product['final_price'], 2, ',', '.')
    ]);
    
} catch (Exception $e) {
    log_create("ERRO: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
