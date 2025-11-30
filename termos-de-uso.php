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
    <title>Termos de Uso - <?php echo SITE_NAME; ?></title>
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
            <h1>Termos de Uso</h1>
            <p>Última atualização: <?php echo date('d/m/Y'); ?></p>
        </div>
    </section>

    <!-- Terms Content -->
    <section class="legal-content">
        <div class="container">
            <div class="legal-text">
                <h2>1. Aceitação dos Termos</h2>
                <p>Ao acessar e usar o site <?php echo SITE_NAME; ?>, você concorda em cumprir e estar vinculado aos seguintes termos e condições de uso. Se você não concordar com qualquer parte destes termos, não deverá usar nosso site.</p>

                <h2>2. Uso do Site</h2>
                <p>O conteúdo deste site é apenas para sua informação geral e uso. Está sujeito a alterações sem aviso prévio. Você não pode:</p>
                <ul>
                    <li>Republicar material deste site</li>
                    <li>Vender, alugar ou sublicenciar material do site</li>
                    <li>Reproduzir, duplicar ou copiar material deste site</li>
                    <li>Redistribuir conteúdo deste site</li>
                </ul>

                <h2>3. Produtos e Serviços</h2>
                <p>Todos os produtos digitais vendidos em nosso site são de propriedade exclusiva da <?php echo SITE_NAME; ?>. Ao adquirir nossos produtos, você recebe uma licença de uso pessoal e intransferível.</p>
                <p>Os resultados podem variar de pessoa para pessoa. Não garantimos resultados específicos de perda de peso.</p>

                <h2>4. Cadastro e Conta</h2>
                <p>Para acessar determinados recursos do site, você pode ser solicitado a criar uma conta. Você é responsável por:</p>
                <ul>
                    <li>Manter a confidencialidade de sua senha</li>
                    <li>Todas as atividades que ocorrem em sua conta</li>
                    <li>Notificar-nos imediatamente sobre qualquer uso não autorizado</li>
                </ul>

                <h2>5. Pagamentos</h2>
                <p>Todos os pagamentos são processados de forma segura através de nossos parceiros de pagamento. Aceitamos PIX como forma de pagamento.</p>
                <p>Os preços estão sujeitos a alterações sem aviso prévio. O preço aplicável será aquele vigente no momento da compra.</p>

                <h2>6. Propriedade Intelectual</h2>
                <p>Todo o conteúdo incluído neste site, como textos, gráficos, logos, imagens, receitas e software, é propriedade da <?php echo SITE_NAME; ?> e protegido por leis de direitos autorais.</p>

                <h2>7. Limitação de Responsabilidade</h2>
                <p>A <?php echo SITE_NAME; ?> não será responsável por quaisquer danos diretos, indiretos, incidentais, consequenciais ou punitivos decorrentes do uso ou incapacidade de usar nossos produtos ou serviços.</p>
                <p>Nossos produtos não substituem orientação médica profissional. Consulte sempre um médico antes de iniciar qualquer programa de emagrecimento.</p>

                <h2>8. Links para Sites de Terceiros</h2>
                <p>Nosso site pode conter links para sites de terceiros. Não temos controle sobre o conteúdo desses sites e não assumimos responsabilidade por eles.</p>

                <h2>9. Modificações dos Termos</h2>
                <p>Reservamo-nos o direito de modificar estes termos a qualquer momento. As alterações entrarão em vigor imediatamente após a publicação no site.</p>

                <h2>10. Lei Aplicável</h2>
                <p>Estes termos serão regidos e interpretados de acordo com as leis do Brasil. Qualquer disputa será submetida à jurisdição exclusiva dos tribunais brasileiros.</p>

                <h2>11. Contato</h2>
                <p>Se você tiver alguma dúvida sobre estes Termos de Uso, entre em contato conosco:</p>
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
