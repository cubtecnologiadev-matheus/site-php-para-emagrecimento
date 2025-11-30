<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = getUserById($_SESSION['user_id']);
$userRecipes = getUserRecipes($_SESSION['user_id']);

$recipesByCategory = [];
foreach ($userRecipes as $recipe) {
    $category = $recipe['category'] ?? 'Outras';
    if (!isset($recipesByCategory[$category])) {
        $recipesByCategory[$category] = [];
    }
    $recipesByCategory[$category][] = $recipe;
}

$selectedRecipe = null;
if (isset($_GET['view']) && !empty($_GET['view'])) {
    $selectedRecipe = getRecipe($_GET['view']);
    if ($selectedRecipe && !in_array($selectedRecipe['id'], array_column($userRecipes, 'id'))) {
        $selectedRecipe = null;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Receitas - <?php echo SITE_NAME; ?></title>
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
                <a href="my-recipes.php" class="active">Minhas Receitas</a>
                <a href="my-purchases.php">Minhas Compras</a>
                <a href="profile.php">Meu Perfil</a>
                <a href="logout.php">Sair</a>
            </nav>
        </div>
        
        <div class="dashboard-content">
            <?php if ($selectedRecipe): ?>
                <div class="recipe-detail">
                    <a href="my-recipes.php" class="back-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Voltar para Minhas Receitas
                    </a>
                    
                    <div class="recipe-header">
                        <img src="<?php echo $selectedRecipe['image']; ?>" alt="<?php echo htmlspecialchars($selectedRecipe['title']); ?>" class="recipe-image-large">
                        <div class="recipe-header-info">
                            <h1><?php echo htmlspecialchars($selectedRecipe['title']); ?></h1>
                            <p class="recipe-description"><?php echo htmlspecialchars($selectedRecipe['description']); ?></p>
                            <div class="recipe-meta">
                                <span class="recipe-meta-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <?php echo $selectedRecipe['prep_time']; ?>
                                </span>
                                <span class="recipe-meta-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    <?php echo $selectedRecipe['servings']; ?>
                                </span>
                                <span class="recipe-meta-item">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                    </svg>
                                    <?php echo $selectedRecipe['calories']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="recipe-content">
                        <div class="recipe-section">
                            <h2>Ingredientes</h2>
                            <ul class="ingredients-list">
                                <?php foreach ($selectedRecipe['ingredients'] as $ingredient): ?>
                                    <li><?php echo htmlspecialchars($ingredient); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="recipe-section">
                            <h2>Modo de Preparo</h2>
                            <ol class="instructions-list">
                                <?php foreach ($selectedRecipe['instructions'] as $instruction): ?>
                                    <li><?php echo htmlspecialchars($instruction); ?></li>
                                <?php endforeach; ?>
                            </ol>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <h1>Minhas Receitas</h1>
                
                <?php if (empty($userRecipes)): ?>
                    <div class="empty-state">
                        <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path>
                            <path d="M7 2v20"></path>
                            <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path>
                        </svg>
                        <h3>Nenhuma receita disponível</h3>
                        <p>Você ainda não possui receitas. <a href="index.php#products">Adquira um programa!</a></p>
                    </div>
                <?php else: ?>
                    <?php foreach ($recipesByCategory as $category => $recipes): ?>
                        <div class="recipe-category-section">
                            <h2 class="category-title"><?php echo htmlspecialchars($category); ?></h2>
                            <div class="recipes-grid">
                                <?php foreach ($recipes as $recipe): ?>
                                    <div class="recipe-card">
                                        <img src="<?php echo $recipe['image']; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                                        <div class="recipe-card-content">
                                            <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                                            <p><?php echo htmlspecialchars($recipe['description']); ?></p>
                                            <div class="recipe-card-meta">
                                                <span>⏱️ <?php echo $recipe['prep_time']; ?></span>
                                                <span>🔥 <?php echo $recipe['calories']; ?></span>
                                            </div>
                                            <a href="my-recipes.php?view=<?php echo $recipe['id']; ?>" class="btn btn-primary">Ver Receita</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>
