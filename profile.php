<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = getUserById($_SESSION['user_id']);
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($name) || empty($email)) {
        $error = 'Nome e email são obrigatórios.';
    } else {
        $updateData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ];
        
        if (!empty($new_password)) {
            if ($new_password !== $confirm_password) {
                $error = 'As senhas não coincidem.';
            } elseif (!password_verify($current_password, $user['password'])) {
                $error = 'Senha atual incorreta.';
            } else {
                $updateData['password'] = password_hash($new_password, PASSWORD_DEFAULT);
            }
        }
        
        if (empty($error)) {
            if (updateUser($_SESSION['user_id'], $updateData)) {
                $message = 'Perfil atualizado com sucesso!';
                $user = getUserById($_SESSION['user_id']);
                $_SESSION['user_name'] = $user['name'];
            } else {
                $error = 'Erro ao atualizar perfil.';
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
    <title>Meu Perfil - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="dashboard-container">
        <div class="dashboard-sidebar">
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            
            <nav class="dashboard-nav">
                <a href="dashboard.php">Meus Cursos</a>
                <a href="my-recipes.php">Minhas Receitas</a>
                <a href="my-purchases.php">Minhas Compras</a>
                <a href="profile.php" class="active">Meu Perfil</a>
                <a href="logout.php">Sair</a>
            </nav>
        </div>
        
        <div class="dashboard-content">
            <h1>Meu Perfil</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="profile-form-container">
                <form method="POST" class="profile-form">
                    <div class="form-section">
                        <h2>Informações Pessoais</h2>
                        
                        <div class="form-group">
                            <label for="name">Nome Completo</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Telefone</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="cpf">CPF</label>
                            <input type="text" id="cpf" value="<?php echo htmlspecialchars($user['cpf']); ?>" disabled>
                            <small>O CPF não pode ser alterado</small>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2>Alterar Senha</h2>
                        <p class="form-hint">Deixe em branco se não quiser alterar a senha</p>
                        
                        <div class="form-group">
                            <label for="current_password">Senha Atual</label>
                            <input type="password" id="current_password" name="current_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">Nova Senha</label>
                            <input type="password" id="new_password" name="new_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirmar Nova Senha</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>
