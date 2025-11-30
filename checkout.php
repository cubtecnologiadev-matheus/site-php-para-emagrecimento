<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

// Verifica se tem produto selecionado
if (!isset($_GET['product']) || empty($_GET['product'])) {
    header('Location: index.php');
    exit;
}

$productId = $_GET['product'];
$products = loadProducts();

if (!isset($products[$productId])) {
    header('Location: index.php');
    exit;
}

$product = $products[$productId];
$error = '';
$success = '';

// Se usuário está logado, pega os dados
$userData = [];
if (isset($_SESSION['user_id'])) {
    $user = getUserById($_SESSION['user_id']);
    $userData = [
        'name' => $user['name'],
        'email' => $user['email'],
        'cpf' => $user['cpf'],
        'phone' => $user['phone'] ?? ''
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Adding dark gradient background to match index.php and produtos.php -->
    <div class="checkout-container" style="background: linear-gradient(135deg, var(--dark-bg) 0%, #1a1f3a 100%); position: relative; overflow: hidden;">
        <!-- Technological overlay effects -->
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0;">
            <div style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle at 30% 50%, rgba(0, 255, 136, 0.08) 0%, transparent 50%);"></div>
            <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: radial-gradient(circle at 70% 50%, rgba(0, 229, 255, 0.08) 0%, transparent 50%);"></div>
        </div>
        
        <div class="checkout-content" style="position: relative; z-index: 1;">
            <div class="checkout-form">
                <h2>Finalizar Compra</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form id="checkoutForm" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                    
                    <div class="form-group" id="nameGroup">
                        <label for="name">Nome Completo *</label>
                        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($userData['name'] ?? ''); ?>">
                        <span class="error-message">Por favor, digite seu nome completo</span>
                    </div>
                    
                    <div class="form-group" id="emailGroup">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>">
                        <span class="error-message">Por favor, digite um email válido</span>
                    </div>
                    
                    <div class="form-group" id="cpfGroup">
                        <label for="cpf">CPF *</label>
                        <input type="text" id="cpf" name="cpf" required maxlength="14" value="<?php echo htmlspecialchars($userData['cpf'] ?? ''); ?>">
                        <span class="error-message">CPF inválido. Digite um CPF válido</span>
                    </div>
                    
                    <div class="form-group" id="phoneGroup">
                        <label for="phone">Telefone *</label>
                        <input type="text" id="phone" name="phone" required maxlength="15" placeholder="(XX) 9XXXX-XXXX" value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>">
                        <span class="error-message">Telefone inválido. Formato: (XX) 9XXXX-XXXX</span>
                    </div>
                    
                    <!-- Button starts disabled and only enables when all validations pass -->
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-block" disabled>Confirmar.</button>
                </form>
            </div>
            
            <div class="checkout-summary">
                <h3>Resumo do Pedido</h3>
                
                <div class="summary-product">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                    <div>
                        <h4><?php echo htmlspecialchars($product['title']); ?></h4>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                    </div>
                </div>
                
                <div class="summary-details">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></span>
                    </div>
                    
                    <?php if ($product['discount'] > 0): ?>
                    <div class="summary-row discount">
                        <span>Desconto (<?php echo $product['discount']; ?>%):</span>
                        <span>- R$ <?php echo number_format($product['price'] * ($product['discount'] / 100), 2, ',', '.'); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>R$ <?php echo number_format($product['final_price'], 2, ',', '.'); ?></span>
                    </div>
                </div>
                
                <div class="payment-info">
                    <h4>Pagamento via PIX</h4>
                    <p>Após preencher seus dados, você receberá um QR Code para pagamento instantâneo via PIX.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal PIX -->
    <div id="pixModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Pagamento via PIX</h3>
                <span class="modal-close">&times;</span>
            </div>
            
            <div class="modal-body">
                <div id="pixLoading" class="loading">
                    <div class="spinner"></div>
                    <p>Gerando QR Code...</p>
                </div>
                
                <div id="pixContent" style="display: none;">
                    <div class="pix-qrcode">
                        <img id="qrcodeImage" src="/placeholder.svg" alt="QR Code PIX">
                    </div>
                    
                    <div class="pix-info">
                        <p><strong>Valor:</strong> <span id="pixValue"></span></p>
                        <p><strong>Status:</strong> <span id="pixStatus" class="status-pending">Aguardando pagamento</span></p>
                    </div>
                    
                    <div class="pix-copy">
                        <label>Código PIX Copia e Cola:</label>
                        <div class="copy-container">
                            <input type="text" id="pixCode" readonly>
                            <button type="button" class="btn btn-secondary" onclick="copyPixCode()">Copiar</button>
                        </div>
                    </div>
                    
                    <div class="pix-instructions">
                        <h4>Como pagar:</h4>
                        <ol>
                            <li>Abra o app do seu banco</li>
                            <li>Escolha pagar via PIX</li>
                            <li>Escaneie o QR Code ou cole o código</li>
                            <li>Confirme o pagamento</li>
                        </ol>
                    </div>
                </div>
                
                <div id="pixSuccess" style="display: none;">
                    <div class="success-icon">✓</div>
                    <h3>Pagamento Confirmado!</h3>
                    <p>Seu pagamento foi aprovado com sucesso.</p>
                    
                    <!-- Adicionando exibição de credenciais de login -->
                    <div class="credentials-box">
                        <h4>🎉 Parabéns pela sua compra!</h4>
                        <p>Sua conta foi criada automaticamente. Use os dados abaixo para fazer login:</p>
                        
                        <div class="credential-item">
                            <strong>Login (CPF):</strong>
                            <span id="userLogin"></span>
                        </div>
                        
                        <div class="credential-item">
                            <strong>Senha:</strong>
                            <span class="password-display">12345678</span>
                        </div>
                        
                        <p class="credential-note">💡 Você pode alterar sua senha após fazer login</p>
                    </div>
                    
                    <a href="login.php" class="btn btn-primary btn-glow">
                        ✨ Clique aqui e faça login agora para acessar seu curso
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
    <script>
        function validateCPF(cpf) {
            cpf = cpf.replace(/\D/g, '');
            
            if (cpf.length !== 11) return false;
            
            // Verifica se todos os dígitos são iguais
            if (/^(\d)\1{10}$/.test(cpf)) return false;
            
            // Valida primeiro dígito verificador
            let sum = 0;
            for (let i = 0; i < 9; i++) {
                sum += parseInt(cpf.charAt(i)) * (10 - i);
            }
            let digit = 11 - (sum % 11);
            if (digit >= 10) digit = 0;
            if (digit !== parseInt(cpf.charAt(9))) return false;
            
            // Valida segundo dígito verificador
            sum = 0;
            for (let i = 0; i < 10; i++) {
                sum += parseInt(cpf.charAt(i)) * (11 - i);
            }
            digit = 11 - (sum % 11);
            if (digit >= 10) digit = 0;
            if (digit !== parseInt(cpf.charAt(10))) return false;
            
            return true;
        }
        
        function validatePhone(phone) {
            const cleaned = phone.replace(/\D/g, '');
            // Formato: (XX) 9XXXX-XXXX = 11 dígitos, começando com 9 após o DDD
            if (cleaned.length !== 11) return false;
            if (cleaned.charAt(2) !== '9') return false;
            return true;
        }
        
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        function validateField(fieldId, validationFn) {
            const input = document.getElementById(fieldId);
            const group = document.getElementById(fieldId + 'Group');
            
            const isValid = validationFn(input.value);
            
            if (input.value.trim() === '') {
                group.classList.remove('error', 'valid');
            } else if (isValid) {
                group.classList.remove('error');
                group.classList.add('valid');
            } else {
                group.classList.remove('valid');
                group.classList.add('error');
            }
            
            checkAllFields();
        }
        
        function checkAllFields() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const cpf = document.getElementById('cpf').value;
            const phone = document.getElementById('phone').value;
            
            const isNameValid = name.length > 0;
            const isEmailValid = validateEmail(email);
            const isCpfValid = validateCPF(cpf);
            const isPhoneValid = validatePhone(phone);
            
            const submitBtn = document.getElementById('submitBtn');
            
            if (isNameValid && isEmailValid && isCpfValid && isPhoneValid) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }
        
        document.getElementById('name').addEventListener('input', function() {
            validateField('name', (val) => val.trim().length > 0);
        });
        
        document.getElementById('email').addEventListener('input', function() {
            validateField('email', validateEmail);
        });
        
        document.getElementById('cpf').addEventListener('input', function(e) {
            // Máscara de CPF
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                e.target.value = value;
            }
            validateField('cpf', validateCPF);
        });
        
        document.getElementById('phone').addEventListener('input', function(e) {
            // Máscara de Telefone
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
                e.target.value = value;
            }
            validateField('phone', validatePhone);
        });
        
        // Submit do formulário
        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const cpfInput = document.getElementById('cpf').value;
            const phoneInput = document.getElementById('phone').value;
            const emailInput = document.getElementById('email').value;
            
            // Valida CPF
            if (!validateCPF(cpfInput)) {
                alert('CPF inválido! Por favor, verifique o número digitado.');
                document.getElementById('cpf').focus();
                return;
            }
            
            // Valida telefone
            if (!validatePhone(phoneInput)) {
                alert('Telefone inválido! O formato correto é: (XX) 9XXXX-XXXX');
                document.getElementById('phone').focus();
                return;
            }
            
            // Valida email
            if (!validateEmail(emailInput)) {
                alert('Email inválido! Por favor, digite um email válido.');
                document.getElementById('email').focus();
                return;
            }
            
            console.log('[v0] Form submitted');
            
            const formData = new FormData(this);
            const productId = formData.get('product_id');
            const product = <?php echo json_encode($product); ?>;
            
            // Abre o modal
            document.getElementById('pixModal').style.display = 'flex';
            document.getElementById('pixLoading').style.display = 'block';
            document.getElementById('pixContent').style.display = 'none';
            
            try {
                // Formata o valor como string com 2 casas decimais
                const pixAmount = parseFloat(product.final_price);
                const formattedAmount = pixAmount.toFixed(2);
                
                // Monta o payload exatamente como o pagamento.php funcional
                const payload = {
                    amount: formattedAmount,
                    description: product.title,
                    customer: {
                        name: formData.get('name'),
                        email: formData.get('email'),
                        phone: formData.get('phone').replace(/\D/g, ''),
                        document: {
                            type: 'cpf',
                            number: formData.get('cpf').replace(/\D/g, '')
                        }
                    }
                };
                
                console.log('[v0] Creating PIX with payload:', payload);
                
                // Chama a API diretamente
                const response = await fetch('api/pix/create.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                console.log('[v0] Response status:', response.status);
                
                const result = await response.json();
                console.log('[v0] Response data:', result);
                
                if (!response.ok || result?.ok === false) {
                    throw new Error(result?.error?.message || `HTTP ${response.status}: ${response.statusText}`);
                }
                
                // Extrai os dados da resposta
                const data = result.data || result.charge || {};
                const transactionId = String(data.id || data.transactionId || data.objectId || data.txid || '');
                const brcode = data?.pix?.qrcode || data?.qrcode || data?.copiaECola || '';
                
                console.log('[v0] Transaction ID:', transactionId);
                console.log('[v0] PIX Code:', brcode);
                
                // Mostra o QR Code
                document.getElementById('pixLoading').style.display = 'none';
                document.getElementById('pixContent').style.display = 'block';
                
                document.getElementById('qrcodeImage').src = `api/pix/qrcode.php?id=${transactionId}`;
                document.getElementById('pixValue').textContent = 'R$ ' + pixAmount.toFixed(2).replace('.', ',');
                document.getElementById('pixCode').value = brcode;
                
                // Inicia verificação de pagamento
                checkPaymentStatus(transactionId);
                
            } catch (error) {
                console.error('[v0] PIX Error:', error);
                const errorMsg = error.message.includes('HTTP') ? 
                    'Erro de conexão com o gateway de pagamento. Tente novamente.' : 
                    error.message;
                alert('Erro ao gerar pagamento: ' + errorMsg);
                document.getElementById('pixModal').style.display = 'none';
            }
        });
        
        // Verifica status do pagamento
        let checkInterval;
        function checkPaymentStatus(transactionId) {
            console.log('[v0] Starting payment status check for:', transactionId);
            
            const cpfInput = document.getElementById('cpf').value;
            const cpfClean = cpfInput.replace(/\D/g, '');
            
            checkInterval = setInterval(async () => {
                try {
                    const response = await fetch('check_payment.php?transaction_id=' + transactionId);
                    const result = await response.json();
                    
                    console.log('[v0] Payment check result:', result);
                    
                    // Atualiza o status na tela
                    const statusElement = document.getElementById('pixStatus');
                    
                    if (result.success && result.status === 'paid') {
                        statusElement.textContent = 'Pagamento confirmado!';
                        statusElement.className = 'status-success';
                        clearInterval(checkInterval);
                        
                        document.getElementById('userLogin').textContent = cpfClean;
                        
                        // Mostra sucesso com credenciais
                        document.getElementById('pixContent').style.display = 'none';
                        document.getElementById('pixSuccess').style.display = 'block';
                    } else if (result.status === 'refused' || result.status === 'refunded') {
                        statusElement.textContent = 'Pagamento recusado';
                        statusElement.className = 'status-error';
                        clearInterval(checkInterval);
                    } else {
                        statusElement.textContent = 'Aguardando pagamento';
                        statusElement.className = 'status-pending';
                    }
                } catch (error) {
                    console.error('[v0] Error checking payment:', error);
                }
            }, 4000); // Verifica a cada 4 segundos
        }
        
        // Copia código PIX
        function copyPixCode() {
            const pixCode = document.getElementById('pixCode');
            pixCode.select();
            document.execCommand('copy');
            
            alert('Código PIX copiado!');
        }
        
        // Fecha modal
        document.querySelector('.modal-close').addEventListener('click', function() {
            if (confirm('Tem certeza que deseja cancelar o pagamento?')) {
                clearInterval(checkInterval);
                document.getElementById('pixModal').style.display = 'none';
            }
        });
    </script>
</body>
</html>
