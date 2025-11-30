<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

$productId = $_GET['id'] ?? '';

if (empty($productId)) {
    header('Location: index.php');
    exit;
}

$products = loadProducts();
$product = $products[$productId] ?? null;

if (!$product) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?> - <?php echo SITE_NAME; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($product['description']); ?>">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="product-detail-container">
        <div class="container">
            <div class="product-detail-hero">
                <div class="product-detail-image">
                    <div class="product-image-wrapper">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" loading="lazy">
                    </div>
                    <?php if (isset($product['old_price']) && $product['old_price'] > $product['final_price']): ?>
                        <div class="product-discount-badge">
                            <span class="discount-percent"><?php echo $product['discount']; ?>%</span>
                            <span class="discount-text">OFF</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="product-detail-info">
                    <div class="product-category">
                        <i class="fas fa-dumbbell"></i> Programa de Treino
                    </div>
                    
                    <h1 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h1>
                    
                    <p class="product-description-main"><?php echo htmlspecialchars($product['description']); ?></p>
                    
                    <div class="product-price-section">
                        <?php if (isset($product['old_price']) && $product['old_price'] > $product['final_price']): ?>
                            <div class="price-comparison">
                                <span class="price-label">De:</span>
                                <span class="old-price-large"><?php echo formatMoney($product['old_price']); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="current-price-large">
                            <span class="price-label-main">Por apenas:</span>
                            <span class="currency">R$</span>
                            <span class="amount"><?php echo number_format($product['final_price'], 2, ',', '.'); ?></span>
                        </div>
                        <?php if (isset($product['old_price']) && $product['old_price'] > $product['final_price']): ?>
                            <div class="savings-badge">
                                <i class="fas fa-tag"></i> Economize <?php echo formatMoney($product['old_price'] - $product['final_price']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Moved buy button here -->
                    <a href="checkout.php?product=<?php echo $product['id']; ?>" class="btn-buy-large">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Comprar Agora</span>
                    </a>
                    
                    <div class="product-highlights">
                        <div class="highlight-item">
                            <i class="fas fa-bolt"></i>
                            <div>
                                <strong>Acesso Imediato</strong>
                                <span>Comece agora mesmo</span>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>Garantia 7 Dias</strong>
                                <span>100% do seu dinheiro de volta</span>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <i class="fas fa-headset"></i>
                            <div>
                                <strong>Suporte WhatsApp</strong>
                                <span>Tire suas dúvidas</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="product-security-badges">
                        <div class="security-item">
                            <i class="fas fa-lock"></i>
                            <span>Pagamento Seguro</span>
                        </div>
                        <div class="security-item">
                            <i class="fab fa-pix"></i>
                            <span>PIX Aprovação Instantânea</span>
                        </div>
                        <div class="security-item">
                            <i class="fas fa-certificate"></i>
                            <span>Certificado SSL</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="product-benefits-section">
                <h2 class="section-heading">
                    <i class="fas fa-check-circle"></i>
                    O Que Você Vai Receber
                </h2>
                <div class="benefits-list-detailed">
                    <?php if (!empty($product['courses'])): ?>
                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="benefit-content">
                                <h3><?php echo count($product['courses']); ?> Curso(s) Completo(s)</h3>
                                <p>Acesso vitalício a todos os módulos e aulas em vídeo de alta qualidade</p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($product['recipes'])): ?>
                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="benefit-content">
                                <h3><?php echo count($product['recipes']); ?> Receita(s) Exclusiva(s)</h3>
                                <p>Receitas fitness balanceadas para potencializar seus resultados</p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="benefit-content">
                            <h3>Acesso em Qualquer Dispositivo</h3>
                            <p>Treine onde e quando quiser: celular, tablet ou computador</p>
                        </div>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-infinity"></i>
                        </div>
                        <div class="benefit-content">
                            <h3>Acesso Vitalício</h3>
                            <p>Pague uma vez e tenha acesso para sempre, incluindo atualizações</p>
                        </div>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="benefit-content">
                            <h3>Comunidade Exclusiva</h3>
                            <p>Faça parte de um grupo motivado e focado em resultados</p>
                        </div>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="benefit-content">
                            <h3>Certificado de Conclusão</h3>
                            <p>Receba seu certificado ao completar o programa</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($product['courses'])): ?>
                <div class="product-courses-section">
                    <h2 class="section-heading">
                        <i class="fas fa-book-open"></i>
                        Cursos Inclusos no Programa
                    </h2>
                    <div class="courses-detailed-grid">
                        <?php
                        $courses = loadCourses();
                        foreach ($product['courses'] as $courseId):
                            $course = $courses[$courseId] ?? null;
                            if ($course):
                        ?>
                            <div class="course-detail-card">
                                <div class="course-header">
                                    <div class="course-icon">
                                        <i class="fas fa-play-circle"></i>
                                    </div>
                                    <div class="course-meta">
                                        <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                                        <span class="module-badge">
                                            <i class="fas fa-list"></i> <?php echo count($course['modules']); ?> módulos
                                        </span>
                                    </div>
                                </div>
                                <p class="course-description"><?php echo htmlspecialchars($course['description']); ?></p>
                                <div class="course-features">
                                    <div class="feature-tag">
                                        <i class="fas fa-video"></i> Vídeo Aulas HD
                                    </div>
                                    <div class="feature-tag">
                                        <i class="fas fa-file-pdf"></i> Material PDF
                                    </div>
                                    <div class="feature-tag">
                                        <i class="fas fa-clock"></i> No seu ritmo
                                    </div>
                                </div>
                            </div>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($product['recipes'])): ?>
                <div class="product-recipes-section">
                    <h2 class="section-heading">
                        <i class="fas fa-fire"></i>
                        Receitas Fitness Incluídas
                    </h2>
                    <div class="recipes-showcase-grid">
                        <?php
                        $recipes = loadRecipes();
                        foreach ($product['recipes'] as $recipeId):
                            $recipe = $recipes[$recipeId] ?? null;
                            if ($recipe):
                        ?>
                            <div class="recipe-showcase-card">
                                <div class="recipe-image-container">
                                    <img src="<?php echo $recipe['image']; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" loading="lazy">
                                    <div class="recipe-overlay">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                </div>
                                <div class="recipe-info">
                                    <h4><?php echo htmlspecialchars($recipe['title']); ?></h4>
                                    <div class="recipe-stats">
                                        <span class="recipe-stat">
                                            <i class="fas fa-clock"></i> <?php echo $recipe['prep_time']; ?>
                                        </span>
                                        <span class="recipe-stat">
                                            <i class="fas fa-fire"></i> <?php echo $recipe['calories']; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="product-guarantee-section">
                <div class="guarantee-content">
                    <div class="guarantee-badge">
                        <i class="fas fa-shield-alt"></i>
                        <span>7 DIAS</span>
                    </div>
                    <div class="guarantee-text">
                        <h2>Garantia Incondicional de 7 Dias</h2>
                        <p>Experimente o programa por 7 dias. Se não ficar satisfeito, devolvemos 100% do seu investimento. Sem perguntas, sem burocracia.</p>
                    </div>
                </div>
            </div>
            
            <div class="product-cta-final">
                <div class="cta-content">
                    <h2>Pronto Para Transformar Seu Corpo?</h2>
                    <p>Junte-se a milhares de pessoas que já estão emagrecendo treinando</p>
                    <a href="checkout.php?product=<?php echo $product['id']; ?>" class="btn-buy-large">
                        <i class="fas fa-rocket"></i>
                        <span>Começar Minha Transformação Agora</span>
                    </a>
                    <div class="cta-security">
                        <i class="fas fa-lock"></i> Pagamento 100% seguro via PIX
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <a href="<?php echo WHATSAPP_LINK; ?>" class="whatsapp-float" target="_blank" rel="noopener">
        <i class="fab fa-whatsapp"></i>
    </a>
    
    <div id="purchase-notification" class="purchase-notification">
        <div class="notification-content">
            <i class="fas fa-check-circle"></i>
            <div class="notification-text">
                <strong id="notification-name"></strong>
                <span id="notification-message"></span>
            </div>
        </div>
    </div>
    
    <script src="assets/js/main.js"></script>
</body>
</html>
