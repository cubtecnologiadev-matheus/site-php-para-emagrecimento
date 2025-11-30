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
    <title>Política de Privacidade - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php"><h1>Da Mamãe <span>Fitness</span></h1></a>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php">Início</a></li>
                        <li><a href="produtos.php">Produtos</a></li>
                        <li><a href="sobre.php">Sobre Nós</a></li>
                        <li><a href="depoimentos.php">Depoimentos</a></li>
                        <li><a href="contato.php">Contato</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="dashboard.php">Minha Conta</a></li>
                            <li><a href="logout.php">Sair</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Entrar</a></li>
                            <li><a href="register.php">Cadastrar</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Política de Privacidade</h1>
            <p>Última atualização: <?php echo date('d/m/Y'); ?></p>
        </div>
    </section>

    <!-- Privacy Content -->
    <section class="legal-content">
        <div class="container">
            <div class="legal-text">
                <h2>1. Introdução</h2>
                <p>A <?php echo SITE_NAME; ?> está comprometida em proteger sua privacidade. Esta Política de Privacidade explica como coletamos, usamos, divulgamos e protegemos suas informações pessoais.</p>

                <h2>2. Informações que Coletamos</h2>
                <h3>2.1 Informações Fornecidas por Você</h3>
                <ul>
                    <li>Nome completo</li>
                    <li>Endereço de e-mail</li>
                    <li>CPF</li>
                    <li>Número de telefone</li>
                    <li>Informações de pagamento</li>
                </ul>

                <h3>2.2 Informações Coletadas Automaticamente</h3>
                <ul>
                    <li>Endereço IP</li>
                    <li>Tipo de navegador</li>
                    <li>Páginas visitadas</li>
                    <li>Tempo de acesso</li>
                    <li>Cookies e tecnologias similares</li>
                </ul>

                <h2>3. Como Usamos Suas Informações</h2>
                <p>Utilizamos suas informações pessoais para:</p>
                <ul>
                    <li>Processar suas compras e pagamentos</li>
                    <li>Fornecer acesso aos produtos adquiridos</li>
                    <li>Enviar confirmações de pedidos e atualizações</li>
                    <li>Responder às suas perguntas e solicitações</li>
                    <li>Melhorar nossos produtos e serviços</li>
                    <li>Enviar comunicações de marketing (com seu consentimento)</li>
                    <li>Prevenir fraudes e garantir a segurança</li>
                </ul>

                <h2>4. Compartilhamento de Informações</h2>
                <p>Não vendemos suas informações pessoais. Podemos compartilhar suas informações com:</p>
                <ul>
                    <li><strong>Processadores de Pagamento:</strong> Para processar transações</li>
                    <li><strong>Provedores de Serviços:</strong> Que nos ajudam a operar nosso negócio</li>
                    <li><strong>Autoridades Legais:</strong> Quando exigido por lei</li>
                </ul>

                <h2>5. Segurança dos Dados</h2>
                <p>Implementamos medidas de segurança técnicas e organizacionais para proteger suas informações pessoais contra acesso não autorizado, alteração, divulgação ou destruição.</p>
                <ul>
                    <li>Criptografia SSL/TLS</li>
                    <li>Armazenamento seguro de dados</li>
                    <li>Acesso restrito às informações</li>
                    <li>Monitoramento regular de segurança</li>
                </ul>

                <h2>6. Cookies</h2>
                <p>Utilizamos cookies para melhorar sua experiência em nosso site. Cookies são pequenos arquivos de texto armazenados em seu dispositivo. Você pode configurar seu navegador para recusar cookies, mas isso pode afetar a funcionalidade do site.</p>

                <h2>7. Seus Direitos (LGPD)</h2>
                <p>De acordo com a Lei Geral de Proteção de Dados (LGPD), você tem direito a:</p>
                <ul>
                    <li>Confirmar a existência de tratamento de dados</li>
                    <li>Acessar seus dados pessoais</li>
                    <li>Corrigir dados incompletos ou desatualizados</li>
                    <li>Solicitar a anonimização ou eliminação de dados</li>
                    <li>Revogar o consentimento</li>
                    <li>Solicitar a portabilidade dos dados</li>
                </ul>

                <h2>8. Retenção de Dados</h2>
                <p>Mantemos suas informações pessoais pelo tempo necessário para cumprir os propósitos descritos nesta política, a menos que um período de retenção mais longo seja exigido por lei.</p>

                <h2>9. Menores de Idade</h2>
                <p>Nossos serviços não são direcionados a menores de 18 anos. Não coletamos intencionalmente informações de menores de idade.</p>

                <h2>10. Alterações nesta Política</h2>
                <p>Podemos atualizar esta Política de Privacidade periodicamente. Notificaremos você sobre alterações significativas publicando a nova política em nosso site.</p>

                <h2>11. Contato</h2>
                <p>Para exercer seus direitos ou esclarecer dúvidas sobre esta política, entre em contato:</p>
                <ul>
                    <li>E-mail: <?php echo CONTACT_EMAIL; ?></li>
                    <li>WhatsApp: <?php echo WHATSAPP_NUMBER; ?></li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- WhatsApp Button -->
    <a href="https://api.whatsapp.com/send?phone=<?php echo WHATSAPP_NUMBER; ?>&text=Olá!%20Gostaria%20de%20mais%20informações" 
       class="whatsapp-float" target="_blank" rel="noopener">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="assets/js/main.js"></script>
</body>
</html>
