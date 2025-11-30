<?php
// Funções de transações

function saveTransaction($transaction) {
    $transactions = loadTransactions();
    $transactions[$transaction['id']] = $transaction;
    
    $file = DATA_DIR . '/transactions.json';
    
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }
    
    $json = json_encode($transactions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($file, $json) !== false;
}

function getTransaction($transactionId) {
    $transactions = loadTransactions();
    return $transactions[$transactionId] ?? null;
}

function updateTransaction($transaction) {
    return saveTransaction($transaction);
}

function loadTransactions() {
    $file = DATA_DIR . '/transactions.json';
    
    if (!file_exists($file)) {
        return [];
    }
    
    $json = file_get_contents($file);
    return json_decode($json, true) ?? [];
}

function getUserTransactions($userId) {
    $transactions = loadTransactions();
    $userTransactions = [];
    
    foreach ($transactions as $transaction) {
        if ($transaction['user_id'] === $userId) {
            $userTransactions[] = $transaction;
        }
    }
    
    return $userTransactions;
}
