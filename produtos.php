<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programas de Treino - <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Conheça todos os nossos programas de treino para emagrecimento saudável">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="page-header">
        <!-- Movendo vídeos para serem background, antes do container -->
        <div class="header-video-background">
            <video class="header-video" autoplay loop muted playsinline>
                <source src="assets/images/videos/treino-demo.mp4" type="video/mp4">
            </video>
            <video class="header-video header-video-duplicate" autoplay loop muted playsinline>
                <source src="assets/images/videos/treino-demo.mp4" type="video/mp4">
            </video>
        </div>
        
        <!-- Container com texto agora sobrepõe os vídeos -->
        <div class="container">
            <h1>Nossos Programas de Treino</h1>
            <p>Escolha o programa perfeito para sua jornada de emagrecimento</p>
        </div>
    </section>

    <!-- Otimizando carregamento de produtos com lazy loading -->
    <section class="products-section">
        <div class="container">
            <div class="products-grid">
                <?php foreach ($PRODUCTS as $product): ?>
                <div class="product-card" onclick="window.location.href='produto.php?id=<?php echo $product['id']; ?>'" style="cursor: pointer;">
                    <?php if (!empty($product['badge'])): ?>
                    <div class="product-badge"><?php echo htmlspecialchars($product['badge']); ?></div>
                    <?php endif; ?>
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             loading="lazy"
                             width="300"
                             height="300">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="product-price">
                            <div class="price-wrapper">
                                <?php if (!empty($product['old_price'])): ?>
                                <span class="old-price">R$ <?php echo number_format($product['old_price'], 2, ',', '.'); ?></span>
                                <?php endif; ?>
                                <span class="price">R$ <?php echo number_format($product['final_price'], 2, ',', '.'); ?></span>
                            </div>
                            <!-- Alterando texto do botão de "Começar Agora" para "Ver Detalhes" -->
                            <a href="produto.php?id=<?php echo $product['id']; ?>" class="btn btn-buy" onclick="event.stopPropagation();">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2>Ainda tem dúvidas?</h2>
            <p>Entre em contato conosco e tire todas as suas dúvidas</p>
            <a href="contato.php" class="btn btn-primary">Falar com Especialista</a>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <a href="https://api.whatsapp.com/send?phone=<?php echo WHATSAPP_NUMBER; ?>&text=Olá!%20Gostaria%20de%20mais%20informações" 
       class="whatsapp-float" target="_blank" rel="noopener">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="assets/js/main.js"></script>
</body>
</html>
