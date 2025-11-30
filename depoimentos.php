<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

$testimonials = [
    [
        'name' => 'Maria Silva',
        'location' => 'São Paulo, SP',
        'image' => 'assets/images/depoimentos/maria.jpg',
        'rating' => 5,
        'weight_loss' => '12kg',
        'time' => '2 meses',
        'text' => 'Perdi 12kg em 2 meses com o Programa Completo Elite! Os treinos são intensos mas muito eficazes. Recomendo demais!',
        'product' => 'Programa Completo Elite'
    ],
    [
        'name' => 'João Santos',
        'location' => 'Rio de Janeiro, RJ',
        'image' => 'assets/images/depoimentos/joao.jpg',
        'rating' => 5,
        'weight_loss' => '8kg',
        'time' => '1 mês',
        'text' => 'Melhor investimento que fiz na minha saúde. Emagreci 8kg e me sinto muito mais disposto e forte!',
        'product' => 'Programa Intensivo 30 Dias'
    ],
    [
        'name' => 'Ana Paula Costa',
        'location' => 'Belo Horizonte, MG',
        'image' => 'assets/images/depoimentos/ana.jpg',
        'rating' => 5,
        'weight_loss' => '15kg',
        'time' => '3 meses',
        'text' => 'Estava desacreditada, mas os resultados apareceram logo na primeira semana. Perdi 15kg em 3 meses!',
        'product' => 'Programa Transformação Total'
    ],
    [
        'name' => 'Carlos Oliveira',
        'location' => 'Curitiba, PR',
        'image' => 'assets/images/depoimentos/carlos.jpg',
        'rating' => 5,
        'weight_loss' => '10kg',
        'time' => '2 meses',
        'text' => 'O programa é incrível! Me sinto mais leve e com muito mais energia. Perdi 10kg em 2 meses.',
        'product' => 'Programa Emagrecimento Rápido'
    ],
    [
        'name' => 'Juliana Ferreira',
        'location' => 'Porto Alegre, RS',
        'image' => 'assets/images/depoimentos/juliana.jpg',
        'rating' => 5,
        'weight_loss' => '7kg',
        'time' => '1 mês',
        'text' => 'Adorei! Os treinos são desafiadores mas muito motivadores. Já indiquei para várias amigas.',
        'product' => 'Programa Completo Elite'
    ],
    [
        'name' => 'Roberto Lima',
        'location' => 'Brasília, DF',
        'image' => 'assets/images/depoimentos/roberto.jpg',
        'rating' => 5,
        'weight_loss' => '9kg',
        'time' => '2 meses',
        'text' => 'Excelente! Consegui emagrecer sem perder massa muscular. O suporte da equipe também é muito bom.',
        'product' => 'Programa Intensivo 30 Dias'
    ]
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depoimentos - <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Veja o que nossos clientes dizem sobre os resultados alcançados">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="product-hero video-hero-section" style="position: relative; padding: 40px 0; overflow: hidden; min-height: 250px;">
        <video autoplay loop playsinline style="position: absolute; top: 50%; left: 50%; width: 100%; height: 100%; object-fit: cover; transform: translate(-50%, -50%); z-index: 0;">
            <source src="assets/images/videos/institucional-homepage-renata.mp4" type="video/mp4">
        </video>
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(10, 22, 40, 0.7); z-index: 1;"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="hero-content" style="text-align: center; max-width: 800px; margin: 0 auto;">
                <h1 style="font-size: 2.5rem; font-weight: 900; color: #fff; margin-bottom: 15px; line-height: 1.2; text-shadow: 0 4px 20px rgba(0,0,0,0.5);">
                    RESULTADOS <span style="color: #00ff88;">REAIS</span>
                </h1>
                <p style="font-size: 1.1rem; color: #fff; margin-bottom: 0; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                    Veja as transformações de quem treinou com foco e disciplina
                </p>
            </div>
        </div>
    </section>

    <section style="padding: 30px 0 60px 0; background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1f3a 100%);">
        <div class="container">
            <div style="text-align: center; margin-bottom: 40px;">
                <h2 style="font-size: 2rem; font-weight: 900; color: #fff; margin-bottom: 15px;">
                    Depoimentos em <span style="color: #00ff88;">Vídeo</span>
                </h2>
                <p style="font-size: 1rem; color: #a0aec0;">Ouça diretamente de quem transformou sua vida</p>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; max-width: 500px; margin: 0 auto;">
                <div class="video-testimonial-card" style="position: relative; border-radius: 15px; overflow: hidden; aspect-ratio: 1; box-shadow: 0 0 20px rgba(0, 255, 136, 0.3); border: 2px solid rgba(0, 255, 136, 0.3); transition: all 0.3s; cursor: pointer;">
                    <video preload="metadata" controls controlsList="nodownload" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        <source src="assets/images/videos/depoimento-1.mp4" type="video/mp4">
                        Seu navegador não suporta vídeos.
                    </video>
                    <div class="video-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.3); display: flex; align-items: center; justify-content: center; opacity: 1; transition: opacity 0.3s;">
                        <div style="width: 50px; height: 50px; background: rgba(0, 255, 136, 0.9); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px rgba(0, 255, 136, 0.8), 0 0 40px rgba(0, 255, 136, 0.4); animation: pulse 2s infinite;">
                            <i class="fas fa-play" style="color: #0a1628; font-size: 1.2rem; margin-left: 3px;"></i>
                        </div>
                    </div>
                </div>
                <div class="video-testimonial-card" style="position: relative; border-radius: 15px; overflow: hidden; aspect-ratio: 1; box-shadow: 0 0 20px rgba(0, 255, 136, 0.3); border: 2px solid rgba(0, 255, 136, 0.3); transition: all 0.3s; cursor: pointer;">
                    <video preload="metadata" controls controlsList="nodownload" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        <source src="assets/images/videos/depoimento-2.mp4" type="video/mp4">
                        Seu navegador não suporta vídeos.
                    </video>
                    <div class="video-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.3); display: flex; align-items: center; justify-content: center; opacity: 1; transition: opacity 0.3s;">
                        <div style="width: 50px; height: 50px; background: rgba(0, 255, 136, 0.9); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px rgba(0, 255, 136, 0.8), 0 0 40px rgba(0, 255, 136, 0.4); animation: pulse 2s infinite;">
                            <i class="fas fa-play" style="color: #0a1628; font-size: 1.2rem; margin-left: 3px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section style="padding: 80px 0; background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1f3a 100%);">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
                <?php foreach ($testimonials as $testimonial): ?>
                <div style="background: rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 35px; backdrop-filter: blur(10px); border: 1px solid rgba(0, 255, 136, 0.1); transition: all 0.3s;">
                    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px;">
                        <img src="<?php echo htmlspecialchars($testimonial['image']); ?>" 
                             alt="<?php echo htmlspecialchars($testimonial['name']); ?>"
                             style="width: 100px; height: 100px; border-radius: 12px; object-fit: cover; border: 3px solid #00ff88;">
                        <div style="flex: 1;">
                            <h3 style="font-size: 1.2rem; font-weight: 800; color: #fff; margin-bottom: 5px;">
                                <?php echo htmlspecialchars($testimonial['name']); ?>
                            </h3>
                            <p style="color: #a0aec0; font-size: 0.9rem; margin-bottom: 8px;">
                                <i class="fas fa-map-marker-alt" style="color: #00ff88;"></i> 
                                <?php echo htmlspecialchars($testimonial['location']); ?>
                            </p>
                            <div style="color: #fbbf24; font-size: 0.9rem;">
                                <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                <i class="fas fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>

                    <div style="background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-around; text-align: center;">
                            <div>
                                <div style="font-size: 2rem; font-weight: 900; color: #0a1628;">
                                    <?php echo htmlspecialchars($testimonial['weight_loss']); ?>
                                </div>
                                <div style="font-size: 0.85rem; color: #0a1628; font-weight: 600;">PERDIDOS</div>
                            </div>
                            <div style="width: 2px; background: rgba(10,22,40,0.2);"></div>
                            <div>
                                <div style="font-size: 2rem; font-weight: 900; color: #0a1628;">
                                    <?php echo htmlspecialchars($testimonial['time']); ?>
                                </div>
                                <div style="font-size: 0.85rem; color: #0a1628; font-weight: 600;">DE TREINO</div>
                            </div>
                        </div>
                    </div>

                    <p style="color: #a0aec0; line-height: 1.7; font-size: 1rem; margin-bottom: 20px; font-style: italic;">
                        "<?php echo htmlspecialchars($testimonial['text']); ?>"
                    </p>

                    <div style="display: inline-block; background: rgba(0, 255, 136, 0.1); padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; color: #00ff88; font-weight: 600; border: 1px solid rgba(0, 255, 136, 0.2);">
                        <i class="fas fa-dumbbell" style="color: #00ff88;"></i> 
                        <?php echo htmlspecialchars($testimonial['product']); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section style="background: linear-gradient(135deg, #0a1628 0%, #1a2f4a 100%); padding: 80px 0;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 50px;">
                <h2 style="font-size: 2.5rem; font-weight: 900; color: #fff; margin-bottom: 15px;">
                    Resultados <span style="color: #00ff88;">Comprovados</span>
                </h2>
                <p style="font-size: 1.1rem; color: #a0aec0;">Números que falam por si</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; text-align: center;">
                <div>
                    <div style="font-size: 4rem; font-weight: 900; color: #00ff88; margin-bottom: 10px;">+10K</div>
                    <p style="font-size: 1.2rem; color: #fff; font-weight: 600;">Clientes Transformados</p>
                </div>
                <div>
                    <div style="font-size: 4rem; font-weight: 900; color: #00ff88; margin-bottom: 10px;">-8kg</div>
                    <p style="font-size: 1.2rem; color: #fff; font-weight: 600;">Média de Perda de Peso</p>
                </div>
                <div>
                    <div style="font-size: 4rem; font-weight: 900; color: #00ff88; margin-bottom: 10px;">4.9★</div>
                    <p style="font-size: 1.2rem; color: #fff; font-weight: 600;">Avaliação Média</p>
                </div>
                <div>
                    <div style="font-size: 4rem; font-weight: 900; color: #00ff88; margin-bottom: 10px;">98%</div>
                    <p style="font-size: 1.2rem; color: #fff; font-weight: 600;">Taxa de Satisfação</p>
                </div>
            </div>
        </div>
    </section>

    <section style="padding: 100px 0; background: linear-gradient(135deg, #1a1f3a 0%, var(--dark-bg) 100%); text-align: center;">
        <div class="container">
            <h2 style="font-size: 3rem; font-weight: 900; color: #fff; margin-bottom: 20px;">
                Você Também Pode <span style="color: #00ff88;">Alcançar Seus Objetivos!</span>
            </h2>
            <p style="font-size: 1.3rem; color: #a0aec0; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">
                Junte-se a milhares de pessoas que já transformaram suas vidas
            </p>
            <a href="produtos.php" style="display: inline-block; background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); color: #0a1628; padding: 20px 50px; border-radius: 50px; font-size: 1.2rem; font-weight: 800; text-decoration: none; transition: all 0.3s; box-shadow: 0 10px 30px rgba(0,255,136,0.3);">
                <i class="fas fa-fire"></i> COMEÇAR AGORA
            </a>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <a href="https://api.whatsapp.com/send?phone=<?php echo WHATSAPP_NUMBER; ?>&text=Olá!%20Gostaria%20de%20mais%20informações" 
       class="whatsapp-float" target="_blank" rel="noopener">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="assets/js/main.js"></script>
    <script>
        document.querySelectorAll('.video-testimonial-card').forEach(card => {
            const video = card.querySelector('video');
            const overlay = card.querySelector('.video-overlay');
            
            // Hide overlay when video plays
            video.addEventListener('play', function() {
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
            });
            
            // Show overlay when video pauses or ends
            video.addEventListener('pause', function() {
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
            });
            
            video.addEventListener('ended', function() {
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
            });
            
            // Click on overlay to play video
            overlay.addEventListener('click', function() {
                video.play();
            });
        });
    </script>
</body>
</html>
