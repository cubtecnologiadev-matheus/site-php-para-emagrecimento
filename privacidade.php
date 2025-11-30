<?php
session_start();
require_once 'config/config.php';
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
    <?php include 'includes/header.php'; ?>

    <section class="product-hero" style="background: linear-gradient(135deg, #0a1628 0%, #1a2f4a 100%); padding: 80px 0;">
        <div class="container">
            <div class="hero-content" style="text-align: center; max-width: 800px; margin: 0 auto;">
                <h1 style="font-size: 3rem; font-weight: 900; color: #fff; margin-bottom: 20px; line-height: 1.2;">
                    POLÍTICA DE <span style="color: #00ff88;">PRIVACIDADE</span>
                </h1>
                <p style="font-size: 1.1rem; color: #a0aec0;">Última atualização: <?php echo date('d/m/Y'); ?></p>
            </div>
        </div>
    </section>

    <section style="padding: 80px 0; background: #fff;">
        <div class="container">
            <div style="max-width: 900px; margin: 0 auto; background: #fff; border-radius: 20px; padding: 50px; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                
                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        1. Informações que Coletamos
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem; margin-bottom: 15px;">
                        Coletamos as seguintes informações quando você usa nosso site:
                    </p>
                    <ul style="color: #4a5568; line-height: 1.8; font-size: 1.05rem; padding-left: 30px;">
                        <li style="margin-bottom: 10px;"><strong>Dados Pessoais:</strong> Nome, e-mail, CPF, telefone</li>
                        <li style="margin-bottom: 10px;"><strong>Dados de Pagamento:</strong> Processados de forma segura pelo Mercado Pago</li>
                        <li style="margin-bottom: 10px;"><strong>Dados de Navegação:</strong> IP, navegador, páginas visitadas</li>
                        <li style="margin-bottom: 10px;"><strong>Cookies:</strong> Para melhorar sua experiência no site</li>
                    </ul>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        2. Como Usamos Suas Informações
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem; margin-bottom: 15px;">
                        Utilizamos suas informações para:
                    </p>
                    <ul style="color: #4a5568; line-height: 1.8; font-size: 1.05rem; padding-left: 30px;">
                        <li style="margin-bottom: 10px;">Processar suas compras e fornecer acesso aos programas</li>
                        <li style="margin-bottom: 10px;">Enviar atualizações sobre seus pedidos</li>
                        <li style="margin-bottom: 10px;">Melhorar nossos serviços e experiência do usuário</li>
                        <li style="margin-bottom: 10px;">Enviar comunicações de marketing (com seu consentimento)</li>
                        <li style="margin-bottom: 10px;">Cumprir obrigações legais</li>
                    </ul>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        3. Compartilhamento de Dados
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem;">
                        Não vendemos, alugamos ou compartilhamos suas informações pessoais com terceiros, exceto quando necessário para processar pagamentos (Mercado Pago) ou quando exigido por lei. Todos os nossos parceiros são obrigados a manter a confidencialidade de suas informações.
                    </p>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        4. Segurança dos Dados
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem;">
                        Implementamos medidas de segurança técnicas e organizacionais para proteger suas informações contra acesso não autorizado, alteração, divulgação ou destruição. Utilizamos criptografia SSL e armazenamento seguro de dados.
                    </p>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        5. Seus Direitos (LGPD)
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem; margin-bottom: 15px;">
                        De acordo com a Lei Geral de Proteção de Dados (LGPD), você tem direito a:
                    </p>
                    <ul style="color: #4a5568; line-height: 1.8; font-size: 1.05rem; padding-left: 30px;">
                        <li style="margin-bottom: 10px;">Acessar seus dados pessoais</li>
                        <li style="margin-bottom: 10px;">Corrigir dados incompletos ou desatualizados</li>
                        <li style="margin-bottom: 10px;">Solicitar a exclusão de seus dados</li>
                        <li style="margin-bottom: 10px;">Revogar consentimento para uso de dados</li>
                        <li style="margin-bottom: 10px;">Solicitar portabilidade de dados</li>
                    </ul>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        6. Cookies
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem;">
                        Utilizamos cookies para melhorar sua experiência de navegação, analisar o tráfego do site e personalizar conteúdo. Você pode configurar seu navegador para recusar cookies, mas isso pode afetar algumas funcionalidades do site.
                    </p>
                </div>

                <div style="background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); border-radius: 15px; padding: 30px; text-align: center;">
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #0a1628; margin-bottom: 15px;">
                        Dúvidas sobre Privacidade?
                    </h3>
                    <p style="color: #0a1628; margin-bottom: 20px; font-size: 1.05rem;">
                        Entre em contato conosco para exercer seus direitos ou esclarecer dúvidas
                    </p>
                    <a href="contato.php" style="display: inline-block; background: #0a1628; color: #fff; padding: 15px 40px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.05rem;">
                        <i class="fas fa-shield-alt"></i> Falar Conosco
                    </a>
                </div>

            </div>
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
