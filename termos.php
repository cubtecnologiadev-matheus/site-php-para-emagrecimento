<?php
session_start();
require_once 'config/config.php';
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
    <?php include 'includes/header.php'; ?>

    <section class="product-hero" style="background: linear-gradient(135deg, #0a1628 0%, #1a2f4a 100%); padding: 80px 0;">
        <div class="container">
            <div class="hero-content" style="text-align: center; max-width: 800px; margin: 0 auto;">
                <h1 style="font-size: 3rem; font-weight: 900; color: #fff; margin-bottom: 20px; line-height: 1.2;">
                    TERMOS DE <span style="color: #00ff88;">USO</span>
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
                        1. Aceitação dos Termos
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem;">
                        Ao acessar e usar o site Da Mamãe Fitness, você concorda em cumprir e estar vinculado aos seguintes termos e condições de uso. Se você não concordar com qualquer parte destes termos, não deverá usar nosso site ou serviços.
                    </p>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        2. Uso dos Serviços
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem; margin-bottom: 15px;">
                        Nossos programas de treino são destinados a pessoas maiores de 18 anos. Ao adquirir nossos produtos, você declara:
                    </p>
                    <ul style="color: #4a5568; line-height: 1.8; font-size: 1.05rem; padding-left: 30px;">
                        <li style="margin-bottom: 10px;">Ter capacidade legal para contratar</li>
                        <li style="margin-bottom: 10px;">Fornecer informações verdadeiras e atualizadas</li>
                        <li style="margin-bottom: 10px;">Usar os programas de forma responsável e segura</li>
                        <li style="margin-bottom: 10px;">Consultar um médico antes de iniciar qualquer programa de exercícios</li>
                    </ul>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        3. Propriedade Intelectual
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem;">
                        Todo o conteúdo presente neste site, incluindo textos, gráficos, logos, imagens, vídeos e programas de treino, é propriedade da Da Mamãe Fitness e está protegido por leis de direitos autorais. É proibida a reprodução, distribuição ou uso comercial sem autorização prévia.
                    </p>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        4. Pagamentos e Reembolsos
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem;">
                        Todos os pagamentos são processados de forma segura através do Mercado Pago. Oferecemos garantia de 7 dias para todos os nossos programas. Para mais informações, consulte nossa <a href="reembolso.php" style="color: #00ff88; font-weight: 700; text-decoration: none;">Política de Reembolso</a>.
                    </p>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        5. Limitação de Responsabilidade
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem;">
                        Os programas de treino são fornecidos para fins informativos e educacionais. A Da Mamãe Fitness não se responsabiliza por lesões, danos ou problemas de saúde decorrentes do uso inadequado dos programas. Sempre consulte um profissional de saúde antes de iniciar qualquer programa de exercícios.
                    </p>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        6. Modificações dos Termos
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem;">
                        Reservamo-nos o direito de modificar estes termos a qualquer momento. As alterações entrarão em vigor imediatamente após a publicação no site. O uso continuado do site após as modificações constitui aceitação dos novos termos.
                    </p>
                </div>

                <div style="background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); border-radius: 15px; padding: 30px; text-align: center;">
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #0a1628; margin-bottom: 15px;">
                        Dúvidas sobre os Termos?
                    </h3>
                    <p style="color: #0a1628; margin-bottom: 20px; font-size: 1.05rem;">
                        Entre em contato conosco para esclarecimentos
                    </p>
                    <a href="contato.php" style="display: inline-block; background: #0a1628; color: #fff; padding: 15px 40px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.05rem;">
                        <i class="fas fa-envelope"></i> Falar Conosco
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
