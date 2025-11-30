<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

$transactionId = $_GET['transaction_id'] ?? '';

if (empty($transactionId)) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

// Carrega transação
$transaction = getTransaction($transactionId);

if (!$transaction) {
    echo json_encode(['success' => false, 'message' => 'Transação não encontrada']);
    exit;
}

try {
    // Verifica se já está pago no nosso sistema
    if ($transaction['status'] === 'paid') {
        echo json_encode([
            'success' => true,
            'status' => 'paid'
        ]);
        exit;
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, SITE_URL . '/api/pix/check_status.php?id=' . urlencode($transactionId));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $statusResponse = json_decode($response, true);
        
        $data = $statusResponse['data'] ?? $statusResponse['cached'] ?? null;
        $pixStatus = null;
        
        if ($data) {
            $pixStatus = strtolower($data['status'] ?? 'pending');
        }
        
        if ($pixStatus === 'paid' && $transaction['status'] !== 'paid') {
            // Atualiza transação
            $transaction['status'] = 'paid';
            $transaction['paid_at'] = date('Y-m-d H:i:s');
            updateTransaction($transaction);
            
            // Verifica se usuário já existe pelo CPF
            $cpf = cleanCPF($transaction['customer_cpf']);
            $existingUser = getUserByCPF($cpf);
            
            $userId = null;
            
            if ($existingUser) {
                // Usuário já existe, usa o ID existente
                $userId = $existingUser['id'];
            } else {
                // Cria novo usuário automaticamente
                // Login: CPF (sem formatação)
                // Senha: 12345678
                $newUser = registerUser(
                    $transaction['customer_name'],
                    $transaction['customer_email'],
                    $cpf,
                    $transaction['customer_phone'],
                    '12345678' // Senha padrão
                );
                
                if ($newUser) {
                    $userId = $newUser['id'];
                }
            }
            
            if ($userId) {
                // Adiciona produto ao usuário
                $products = loadProducts();
                $product = $products[$transaction['product_id']];
                
                // Adiciona cursos
                if (!empty($product['courses'])) {
                    foreach ($product['courses'] as $courseId) {
                        addCourseToUser($userId, $courseId);
                    }
                }
                
                // Adiciona receitas
                if (!empty($product['recipes'])) {
                    foreach ($product['recipes'] as $recipeId) {
                        addRecipeToUser($userId, $recipeId);
                    }
                }
                
                // Adiciona compra ao histórico
                addPurchaseToUser($userId, [
                    'transaction_id' => $transactionId,
                    'product_id' => $transaction['product_id'],
                    'product_title' => $product['title'],
                    'value' => $transaction['value'],
                    'date' => date('Y-m-d H:i:s')
                ]);
                
                // Atualiza transação com user_id
                $transaction['user_id'] = $userId;
                updateTransaction($transaction);
            }
        }
        
        echo json_encode([
            'success' => true,
            'status' => $transaction['status']
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'status' => $transaction['status']
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
