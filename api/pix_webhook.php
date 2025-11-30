<?php
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Verificar token de webhook se configurado
$webhookToken = getenv('WEBHOOK_TOKEN') ?: 'token aqui ';
if ($webhookToken) {
    $receivedToken = $_SERVER['HTTP_X_WEBHOOK_TOKEN'] ?? $_GET['token'] ?? '';
    if ($receivedToken !== $webhookToken) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

// Recebe atualizações do gateway
http_response_code(200);
$raw = file_get_contents('php://input');

// Log do webhook usando a função do bootstrap
log_status("WEBHOOK RECEIVED: " . $raw);

$data = json_decode($raw, true) ?: [];
$status = $data['status'] ?? '';
$transactionId = $data['id'] ?? $data['transactionId'] ?? '';

// Atualiza snapshot do PIX
if (!empty($transactionId)) {
    $snapshot = load_snapshot($transactionId);
    if ($snapshot) {
        $snapshot['status'] = $status;
        $snapshot['webhook_received_at'] = date('c');
        $snapshot['webhook_data'] = $data;
        save_snapshot($transactionId, $snapshot);
        
        log_status("SNAPSHOT UPDATED: $transactionId -> $status");
    }
}

// Se o pagamento foi aprovado, atualiza no sistema principal
if ($status === 'paid' && !empty($transactionId)) {
    // Carrega funções do sistema principal
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../includes/functions.php';
    
    $transaction = getTransaction($transactionId);
    
    if ($transaction && $transaction['status'] !== 'paid') {
        // Atualiza transação
        $transaction['status'] = 'paid';
        $transaction['paid_at'] = date('Y-m-d H:i:s');
        updateTransaction($transaction);
        
        log_status("TRANSACTION UPDATED: $transactionId marked as paid");
        
        // Adiciona produto ao usuário
        $products = loadProducts();
        $product = $products[$transaction['product_id']] ?? null;
        
        if ($product) {
            // Adiciona cursos
            if (!empty($product['courses'])) {
                foreach ($product['courses'] as $courseId) {
                    addCourseToUser($transaction['user_id'], $courseId);
                }
                log_status("COURSES ADDED: " . count($product['courses']) . " courses to user " . $transaction['user_id']);
            }
            
            // Adiciona receitas
            if (!empty($product['recipes'])) {
                foreach ($product['recipes'] as $recipeId) {
                    addRecipeToUser($transaction['user_id'], $recipeId);
                }
                log_status("RECIPES ADDED: " . count($product['recipes']) . " recipes to user " . $transaction['user_id']);
            }
            
            // Adiciona compra ao histórico
            addPurchaseToUser($transaction['user_id'], [
                'transaction_id' => $transactionId,
                'product_id' => $transaction['product_id'],
                'product_title' => $product['title'],
                'value' => $transaction['value'],
                'date' => date('Y-m-d H:i:s')
            ]);
            
            log_status("PURCHASE COMPLETED: User " . $transaction['user_id'] . " received product " . $transaction['product_id']);
        }
    }
}

echo json_encode(['status' => 'received', 'processed' => true, 'transaction_id' => $transactionId]);
