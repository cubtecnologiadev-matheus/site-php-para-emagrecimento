<?php
session_start();

// Clear payment-related session data
unset($_SESSION['paymentConfirmed']);
unset($_SESSION['transactionId']);

echo json_encode(['status' => 'cleared']);
