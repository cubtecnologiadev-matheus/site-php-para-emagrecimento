<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Por favor, preencha todos os campos obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Por favor, insira um e-mail válido.';
    } else {
        // Aqui você pode adicionar lógica para enviar e-mail ou salvar no banco
        // Por enquanto, apenas simula o sucesso
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Entre em contato conosco e tire suas dúvidas">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero section com design fitness -->
    <section class="product-hero" style="background: linear-gradient(135deg, #0a1628 0%, #1a2f4a 100%); padding: 80px 0;">
        <div class="container">
            <div class="hero-content" style="text-align: center; max-width: 800px; margin: 0 auto;">
                <h1 style="font-size: 3rem; font-weight: 900; color: #fff; margin-bottom: 20px; line-height: 1.2;">
                    FALE <span style="color: #00ff88;">CONOSCO</span>
                </h1>
                <p style="font-size: 1.3rem; color: #a0aec0; margin-bottom: 30px;">
                    Estamos aqui para ajudar você a começar sua transformação
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section com design moderno -->
    <section style="padding: 80px 0; background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1f3a 100%);">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 60px;">
                <!-- Contact Info com cards -->
                <div>
                    <h2 style="font-size: 2rem; font-weight: 900; color: #fff; margin-bottom: 20px;">
                        Entre em Contato
                    </h2>
                    <p style="color: #a0aec0; font-size: 1.1rem; margin-bottom: 40px; line-height: 1.6;">
                        Tem alguma dúvida? Fale conosco através dos canais abaixo ou preencha o formulário.
                    </p>
                    
                    <div style="display: flex; flex-direction: column; gap: 25px;">
                        <div style="background: rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px; display: flex; align-items: center; gap: 20px; backdrop-filter: blur(10px); border: 1px solid rgba(0, 255, 136, 0.1);">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-phone" style="font-size: 1.5rem; color: #0a1628;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 1.1rem; font-weight: 800; color: #0a1628; margin-bottom: 5px;">Telefone</h3>
                                <p style="color: #718096; font-size: 1rem;">(11) 2626-0429</p>
                            </div>
                        </div>
                        
                        <div style="background: rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px; display: flex; align-items: center; gap: 20px; backdrop-filter: blur(10px); border: 1px solid rgba(37, 211, 102, 0.2);">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fab fa-whatsapp" style="font-size: 1.5rem; color: #fff;"></i>
                            </div>
                            <div style="flex: 1;">
                                <h3 style="font-size: 1.1rem; font-weight: 800; color: #0a1628; margin-bottom: 5px;">WhatsApp</h3>
                                <p style="color: #718096; font-size: 1rem; margin-bottom: 10px;"><?php echo WHATSAPP_NUMBER; ?></p>
                                <a href="https://api.whatsapp.com/send?phone=<?php echo WHATSAPP_NUMBER; ?>&text=Olá!%20Gostaria%20de%20mais%20informações" 
                                   target="_blank" style="display: inline-block; background: #25d366; color: #fff; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-size: 0.9rem; font-weight: 700;">
                                    Enviar Mensagem
                                </a>
                            </div>
                        </div>
                        
                        <div style="background: rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px; display: flex; align-items: center; gap: 20px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 107, 53, 0.2);">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-envelope" style="font-size: 1.5rem; color: #fff;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 1.1rem; font-weight: 800; color: #0a1628; margin-bottom: 5px;">E-mail</h3>
                                <p style="color: #718096; font-size: 1rem;"><?php echo CONTACT_EMAIL; ?></p>
                            </div>
                        </div>
                        
                        <div style="background: rgba(255, 255, 255, 0.05); border-radius: 15px; padding: 25px; display: flex; align-items: center; gap: 20px; backdrop-filter: blur(10px); border: 1px solid rgba(0, 212, 255, 0.2);">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-clock" style="font-size: 1.5rem; color: #fff;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 1.1rem; font-weight: 800; color: #0a1628; margin-bottom: 5px;">Horário</h3>
                                <p style="color: #718096; font-size: 0.95rem; margin-bottom: 3px;">Seg a Sex: 9h às 18h</p>
                                <p style="color: #718096; font-size: 0.95rem;">Sábado: 9h às 13h</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form moderno -->
                <div style="background: rgba(255, 255, 255, 0.05); border-radius: 25px; padding: 50px; backdrop-filter: blur(10px); border: 1px solid rgba(0, 255, 136, 0.1);">
                    <h2 style="font-size: 2rem; font-weight: 900; color: #fff; margin-bottom: 30px;">Envie sua Mensagem</h2>
                    
                    <?php if ($success): ?>
                    <div style="background: #d4edda; border: 2px solid #00ff88; border-radius: 10px; padding: 15px; margin-bottom: 25px; color: #155724;">
                        <i class="fas fa-check-circle" style="color: #00ff88;"></i>
                        Mensagem enviada com sucesso! Entraremos em contato em breve.
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                    <div style="background: #f8d7da; border: 2px solid #dc3545; border-radius: 10px; padding: 15px; margin-bottom: 25px; color: #721c24;">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" style="display: flex; flex-direction: column; gap: 25px;">
                        <div>
                            <label style="display: block; font-weight: 700; color: #fff; margin-bottom: 10px; font-size: 0.95rem;">Nome Completo *</label>
                            <input type="text" name="name" required 
                                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                                   style="width: 100%; padding: 15px 20px; border: 2px solid rgba(0, 255, 136, 0.2); border-radius: 10px; font-size: 1rem; transition: all 0.3s; background: rgba(255, 255, 255, 0.05); color: #fff;">
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <label style="display: block; font-weight: 700; color: #fff; margin-bottom: 10px; font-size: 0.95rem;">E-mail *</label>
                                <input type="email" name="email" required 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                       style="width: 100%; padding: 15px 20px; border: 2px solid rgba(0, 255, 136, 0.2); border-radius: 10px; font-size: 1rem; background: rgba(255, 255, 255, 0.05); color: #fff;">
                            </div>
                            
                            <div>
                                <label style="display: block; font-weight: 700; color: #fff; margin-bottom: 10px; font-size: 0.95rem;">Telefone</label>
                                <input type="tel" name="phone" 
                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                                       style="width: 100%; padding: 15px 20px; border: 2px solid rgba(0, 255, 136, 0.2); border-radius: 10px; font-size: 1rem; background: rgba(255, 255, 255, 0.05); color: #fff;">
                            </div>
                        </div>
                        
                        <div>
                            <label style="display: block; font-weight: 700; color: #fff; margin-bottom: 10px; font-size: 0.95rem;">Assunto</label>
                            <select name="subject" style="width: 100%; padding: 15px 20px; border: 2px solid rgba(0, 255, 136, 0.2); border-radius: 10px; font-size: 1rem; background: rgba(255, 255, 255, 0.05); color: #fff;">
                                <option value="">Selecione um assunto</option>
                                <option value="duvida">Dúvida sobre programas</option>
                                <option value="pedido">Informações sobre pedido</option>
                                <option value="suporte">Suporte técnico</option>
                                <option value="parceria">Parceria</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                        
                        <div>
                            <label style="display: block; font-weight: 700; color: #fff; margin-bottom: 10px; font-size: 0.95rem;">Mensagem *</label>
                            <textarea name="message" rows="5" required 
                                      style="width: 100%; padding: 15px 20px; border: 2px solid rgba(0, 255, 136, 0.2); border-radius: 10px; font-size: 1rem; resize: vertical; background: rgba(255, 255, 255, 0.05); color: #fff;"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" style="background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); color: #0a1628; padding: 18px 40px; border: none; border-radius: 50px; font-size: 1.1rem; font-weight: 800; cursor: pointer; transition: all 0.3s; box-shadow: 0 10px 30px rgba(0,255,136,0.3);">
                            <i class="fas fa-paper-plane"></i> ENVIAR MENSAGEM
                        </button>
                    </form>
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
