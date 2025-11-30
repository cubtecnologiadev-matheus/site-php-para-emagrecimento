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
    <title>Política de Reembolso - <?php echo SITE_NAME; ?></title>
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
            <h1>Política de Reembolso</h1>
            <p>Garantia de satisfação de 7 dias</p>
        </div>
    </section>

    <!-- Refund Content -->
    <section class="legal-content">
        <div class="container">
            <div class="legal-text">
                <h2>1. Garantia de Satisfação</h2>
                <p>Na <?php echo SITE_NAME; ?>, acreditamos na qualidade dos nossos produtos. Por isso, oferecemos uma <strong>garantia incondicional de 7 dias</strong>. Se você não ficar satisfeito com sua compra, devolvemos 100% do seu dinheiro.</p>

                <h2>2. Prazo para Solicitação</h2>
                <p>Você tem até <strong>7 dias corridos</strong> a partir da data da compra para solicitar o reembolso. Após este prazo, não será possível processar devoluções.</p>

                <h2>3. Como Solicitar Reembolso</h2>
                <p>Para solicitar um reembolso, siga os passos abaixo:</p>
                <ol>
                    <li>Entre em contato conosco através do e-mail <?php echo CONTACT_EMAIL; ?> ou WhatsApp <?php echo WHATSAPP_NUMBER; ?></li>
                    <li>Informe o número do seu pedido e o motivo da solicitação</li>
                    <li>Aguarde a confirmação da nossa equipe</li>
                    <li>O reembolso será processado em até 5 dias úteis</li>
                </ol>

                <h2>4. Informações Necessárias</h2>
                <p>Para processar seu reembolso, precisaremos das seguintes informações:</p>
                <ul>
                    <li>Número do pedido</li>
                    <li>CPF utilizado na compra</li>
                    <li>E-mail cadastrado</li>
                    <li>Dados bancários para devolução (se aplicável)</li>
                </ul>

                <h2>5. Processamento do Reembolso</h2>
                <p>Após a aprovação da solicitação:</p>
                <ul>
                    <li><strong>Pagamento via PIX:</strong> O reembolso será feito via PIX em até 5 dias úteis</li>
                    <li>Você receberá um e-mail de confirmação quando o reembolso for processado</li>
                    <li>O acesso ao conteúdo digital será revogado após o reembolso</li>
                </ul>

                <h2>6. Condições Especiais</h2>
                <h3>6.1 Produtos Digitais</h3>
                <p>Como nossos produtos são digitais e de acesso imediato, o direito ao reembolso é garantido dentro do prazo de 7 dias, independentemente do uso do material.</p>

                <h3>6.2 Múltiplas Compras</h3>
                <p>Cada compra é tratada individualmente. Se você adquiriu múltiplos produtos, pode solicitar reembolso de um ou mais itens separadamente.</p>

                <h3>6.3 Promoções e Descontos</h3>
                <p>O reembolso será feito no valor efetivamente pago, considerando descontos e promoções aplicados no momento da compra.</p>

                <h2>7. Situações que Não se Aplicam ao Reembolso</h2>
                <p>O reembolso não será concedido nas seguintes situações:</p>
                <ul>
                    <li>Solicitações feitas após o prazo de 7 dias</li>
                    <li>Violação dos Termos de Uso</li>
                    <li>Compartilhamento não autorizado do conteúdo</li>
                    <li>Uso indevido da plataforma</li>
                </ul>

                <h2>8. Cancelamento de Assinatura</h2>
                <p>Se aplicável a produtos com assinatura recorrente:</p>
                <ul>
                    <li>Você pode cancelar sua assinatura a qualquer momento</li>
                    <li>O cancelamento impede cobranças futuras</li>
                    <li>Você mantém acesso até o fim do período já pago</li>
                    <li>Não há reembolso proporcional de períodos já iniciados</li>
                </ul>

                <h2>9. Resolução de Disputas</h2>
                <p>Estamos comprometidos em resolver qualquer problema de forma amigável. Se você tiver alguma insatisfação:</p>
                <ol>
                    <li>Entre em contato com nosso suporte</li>
                    <li>Explique sua situação detalhadamente</li>
                    <li>Trabalharemos para encontrar a melhor solução</li>
                </ol>

                <h2>10. Alterações nesta Política</h2>
                <p>Reservamo-nos o direito de modificar esta política a qualquer momento. As alterações entrarão em vigor imediatamente após a publicação no site.</p>

                <h2>11. Contato</h2>
                <p>Para solicitar reembolso ou esclarecer dúvidas:</p>
                <ul>
                    <li><strong>E-mail:</strong> <?php echo CONTACT_EMAIL; ?></li>
                    <li><strong>WhatsApp:</strong> <?php echo WHATSAPP_NUMBER; ?></li>
                    <li><strong>Horário de Atendimento:</strong> Segunda a Sexta, 9h às 18h</li>
                </ul>

                <div class="guarantee-badge">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Garantia de 7 Dias</h3>
                    <p>Sua satisfação é nossa prioridade. Compre com confiança!</p>
                </div>
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
