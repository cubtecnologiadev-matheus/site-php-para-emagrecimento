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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cpf = cleanCPF($_POST['cpf'] ?? '');
    $phone = cleanPhone($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validações
    if (empty($name) || empty($email) || empty($cpf) || empty($password)) {
        $error = 'Preencha todos os campos obrigatórios!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email inválido!';
    } elseif (strlen($cpf) !== 11) {
        $error = 'CPF inválido!';
    } elseif ($password !== $confirm_password) {
        $error = 'As senhas não coincidem!';
    } elseif (strlen($password) < 6) {
        $error = 'A senha deve ter no mínimo 6 caracteres!';
    } else {
        // Verifica se CPF já existe
        if (userExists($cpf)) {
            $error = 'Este CPF já está cadastrado!';
        } else {
            // Registra o usuário
            $user = registerUser($name, $email, $cpf, $phone, $password);
            
            if ($user) {
                $success = 'Cadastro realizado com sucesso! Faça login para continuar.';
            } else {
                $error = 'Erro ao realizar cadastro. Tente novamente.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background: linear-gradient(135deg, #0a1628 0%, #1a2f4a 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div style="width: 100%; max-width: 550px;">
        <div style="background: #fff; border-radius: 25px; padding: 50px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 2rem; font-weight: 900; color: #0a1628; margin-bottom: 10px;">
                    DA MAMÃE <span style="color: #00ff88;">FITNESS</span>
                </h1>
                <p style="color: #718096; font-size: 1rem;">Crie sua conta e comece a treinar</p>
            </div>
            
            <?php if ($error): ?>
                <div style="background: #fee; border: 2px solid #dc3545; border-radius: 10px; padding: 15px; margin-bottom: 25px; color: #721c24; text-align: center;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="background: #d4edda; border: 2px solid #00ff88; border-radius: 10px; padding: 15px; margin-bottom: 25px; color: #155724; text-align: center;">
                    <i class="fas fa-check-circle" style="color: #00ff88;"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                <div>
                    <label style="display: block; font-weight: 700; color: #0a1628; margin-bottom: 10px; font-size: 0.95rem;">
                        <i class="fas fa-user" style="color: #00ff88;"></i> Nome Completo *
                    </label>
                    <input type="text" id="name" name="name" placeholder="Seu nome completo" required 
                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                           style="width: 100%; padding: 15px 20px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; font-weight: 700; color: #0a1628; margin-bottom: 10px; font-size: 0.95rem;">
                            <i class="fas fa-envelope" style="color: #00ff88;"></i> Email *
                        </label>
                        <input type="email" id="email" name="email" placeholder="seu@email.com" required 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                               style="width: 100%; padding: 15px 20px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 700; color: #0a1628; margin-bottom: 10px; font-size: 0.95rem;">
                            <i class="fas fa-id-card" style="color: #00ff88;"></i> CPF *
                        </label>
                        <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required maxlength="14"
                               style="width: 100%; padding: 15px 20px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                    </div>
                </div>
                
                <div>
                    <label style="display: block; font-weight: 700; color: #0a1628; margin-bottom: 10px; font-size: 0.95rem;">
                        <i class="fas fa-phone" style="color: #00ff88;"></i> Telefone
                    </label>
                    <input type="text" id="phone" name="phone" placeholder="(00) 00000-0000" maxlength="15"
                           style="width: 100%; padding: 15px 20px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; font-weight: 700; color: #0a1628; margin-bottom: 10px; font-size: 0.95rem;">
                            <i class="fas fa-lock" style="color: #00ff88;"></i> Senha *
                        </label>
                        <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" required
                               style="width: 100%; padding: 15px 20px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 700; color: #0a1628; margin-bottom: 10px; font-size: 0.95rem;">
                            <i class="fas fa-lock" style="color: #00ff88;"></i> Confirmar *
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Digite novamente" required
                               style="width: 100%; padding: 15px 20px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1rem;">
                    </div>
                </div>
                
                <button type="submit" style="background: linear-gradient(135deg, #00ff88 0%, #00d4ff 100%); color: #0a1628; padding: 18px; border: none; border-radius: 50px; font-size: 1.1rem; font-weight: 800; cursor: pointer; transition: all 0.3s; box-shadow: 0 10px 30px rgba(0,255,136,0.3); margin-top: 10px;">
                    <i class="fas fa-user-plus"></i> CADASTRAR
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 30px; padding-top: 30px; border-top: 2px solid #e2e8f0;">
                <p style="color: #718096; margin-bottom: 10px;">Já tem uma conta? <a href="login.php" style="color: #00ff88; font-weight: 700; text-decoration: none;">Faça login</a></p>
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
        
        // Máscara de Telefone
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
                e.target.value = value;
            }
        });
    </script>
</body>
</html>
