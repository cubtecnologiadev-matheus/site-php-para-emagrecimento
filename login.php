<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

// Se já estiver logado, redireciona
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = cleanCPF($_POST['cpf'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $user = loginUser($cpf, $password);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_cpf'] = $user['cpf'];
        
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'CPF ou senha incorretos!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background: linear-gradient(135deg, #0a1628 0%, #1a2f4a 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div style="width: 100%; max-width: 480px;">
        <div style="background: #fff; border-radius: 25px; padding: 50px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 2rem; font-weight: 900; color: #0a1628; margin-bottom: 10px;">
                    DA MAMÃE <span style="color: #00ff88;">FITNESS</span>
                </h1>
                <p style="color: #718096; font-size: 1rem;">Entre na sua conta</p>
            </div>
            
            <?php if ($error): ?>
                <div style="background: #fee; border: 2px solid #dc3545; border-radius: 10px; padding: 15px; margin-bottom: 25px; color: #721c24; text-align: center;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" style="display: flex; flex-direction: column; gap: 25px;">
                <div>
                    <label style="display: block; font-weight: 700; color: #0a1628; margin-bottom: 10px; font-size: 0.95rem;">
                        <i class="fas fa-id-card" style="color: #00ff88;"></i> CPF
                    </label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required maxlength="14"
                           style="width: 100%; padding: 15px 20px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem; transition: all 0.3s;">
                </div>
                
                <div>
                    <label style="display: block; font-weight: 700; color: #0a1628; margin-bottom: 10px; font-size: 0.95rem;">
                        <i class="fas fa-lock" style="color: #00ff88;"></i> Senha
                    </label>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha" required
                           style="width: 100%; padding: 15px 20px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                </div>
                
                <button type="submit" style="background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); color: #0a1628; padding: 18px; border: none; border-radius: 50px; font-size: 1.1rem; font-weight: 800; cursor: pointer; transition: all 0.3s; box-shadow: 0 10px 30px rgba(0,255,136,0.3);">
                    <i class="fas fa-sign-in-alt"></i> ENTRAR
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 30px; padding-top: 30px; border-top: 2px solid #e2e8f0;">
                <p style="color: #718096; margin-bottom: 10px;">Não tem uma conta? <a href="register.php" style="color: #00ff88; font-weight: 700; text-decoration: none;">Cadastre-se</a></p>
                <p><a href="index.php" style="color: #718096; text-decoration: none; font-weight: 600;"><i class="fas fa-arrow-left"></i> Voltar para o site</a></p>
            </div>
        </div>
    </div>
    
    <script src="assets/js/main.js"></script>
    <script>
        // Máscara de CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                e.target.value = value;
            }
        });
    </script>
</body>
</html>
