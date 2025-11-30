<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = getUserById($_SESSION['user_id']);
$purchases = getUserPurchases($_SESSION['user_id']);

usort($purchases, function($a, $b) {
    return strtotime($b['date'] ?? $b['created_at'] ?? 0) - strtotime($a['date'] ?? $a['created_at'] ?? 0);
});
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Compras - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="dashboard-container">
        <div class="dashboard-sidebar">
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            
            <nav class="dashboard-nav">
                <a href="dashboard.php">Meus Cursos</a>
                <a href="my-recipes.php">Minhas Receitas</a>
                <a href="my-purchases.php" class="active">Minhas Compras</a>
                <a href="profile.php">Meu Perfil</a>
                <a href="logout.php">Sair</a>
            </nav>
        </div>
        
        <div class="dashboard-content">
            <h1>Minhas Compras</h1>
            
            <div class="purchases-summary">
                <div class="stat-card">
                    <h3><?php echo count($purchases); ?></h3>
                    <p>Total de Compras</p>
                </div>
                <div class="stat-card">
                    <h3>R$ <?php 
                        $total = array_sum(array_column($purchases, 'amount'));
                        echo number_format($total, 2, ',', '.');
                    ?></h3>
                    <p>Valor Total Investido</p>
                </div>
            </div>
            
            <?php if (empty($purchases)): ?>
                <div class="empty-state">
                    <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <h3>Nenhuma compra realizada</h3>
                    <p>Você ainda não realizou nenhuma compra. <a href="index.php#products">Confira nossos programas!</a></p>
                </div>
            <?php else: ?>
                <div class="purchases-list">
                    <?php foreach ($purchases as $purchase): 
                        $product = getProduct($purchase['product_id']);
                        $statusClass = $purchase['status'] === 'approved' ? 'status-approved' : 
                                      ($purchase['status'] === 'pending' ? 'status-pending' : 'status-cancelled');
                        $statusText = $purchase['status'] === 'approved' ? 'Aprovado' : 
                                     ($purchase['status'] === 'pending' ? 'Pendente' : 'Cancelado');
                    ?>
                        <div class="purchase-card">
                            <div class="purchase-image">
                                <?php if ($product): ?>
                                    <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <div class="placeholder-image">Produto</div>
                                <?php endif; ?>
                            </div>
                            <div class="purchase-details">
                                <h3><?php echo htmlspecialchars($product['name'] ?? 'Produto'); ?></h3>
                                <p class="purchase-date">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <?php echo date('d/m/Y H:i', strtotime($purchase['date'] ?? $purchase['created_at'])); ?>
                                </p>
                                <p class="purchase-transaction">
                                    ID: <?php echo htmlspecialchars($purchase['transaction_id'] ?? $purchase['id'] ?? 'N/A'); ?>
                                </p>
                            </div>
                            <div class="purchase-status">
                                <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                <p class="purchase-amount">R$ <?php echo number_format($purchase['amount'], 2, ',', '.'); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>
