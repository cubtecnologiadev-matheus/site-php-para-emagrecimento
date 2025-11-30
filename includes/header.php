<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$firstName = '';
if ($isLoggedIn && isset($_SESSION['user_name'])) {
    $nameParts = explode(' ', $_SESSION['user_name']);
    $firstName = $nameParts[0];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Transforme seu corpo através do treino físico. Programas completos de emagrecimento com foco em atividade física e resultados reais.'; ?>">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php">
                        <h1>
                            <span class="logo-line-1">DA MAMÃE</span>
                            <span class="logo-line-2">FITNESS</span>
                        </h1>
                    </a>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php">Início</a></li>
                        <li><a href="produtos.php">Programas</a></li>
                        <li><a href="sobre.php">Sobre</a></li>
                        <li><a href="depoimentos.php">Resultados</a></li>
                        <li><a href="contato.php">Contato</a></li>
                        <?php if ($isLoggedIn): ?>
                            <li><a href="minha-conta.php" class="btn-nav">Minha Conta</a></li>
                            <li><a href="logout.php">Sair</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Entrar</a></li>
                            <li><a href="cadastro.php" class="btn-nav">Cadastrar</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="mobile-header-actions">
                    <?php if (!$isLoggedIn): ?>
                        <a href="login.php" class="mobile-login-btn">
                            <span>JÁ SOU ALUNO</span>
                        </a>
                    <?php else: ?>
                        <span class="user-first-name"><?php echo htmlspecialchars($firstName); ?></span>
                        <a href="minha-conta.php" class="mobile-login-btn logged-in">
                            <i class="fas fa-user-circle"></i>
                        </a>
                    <?php endif; ?>
                    <div class="mobile-menu-toggle">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            const mainNav = document.querySelector('.main-nav');
            
            if (mobileToggle && mainNav) {
                mobileToggle.addEventListener('click', function() {
                    mainNav.classList.toggle('active');
                    const icon = this.querySelector('i');
                    if (mainNav.classList.contains('active')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });

                const navLinks = mainNav.querySelectorAll('a');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mainNav.classList.remove('active');
                        const icon = mobileToggle.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    });
                });

                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.header-content')) {
                        mainNav.classList.remove('active');
                        const icon = mobileToggle.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            }
        });
    </script>
