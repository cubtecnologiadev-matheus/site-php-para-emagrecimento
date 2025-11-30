<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/transactions.php';
require_once __DIR__ . '/products.php';

// Funções auxiliares do sistema (mantidas para compatibilidade)

// Carregar dados JSON
function loadJSON($file) {
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    return json_decode($content, true) ?: [];
}

// Salvar dados JSON
function saveJSON($file, $data) {
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Validar CPF
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    if (strlen($cpf) != 11) {
        return false;
    }
    
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

// Criar usuário (mantido para compatibilidade)
function createUser($cpf, $nome, $email, $telefone, $senha = '123456') {
    return registerUser($nome, $email, cleanCPF($cpf), cleanPhone($telefone), $senha);
}

// Autenticar usuário (mantido para compatibilidade)
function authenticateUser($cpf, $senha) {
    return loginUser(cleanCPF($cpf), $senha);
}

// Criar pedido (mantido para compatibilidade)
function createOrder($userId, $productId, $amount, $transactionId) {
    $transaction = [
        'id' => $transactionId,
        'user_id' => $userId,
        'product_id' => $productId,
        'value' => $amount,
        'status' => 'pending',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    return saveTransaction($transaction);
}

// Atualizar status do pedido (mantido para compatibilidade)
function updateOrderStatus($transactionId, $status) {
    $transaction = getTransaction($transactionId);
    if ($transaction) {
        $transaction['status'] = $status;
        $transaction['updated_at'] = date('Y-m-d H:i:s');
        return updateTransaction($transaction);
    }
    return false;
}

// Gerar nome brasileiro aleatório (mantido para compatibilidade)
function getRandomBrazilianName() {
    return generateRandomName();
}

// Gerar cidade brasileira aleatória (mantido para compatibilidade)
function getRandomBrazilianCity() {
    return generateRandomCity();
}

// Sanitizar entrada (mantido para compatibilidade)
function sanitize($data) {
    return sanitizeInput($data);
}

// Verificar se usuário está logado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirecionar
function redirect($url) {
    header("Location: $url");
    exit;
}
