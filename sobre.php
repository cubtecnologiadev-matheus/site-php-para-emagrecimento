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
    <title>Sobre Nós - <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Conheça a história e missão da Da Mamãe Fitness">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="product-hero" style="background: linear-gradient(135deg, #0a1628 0%, #1a2f4a 100%); padding: 80px 0;">
        <div class="container">
            <div class="hero-content" style="text-align: center; max-width: 800px; margin: 0 auto;">
                <h1 style="font-size: 3rem; font-weight: 900; color: #fff; margin-bottom: 20px; line-height: 1.2;">
                    SOBRE A <span style="color: #00ff88;">DA MAMÃE FITNESS</span>
                </h1>
                <p style="font-size: 1.3rem; color: #a0aec0; margin-bottom: 30px;">
                    Transformando vidas através do movimento e disciplina
                </p>
            </div>
        </div>
    </section>

    <section style="padding: 80px 0; background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1f3a 100%);">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; margin-bottom: 80px;">
                <div>
                    <div style="display: inline-block; background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); padding: 8px 20px; border-radius: 20px; margin-bottom: 20px;">
                        <span style="color: #0a1628; font-weight: 700; font-size: 0.9rem;">NOSSA HISTÓRIA</span>
                    </div>
                    <h2 style="font-size: 2.5rem; font-weight: 900; color: #fff; margin-bottom: 25px; line-height: 1.2;">
                        Nascemos do Sonho de Transformar Vidas
                    </h2>
                    <p style="font-size: 1.1rem; color: #a0aec0; line-height: 1.8; margin-bottom: 20px;">
                        A <strong style="color: #00ff88;">Da Mamãe Fitness</strong> nasceu da paixão por ajudar pessoas a alcançarem seus objetivos através do treino físico e disciplina. Acreditamos que o emagrecimento saudável vem do movimento, da energia e do foco.
                    </p>
                    <p style="font-size: 1.1rem; color: #a0aec0; line-height: 1.8;">
                        Com anos de experiência, desenvolvemos programas completos de treino que combinam eficiência, resultados reais e motivação constante. Nossos métodos são testados e aprovados por milhares de clientes satisfeitos.
                    </p>
                </div>
                <div>
                    <img src="assets/images/sobre/historia.jpg" alt="Nossa História" style="width: 100%; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,255,136,0.2);">
                </div>
            </div>

            <div style="background: linear-gradient(135deg, #0a1628 0%, #1a2f4a 100%); border-radius: 30px; padding: 60px; margin-bottom: 60px;">
                <div style="text-align: center; margin-bottom: 50px;">
                    <h2 style="font-size: 2.5rem; font-weight: 900; color: #fff; margin-bottom: 15px;">Nossa Missão</h2>
                    <p style="font-size: 1.2rem; color: #00ff88; max-width: 700px; margin: 0 auto; line-height: 1.6;">
                        Proporcionar programas completos de treino para emagrecimento real, com foco, disciplina e energia que transformam corpo e mente.
                    </p>
                </div>
            </div>

            <div style="text-align: center; margin-bottom: 50px;">
                <h2 style="font-size: 2.5rem; font-weight: 900; color: #fff; margin-bottom: 15px;">Nossos Valores</h2>
                <p style="font-size: 1.1rem; color: #a0aec0;">Os pilares que guiam nossa jornada</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <div style="background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(0, 255, 136, 0.2); border-radius: 20px; padding: 40px; text-align: center; transition: all 0.3s; backdrop-filter: blur(10px);">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                        <i class="fas fa-heartbeat" style="font-size: 2rem; color: #0a1628;"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 15px;">Saúde em Primeiro Lugar</h3>
                    <p style="color: #a0aec0; line-height: 1.6;">Priorizamos sempre o bem-estar e a saúde dos nossos clientes em cada programa</p>
                </div>

                <div style="background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(255, 107, 53, 0.2); border-radius: 20px; padding: 40px; text-align: center; backdrop-filter: blur(10px);">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                        <i class="fas fa-dumbbell" style="font-size: 2rem; color: #fff;"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 15px;">Resultados Reais</h3>
                    <p style="color: #a0aec0; line-height: 1.6;">Comprometimento com a transformação real através de treinos eficientes</p>
                </div>

                <div style="background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(0, 212, 255, 0.2); border-radius: 20px; padding: 40px; text-align: center; backdrop-filter: blur(10px);">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                        <i class="fas fa-trophy" style="font-size: 2rem; color: #fff;"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 15px;">Excelência</h3>
                    <p style="color: #a0aec0; line-height: 1.6;">Programas de alta qualidade desenvolvidos por profissionais qualificados</p>
                </div>

                <div style="background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(0, 255, 136, 0.2); border-radius: 20px; padding: 40px; text-align: center; backdrop-filter: blur(10px);">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #00ff88 0%, #00cc6f 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                        <i class="fas fa-users" style="font-size: 2rem; color: #0a1628;"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 15px;">Comunidade</h3>
                    <p style="color: #a0aec0; line-height: 1.6;">Suporte e motivação constante para você nunca desistir</p>
                </div>
            </div>
        </div>
    </section>

    <section style="background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); padding: 80px 0;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; text-align: center;">
                <div>
                    <div style="font-size: 4rem; font-weight: 900; color: #0a1628; margin-bottom: 10px;">+10K</div>
                    <p style="font-size: 1.2rem; color: #0a1628; font-weight: 600;">Clientes Transformados</p>
                </div>
                <div>
                    <div style="font-size: 4rem; font-weight: 900; color: #0a1628; margin-bottom: 10px;">4.9★</div>
                    <p style="font-size: 1.2rem; color: #0a1628; font-weight: 600;">Avaliação Média</p>
                </div>
                <div>
                    <div style="font-size: 4rem; font-weight: 900; color: #0a1628; margin-bottom: 10px;">+5</div>
                    <p style="font-size: 1.2rem; color: #0a1628; font-weight: 600;">Anos de Experiência</p>
                </div>
                <div>
                    <div style="font-size: 4rem; font-weight: 900; color: #0a1628; margin-bottom: 10px;">98%</div>
                    <p style="font-size: 1.2rem; color: #0a1628; font-weight: 600;">Taxa de Satisfação</p>
                </div>
            </div>
        </div>
    </section>

    <section style="padding: 100px 0; background: #0a1628; text-align: center;">
        <div class="container">
            <h2 style="font-size: 3rem; font-weight: 900; color: #fff; margin-bottom: 20px;">
                Pronto para <span style="color: #00ff88;">Transformar Sua Vida?</span>
            </h2>
            <p style="font-size: 1.3rem; color: #a0aec0; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">
                Junte-se a milhares de pessoas que já alcançaram seus objetivos
            </p>
            <a href="produtos.php" style="display: inline-block; background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); color: #0a1628; padding: 20px 50px; border-radius: 50px; font-size: 1.2rem; font-weight: 800; text-decoration: none; transition: all 0.3s; box-shadow: 0 10px 30px rgba(0,255,136,0.3);">
                <i class="fas fa-fire"></i> COMECE SEU TREINO AGORA
            </a>
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
