<?php
// Arquivo para visualizar os logs de debug de pagamento
session_start();
require_once 'config/config.php';

// Apenas admin pode ver
if (!isset($_SESSION['user_id'])) {
    die('Acesso negado');
}

$logFile = __DIR__ . '/data/payment_debug.log';

header('Content-Type: text/plain; charset=utf-8');

if (file_exists($logFile)) {
    echo "=== LOGS DE DEBUG DE PAGAMENTO ===\n\n";
    echo file_get_contents($logFile);
} else {
    echo "Nenhum log encontrado ainda.\n";
    echo "Arquivo esperado: $logFile\n";
}
