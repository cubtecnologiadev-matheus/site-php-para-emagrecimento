<?php
session_start();
require_once 'config/config.php';
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
    <?php include 'includes/header.php'; ?>

    <section class="product-hero" style="background: linear-gradient(135deg, #0a1628 0%, #1a2f4a 100%); padding: 80px 0;">
        <div class="container">
            <div class="hero-content" style="text-align: center; max-width: 800px; margin: 0 auto;">
                <h1 style="font-size: 3rem; font-weight: 900; color: #fff; margin-bottom: 20px; line-height: 1.2;">
                    POLÍTICA DE <span style="color: #00ff88;">REEMBOLSO</span>
                </h1>
                <p style="font-size: 1.1rem; color: #a0aec0;">Garantia de 7 dias - Sua satisfação é nossa prioridade</p>
            </div>
        </div>
    </section>

    <section style="padding: 80px 0; background: #fff;">
        <div class="container">
            <div style="max-width: 900px; margin: 0 auto; background: #fff; border-radius: 20px; padding: 50px; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
                
                <div style="background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); border-radius: 15px; padding: 30px; margin-bottom: 40px; text-align: center;">
                    <div style="font-size: 4rem; font-weight: 900; color: #0a1628; margin-bottom: 10px;">7 DIAS</div>
                    <p style="font-size: 1.3rem; color: #0a1628; font-weight: 700;">Garantia Incondicional de Satisfação</p>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        Nossa Garantia
                    </h2>
                    <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem;">
                        Acreditamos tanto na qualidade dos nossos programas de treino que oferecemos uma <strong style="color: #00ff88;">garantia incondicional de 7 dias</strong>. Se por qualquer motivo você não ficar satisfeito com sua compra, devolvemos 100% do seu dinheiro, sem perguntas e sem burocracia.
                    </p>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        Como Solicitar Reembolso
                    </h2>
                    <div style="display: grid; gap: 20px;">
                        <div style="display: flex; gap: 20px; align-items: start;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.5rem; font-weight: 900; color: #0a1628;">1</div>
                            <div>
                                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0a1628; margin-bottom: 10px;">Entre em Contato</h3>
                                <p style="color: #4a5568; line-height: 1.7;">Envie um e-mail para <strong><?php echo CONTACT_EMAIL; ?></strong> ou fale conosco pelo WhatsApp informando seu pedido e solicitando o reembolso.</p>
                            </div>
                        </div>

                        <div style="display: flex; gap: 20px; align-items: start;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.5rem; font-weight: 900; color: #0a1628;">2</div>
                            <div>
                                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0a1628; margin-bottom: 10px;">Confirmação</h3>
                                <p style="color: #4a5568; line-height: 1.7;">Nossa equipe irá confirmar sua solicitação em até 24 horas úteis. Não fazemos perguntas, respeitamos sua decisão.</p>
                            </div>
                        </div>

                        <div style="display: flex; gap: 20px; align-items: start;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.5rem; font-weight: 900; color: #0a1628;">3</div>
                            <div>
                                <h3 style="font-size: 1.3rem; font-weight: 800; color: #0a1628; margin-bottom: 10px;">Reembolso Processado</h3>
                                <p style="color: #4a5568; line-height: 1.7;">O valor será estornado na mesma forma de pagamento utilizada na compra em até 7 dias úteis.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        Condições
                    </h2>
                    <ul style="color: #4a5568; line-height: 1.8; font-size: 1.05rem; padding-left: 30px;">
                        <li style="margin-bottom: 10px;">A solicitação deve ser feita dentro de 7 dias corridos após a compra</li>
                        <li style="margin-bottom: 10px;">O reembolso é de 100% do valor pago</li>
                        <li style="margin-bottom: 10px;">Não é necessário justificar o motivo do reembolso</li>
                        <li style="margin-bottom: 10px;">O prazo para o estorno depende da operadora do cartão ou banco</li>
                        <li style="margin-bottom: 10px;">Após o reembolso, o acesso ao programa será removido</li>
                    </ul>
                </div>

                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0a1628; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #00ff88;">
                        Prazo de Estorno
                    </h2>
                    <div style="background: #f7fafc; border-left: 4px solid #00ff88; padding: 25px; border-radius: 10px;">
                        <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem; margin-bottom: 15px;">
                            <strong style="color: #0a1628;">Cartão de Crédito:</strong> O estorno aparece na fatura em até 2 faturas (dependendo da data de fechamento)
                        </p>
                        <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem; margin-bottom: 15px;">
                            <strong style="color: #0a1628;">PIX:</strong> O valor é devolvido em até 7 dias úteis na mesma conta de origem
                        </p>
                        <p style="color: #4a5568; line-height: 1.8; font-size: 1.05rem;">
                            <strong style="color: #0a1628;">Boleto:</strong> Necessário informar dados bancários para transferência em até 7 dias úteis
                        </p>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #0a1628 0%, #1a2f4a 100%); border-radius: 15px; padding: 40px; text-align: center;">
                    <h3 style="font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: 15px;">
                        Precisa Solicitar Reembolso?
                    </h3>
                    <p style="color: #a0aec0; margin-bottom: 25px; font-size: 1.05rem;">
                        Entre em contato conosco agora mesmo
                    </p>
                    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                        <a href="mailto:<?php echo CONTACT_EMAIL; ?>" style="display: inline-block; background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); color: #0a1628; padding: 15px 35px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.05rem;">
                            <i class="fas fa-envelope"></i> E-mail
                        </a>
                        <a href="https://api.whatsapp.com/send?phone=<?php echo WHATSAPP_NUMBER; ?>&text=Olá!%20Gostaria%20de%20solicitar%20reembolso" target="_blank" style="display: inline-block; background: #25d366; color: #fff; padding: 15px 35px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 1.05rem;">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
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
