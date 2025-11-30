<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

// Verifica se está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = getUserById($_SESSION['user_id']);
$purchases = getUserPurchases($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta - <?php echo SITE_NAME; ?></title>
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
                <a href="dashboard.php" class="active">Meus Cursos</a>
                <a href="my-recipes.php">Minhas Receitas</a>
                <a href="my-purchases.php">Minhas Compras</a>
                <a href="profile.php">Meu Perfil</a>
                <a href="logout.php">Sair</a>
            </nav>
        </div>
        
        <div class="dashboard-content">
            <h1>Bem-vindo(a), <?php echo htmlspecialchars(explode(' ', $user['name'])[0]); ?>!</h1>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3><?php echo count($purchases); ?></h3>
                    <p>Compras Realizadas</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo countUserCourses($_SESSION['user_id']); ?></h3>
                    <p>Cursos Ativos</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo countUserRecipes($_SESSION['user_id']); ?></h3>
                    <p>Receitas Disponíveis</p>
                </div>
            </div>
            
            <h2>Meus Cursos</h2>
            <div class="courses-grid">
                <?php
                $courses = getUserCourses($_SESSION['user_id']);
                if (empty($courses)):
                ?>
                    <div class="empty-state">
                        <p>Você ainda não possui cursos. <a href="index.php#products">Compre agora!</a></p>
                    </div>
                <?php else: ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="course-card">
                             Fixed image reference to use thumbnail 
                            <img src="<?php echo $course['thumbnail']; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                            <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <a href="course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary">Acessar Curso</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>
