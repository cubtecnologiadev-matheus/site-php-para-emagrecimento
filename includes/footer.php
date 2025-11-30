<?php
// includes/footer.php
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Da Mamãe Fitness</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- ... existing body content here ... -->

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <h4>Da Mamãe Fitness</h4>
                    <p>Transforme seu corpo através do treino. Emagrecimento saudável com foco, disciplina e resultados reais.</p>
                    <div class="footer-cta">
                        <a href="produtos.php" class="btn btn-primary">Comece Seu Treino Agora</a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Links Rápidos</h4>
                    <ul>
                        <li><a href="index.php">Início</a></li>
                        <li><a href="produtos.php">Programas</a></li>
                        <li><a href="sobre.php">Sobre Nós</a></li>
                        <li><a href="depoimentos.php">Resultados</a></li>
                        <li><a href="contato.php">Contato</a></li>
                        <li><a href="faq.php">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="termos-de-uso.php">Termos de Uso</a></li>
                        <li><a href="politica-privacidade.php">Política de Privacidade</a></li>
                        <li><a href="politica-reembolso.php">Política de Reembolso</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contato</h4>
                    <ul>
                        <li><i class="fas fa-phone"></i> <?php echo defined('CONTACT_PHONE') ? CONTACT_PHONE : '(11) 2626-0429'; ?></li>
                        <li><i class="fas fa-envelope"></i> <?php echo defined('CONTACT_EMAIL') ? CONTACT_EMAIL : 'contato@damamaefitness.com.br'; ?></li>
                        <li><i class="fas fa-map-marker-alt"></i> São Paulo, SP</li>
                    </ul>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Da Mamãe Fitness. Todos os direitos reservados. <strong>Emagreça treinando!</strong></p>
            </div>
        </div>
    </footer>

    <a href="https://api.whatsapp.com/send?phone=<?php echo defined('WHATSAPP_NUMBER') ? str_replace(['+', ' ', '-', '(', ')'], '', WHATSAPP_NUMBER) : '5511984968625'; ?>&text=Olá!%20Quero%20começar%20meu%20treino!" 
       class="whatsapp-float" target="_blank" rel="noopener" aria-label="WhatsApp">
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
