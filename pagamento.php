<?php
date_default_timezone_set('America/Sao_Paulo');

session_start();

if (!isset($_SESSION['orderData'])) {
    header('Location: resumo.php');
    exit;
}

$orderData = $_SESSION['orderData'];
$shippingData = $_SESSION['shippingData'] ?? null;
$shippingCost = floatval($shippingData['shipping_cost'] ?? 0);

function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

$pixAmount = 0;

// First try to get from entrada_personalizada
if (isset($orderData['simulation']['entrada_personalizada']) && !empty($orderData['simulation']['entrada_personalizada'])) {
    $pixAmount = floatval($orderData['simulation']['entrada_personalizada']);
} 
// Fallback to regular entrada
elseif (isset($orderData['simulation']['entrada']) && !empty($orderData['simulation']['entrada'])) {
    $pixAmount = floatval($orderData['simulation']['entrada']);
}
// Final fallback - calculate 10% of product price
else {
    $productPrice = floatval($orderData['simulation']['produto_preco'] ?? 0);
    $pixAmount = $productPrice > 0 ? round($productPrice * 0.1) : 100;
}

if ($shippingCost > 0) {
    $pixAmount += $shippingCost;
}

if ($pixAmount <= 0) {
    $pixAmount = 100;
}

$productName = $orderData['simulation']['produto_nome'] ?? 'iPhone não especificado';
$productPrice = floatval($orderData['simulation']['produto_preco'] ?? 0);
$parcelas = intval($orderData['simulation']['parcelas_personalizadas'] ?? ($orderData['simulation']['parcelas'] ?? 12));

$valorParcela = floatval($orderData['simulation']['valor_parcela'] ?? 0);

// If installment value is not in session, recalculate with interest
if ($valorParcela == 0 && $parcelas > 0) {
    $entrada = floatval($orderData['simulation']['entrada_personalizada'] ?? ($orderData['simulation']['entrada'] ?? 0));
    $saldo = $productPrice - $entrada;
    $taxaJurosMensal = 0.02; // 2% ao mês
    $fator = pow(1 + $taxaJurosMensal, $parcelas);
    $valorParcela = $saldo * (($taxaJurosMensal * $fator) / ($fator - 1));
    $valorParcela = round($valorParcela, 2);
}

$customerName = $orderData['customer']['nome'] ?? 'Cliente';
$customerEmail = $orderData['customer']['email'] ?? '';
$customerPhone = $orderData['customer']['telefone'] ?? '';
$customerCPF = $orderData['customer']['cpf'] ?? '';
$productImage = $orderData['simulation']['produto_imagem'] ?? '/placeholder.svg?height=100&width=100';

if (strpos($productImage, 'data/') === 0) {
    $productImage = '../' . $productImage;
}

$transactionId = $_SESSION['transactionId'] ?? null;

$paymentDate = date('d/m/Y', strtotime('+30 days'));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento - IStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .spinner{width:18px;height:18px;border:3px solid #e5e7eb;border-top-color:#16a34a;border-radius:50%;animation:spin 1s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
        .modal-overlay {
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.9);
        }
    </style>
</head>
<body class="min-h-screen bg-black text-white">
    <?php include '../components/banner-financiamento.php'; ?>
    <?php include '../components/header.php'; ?>
    
    <!-- Reduced top padding from 200px to pt-24 (96px) for compact layout -->
    <div class="pt-24 px-4 sm:px-5 pb-12">
        <div class="max-w-4xl mx-auto">
            <!-- Reduced title size and margin for compact design -->
            <div class="text-center mb-3">
                <h1 class="text-xl lg:text-2xl font-black mb-2 bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                    Pagamento da Entrada
                </h1>
                <p class="text-gray-400 text-xs lg:text-sm">Escaneie o QR Code ou copie o código PIX para pagar</p>
            </div>
            
            <!-- Reduced gap from gap-6 lg:gap-8 to gap-3 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                <!-- PIX Payment Section -->
                <!-- Reduced padding from p-6 lg:p-8 to p-3 -->
                <div class="bg-gray-900/50 backdrop-blur-sm border border-white/10 rounded-xl lg:rounded-2xl p-3">
                    <h2 class="text-base lg:text-lg font-black mb-3 text-center">PIX - Entrada</h2>
                    <!-- Reduced spacing from space-y-4 to space-y-2 -->
                    <div class="space-y-2">
                        <!-- Reduced padding from p-4 lg:p-6 to p-2 lg:p-3 -->
                        <div class="text-center bg-gray-800/50 rounded-lg lg:rounded-xl p-2 lg:p-3">
                            <div class="text-[10px] lg:text-xs text-gray-400 mb-1">Valor da entrada</div>
                            <div class="text-2xl lg:text-3xl font-black text-cyan-400" id="pixAmount"><?php echo formatarMoeda($pixAmount); ?></div>
                        </div>
                        <!-- Reduced QR code size and padding -->
                        <div class="bg-white rounded-lg lg:rounded-xl p-3 lg:p-4 text-center" id="qrSection">
                            <div id="qrLoading" class="w-32 h-32 lg:w-40 lg:h-40 mx-auto bg-gray-200 rounded-lg flex items-center justify-center">
                                <div class="text-gray-600 text-xs flex items-center gap-2">
                                    <span class="spinner"></span>
                                    Gerando PIX...
                                </div>
                            </div>
                            <img id="qrCode" class="w-32 h-32 lg:w-40 lg:h-40 mx-auto rounded-lg" style="display:none" alt="QR Code PIX">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-300">
                                Código PIX (Copiar e Colar)
                            </label>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <textarea id="pixCode" readonly class="flex-1 bg-gray-800 border border-gray-600 rounded-lg px-3 py-2 text-white text-xs min-h-[60px] lg:min-h-[70px] resize-none"></textarea>
                                <button onclick="copyPixKey()" id="copyBtn" class="bg-cyan-400 text-black px-3 lg:px-4 py-2 rounded-lg font-bold hover:bg-cyan-300 transition-colors text-xs lg:text-sm">
                                    Copiar
                                </button>
                            </div>
                        </div>
                        <!-- Reduced padding from p-4 lg:p-6 to p-2 lg:p-3 -->
                        <div id="statusSection" class="bg-blue-900/20 border border-blue-500/30 rounded-lg lg:rounded-xl p-2 lg:p-3">
                            <h3 class="text-xs lg:text-sm font-bold text-blue-400 mb-1">Status do Pagamento:</h3>
                            <div id="paymentStatus" class="text-yellow-400 font-semibold text-xs lg:text-sm">Aguardando pagamento...</div>
                        </div>
                        <div class="bg-blue-900/20 border border-blue-500/30 rounded-lg lg:rounded-xl p-2 lg:p-3">
                            <h3 class="text-xs lg:text-sm font-bold text-blue-400 mb-2">Como pagar:</h3>
                            <ol class="space-y-1 text-[10px] lg:text-xs text-gray-300">
                                <li>1. Abra o app do seu banco</li>
                                <li>2. Escolha a opção PIX</li>
                                <li>3. Escaneie o QR Code ou cole o código</li>
                                <li>4. Confirme o pagamento</li>
                                <li>5. Aguarde a confirmação automática</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Section -->
                <!-- Reduced padding from p-6 lg:p-8 to p-3 -->
                <div class="bg-gray-900/50 backdrop-blur-sm border border-white/10 rounded-xl lg:rounded-2xl p-3">
                    <h2 class="text-base lg:text-lg font-black mb-3 text-center">Resumo do Pedido</h2>
                    <!-- Reduced spacing from space-y-4 to space-y-2 -->
                    <div class="space-y-2">
                        <!-- Reduced padding from p-4 lg:p-6 to p-2 lg:p-3 -->
                        <div class="bg-gray-800/50 rounded-lg lg:rounded-xl p-2 lg:p-3">
                            <div class="flex items-center gap-2 lg:gap-3 mb-1">
                                <img src="<?php echo htmlspecialchars($productImage); ?>" 
                                     alt="<?php echo htmlspecialchars($productName); ?>" 
                                     class="w-8 h-8 lg:w-10 lg:h-10 object-contain bg-gray-700 rounded-lg p-1" 
                                     onerror="this.src='../placeholder.svg?height=48&width=48&text=<?php echo urlencode($productName); ?>'">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xs lg:text-sm font-bold truncate"><?php echo $productName; ?></h3>
                                    <p class="text-base lg:text-lg font-black text-cyan-400"><?php echo formatarMoeda($productPrice); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- Reduced spacing from space-y-2 to space-y-1 and text sizes -->
                        <div class="space-y-1">
                            <?php if ($shippingCost > 0): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-400 text-xs lg:text-sm">Entrada:</span>
                                    <span class="font-bold text-cyan-400 text-xs lg:text-sm"><?php echo formatarMoeda($pixAmount - $shippingCost); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400 text-xs lg:text-sm">Frete Express:</span>
                                    <span class="font-bold text-cyan-400 text-xs lg:text-sm"><?php echo formatarMoeda($shippingCost); ?></span>
                                </div>
                                <div class="flex justify-between border-t border-gray-600 pt-1">
                                    <span class="text-gray-400 text-xs lg:text-sm font-bold">Total PIX:</span>
                                    <span class="font-bold text-green-400 text-xs lg:text-sm"><?php echo formatarMoeda($pixAmount); ?></span>
                                </div>
                            <?php else: ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-400 text-xs lg:text-sm">Entrada (PIX):</span>
                                    <span class="font-bold text-cyan-400 text-xs lg:text-sm"><?php echo formatarMoeda($pixAmount); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="flex justify-between">
                                <span class="text-gray-400 text-xs lg:text-sm">Restante:</span>
                                <span class="font-semibold text-xs lg:text-sm">
                                    <?php echo $parcelas; ?>x de 
                                    <?php echo formatarMoeda($valorParcela); ?>
                                </span>
                            </div>
                            <div class="flex justify-between text-[10px] lg:text-xs text-gray-400">
                                <span>Taxa de juros:</span>
                                <span class="text-yellow-400">2% ao mês (12% ao ano)</span>
                            </div>
                        </div>
                        <!-- Reduced padding from p-4 lg:p-6 to p-2 lg:p-3 -->
                        <div class="bg-gray-800/50 rounded-lg lg:rounded-xl p-2 lg:p-3">
                            <h3 class="text-xs lg:text-sm font-bold mb-2 text-cyan-400">Cliente</h3>
                            <div class="space-y-1 text-[10px] lg:text-xs">
                                <p><span class="text-gray-400">Nome:</span> <?php echo $customerName; ?></p>
                                <p><span class="text-gray-400">E-mail:</span> <span class="break-all"><?php echo $customerEmail; ?></span></p>
                                <p><span class="text-gray-400">Telefone:</span> <?php echo $customerPhone; ?></p>
                            </div>
                        </div>
                        
                        <!-- Reduced padding and margins -->
                        <div class="bg-green-900/20 border border-green-500/30 rounded-lg lg:rounded-xl p-2 lg:p-3 mb-3 text-left">
                            <h3 class="text-xs lg:text-sm font-bold text-cyan-400 mb-2">Importante:</h3>
                            <ul class="space-y-1 text-[10px] lg:text-xs text-gray-300">
                                <li>• Após o pagamento, você receberá confirmação por e-mail</li>
                                <li>• O produto será enviado em até 24h após confirmação</li>
                                <li>• Código de rastreio será enviado via WhatsApp</li>
                                <li>• Os boletos das parcelas serão enviados por e-mail</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reduced margins and padding for buttons -->
            <div class="text-center mt-3 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="resumo.php" class="inline-block border-2 border-gray-600 text-gray-300 px-4 lg:px-6 py-2 rounded-lg font-bold hover:border-gray-400 transition-all text-xs lg:text-sm">
                    Voltar ao Resumo
                </a>
                
                <a href="https://wa.me/5545991277260?text=Olá! Gostaria de mais informações sobre financiamento de iPhone." class="inline-block border-2 border-cyan-400 text-cyan-400 px-4 lg:px-6 py-2 rounded-lg font-bold text-center hover:bg-cyan-400 hover:text-black transition-all text-xs lg:text-sm">
                    FALAR COM CONSULTOR
                </a>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 modal-overlay hidden items-center justify-center z-50 p-4">
        <!-- Reduced padding from p-6 lg:p-8 to p-3 lg:p-4 -->
        <div class="bg-gray-900 border border-green-500/30 rounded-xl lg:rounded-2xl p-3 lg:p-4 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="text-center">
                <div class="text-4xl lg:text-5xl mb-3">✅</div>
                <h2 class="text-base lg:text-xl font-black text-green-400 mb-3">Pagamento Confirmado!</h2>
                <p class="text-gray-300 mb-4 text-xs lg:text-sm">Obrigado pela sua compra! Você receberá um e-mail de confirmação em breve.</p>
                <!-- Reduced padding and text sizes -->
                <div class="bg-gray-800/50 rounded-lg lg:rounded-xl p-2 lg:p-3 mb-4">
                    <h3 class="text-xs lg:text-sm font-bold text-cyan-400 mb-1">Número do Protocolo</h3>
                    <p class="text-base lg:text-xl font-black text-white" id="protocolNumber">#<?php echo rand(100000, 999999); ?></p>
                </div>
                <div class="bg-gray-800/50 rounded-lg lg:rounded-xl p-2 lg:p-3 mb-4 text-left">
                    <h3 class="text-xs lg:text-sm font-bold text-cyan-400 mb-2">Resumo do Pedido</h3>
                    <div class="space-y-1 text-[10px] lg:text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Produto:</span>
                            <span class="truncate ml-2"><?php echo $productName; ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Valor Total:</span>
                            <span class="font-bold"><?php echo formatarMoeda($productPrice); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Entrada Paga:</span>
                            <span class="text-green-400 font-bold">
                                <?php echo formatarMoeda($pixAmount); ?>
                                <?php if ($shippingCost > 0): ?>
                                    <small class="block text-[10px] text-gray-400">(inclui frete)</small>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Parcelas:</span>
                            <span><?php echo $parcelas; ?>x de <?php echo formatarMoeda($valorParcela); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Primeiro vencimento:</span>
                            <span class="text-yellow-400"><?php echo $paymentDate; ?></span>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-900/20 border border-blue-500/30 rounded-lg lg:rounded-xl p-2 lg:p-3 mb-4 text-left">
                    <h3 class="text-xs lg:text-sm font-bold text-blue-400 mb-2">Próximos Passos</h3>
                    <ul class="space-y-1 text-[10px] lg:text-xs text-gray-300">
                        <li>• O envio pode ocorrer em até 24 horas</li>
                        <li>• A confirmação será enviada no seu WhatsApp dentro de alguns minutos</li>
                        <li>• Os boletos de pagamento serão enviados no seu e-mail</li>
                        <li>• Primeiro vencimento: <?php echo $paymentDate; ?></li>
                    </ul>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick="contactSupport()" class="flex-1 border-2 border-cyan-400 text-cyan-400 py-2 rounded-lg font-bold hover:bg-cyan-400 hover:text-black transition-all text-xs lg:text-sm">
                        Entrar em Contato
                    </button>
                    <button onclick="window.location.href='../index.php'" class="flex-1 bg-gradient-to-r from-cyan-400 to-blue-500 text-black py-2 rounded-lg font-bold hover:scale-105 transition-transform text-xs lg:text-sm">
                        Voltar ao Início
                    </button>
                </div>
                <div class="mt-3">
                    <button onclick="window.location.href='../simulacao.php'" class="text-cyan-400 hover:text-cyan-300 underline text-[10px] lg:text-xs">
                        Simular Outro Financiamento
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../components/footer.php'; ?>

    <script>
        let currentTransactionId = null;
        let statusInterval = null;

        window.addEventListener('DOMContentLoaded', function() {
            <?php if (!$transactionId): ?>
                createPixPayment();
            <?php else: ?>
                currentTransactionId = '<?php echo $transactionId; ?>';
                startStatusPolling();
            <?php endif; ?>
        });

        async function createPixPayment() {
            try {
                const pixAmountValue = <?php echo $pixAmount; ?>;
                const formattedAmount = pixAmountValue.toFixed(2);
                
                const payload = {
                    amount: formattedAmount,
                    description: 'Entrada - <?php echo $productName; ?>',
                    customer: {
                        name: '<?php echo $customerName; ?>',
                        email: '<?php echo $customerEmail; ?>',
                        phone: '<?php echo $customerPhone; ?>',
                        document: {
                            type: 'cpf',
                            number: '<?php echo $customerCPF; ?>'
                        }
                    }
                };

                console.log('[v0] Creating PIX with payload:', payload);

                const response = await fetch('../api/pix/create.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();
                console.log('[v0] PIX creation response:', result);
                
                if (!response.ok || result?.ok === false) {
                    throw new Error(result?.error?.message || `HTTP ${response.status}: ${response.statusText}`);
                }

                const data = result.data || result.charge || {};
                currentTransactionId = String(data.id || data.transactionId || data.objectId || data.txid || '');
                const brcode = data?.pix?.qrcode || data?.qrcode || data?.copiaECola || '';
                
                console.log('[v0] Transaction ID:', currentTransactionId);
                console.log('[v0] PIX Code:', brcode);
                
                document.getElementById('pixCode').value = brcode;
                document.getElementById('qrLoading').style.display = 'none';
                
                const qrCodeImg = document.getElementById('qrCode');
                qrCodeImg.src = `../api/pix/qrcode.php?id=${currentTransactionId}`;
                qrCodeImg.onload = function() {
                    console.log('[v0] QR Code loaded successfully');
                    this.style.display = 'block';
                };
                qrCodeImg.onerror = function() {
                    console.log('[v0] QR Code failed to load, trying alternative URL');
                    this.src = `../api/pix/${currentTransactionId}/qrcode.png`;
                    this.onerror = function() {
                        console.log('[v0] Both QR Code URLs failed');
                        document.getElementById('qrLoading').innerHTML = '<div class="text-red-600 text-sm">QR Code não disponível<br>Use o código PIX abaixo</div>';
                    };
                };

                startStatusPolling();

            } catch (error) {
                console.error('[v0] PIX Error:', error);
                const errorMsg = error.message.includes('HTTP') ? 
                    'Erro de conexão com o gateway de pagamento. Tente novamente.' : 
                    error.message;
                alert('Erro ao gerar PIX: ' + errorMsg);
                document.getElementById('qrLoading').innerHTML = '<div class="text-red-600 text-sm">Erro ao gerar PIX<br><button onclick="createPixPayment()" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Tentar Novamente</button></div>';
            }
        }

        async function checkPaymentStatus() {
            if (!currentTransactionId) return;

            try {
                const response = await fetch(`../api/transactions/get.php?id=${currentTransactionId}`);
                const result = await response.json();
                
                const data = result?.data || result?.cached || {};
                const status = String(data.status || data.currentStatus || 'waiting_payment').toLowerCase();
                
                updateStatusDisplay(status);
                
                if (status.includes('paid')) {
                    clearInterval(statusInterval);
                    showSuccessModal();
                }
            } catch (error) {
                console.error('[v0] Status check error:', error);
            }
        }

        function updateStatusDisplay(status) {
            const statusElement = document.getElementById('paymentStatus');
            
            switch (status.toLowerCase()) {
                case 'paid':
                    statusElement.textContent = 'Pagamento confirmado! ✅';
                    statusElement.className = 'text-green-400 font-semibold text-xs lg:text-sm';
                    break;
                case 'waiting_payment':
                case 'pending':
                    statusElement.textContent = 'Aguardando pagamento...';
                    statusElement.className = 'text-yellow-400 font-semibold text-xs lg:text-sm';
                    break;
                case 'refused':
                case 'refunded':
                    statusElement.textContent = 'Pagamento recusado';
                    statusElement.className = 'text-red-400 font-semibold text-xs lg:text-sm';
                    break;
                default:
                    statusElement.textContent = 'Verificando status...';
                    statusElement.className = 'text-blue-400 font-semibold text-xs lg:text-sm';
            }
        }

        function startStatusPolling() {
            checkPaymentStatus();
            statusInterval = setInterval(checkPaymentStatus, 4000);
        }

        function showSuccessModal() {
            saveClientData();
            document.getElementById('successModal').classList.remove('hidden');
            document.getElementById('successModal').classList.add('flex');
        }

        async function saveClientData() {
            try {
                const clientData = {
                    transactionId: currentTransactionId,
                    customer: {
                        nome: '<?php echo $customerName; ?>',
                        email: '<?php echo $customerEmail; ?>',
                        telefone: '<?php echo $customerPhone; ?>',
                        cpf: '<?php echo $customerCPF; ?>'
                    },
                    product: {
                        nome: '<?php echo $productName; ?>',
                        preco: <?php echo $productPrice; ?>,
                        imagem: '<?php echo $productImage; ?>'
                    },
                    financing: {
                        entrada: <?php echo $pixAmount; ?>,
                        parcelas: <?php echo $parcelas; ?>,
                        valorParcela: <?php echo $valorParcela; ?>,
                        primeiroVencimento: '<?php echo $paymentDate; ?>'
                    },
                    orderData: <?php echo json_encode($orderData); ?>,
                    timestamp: new Date().toISOString()
                };

                const response = await fetch('../api/save-client-data.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(clientData)
                });

                const result = await response.json();
                console.log('[v0] Client data saved:', result);
            } catch (error) {
                console.error('[v0] Error saving client data:', error);
            }
        }

        function copyPixKey() {
            const pixCode = document.getElementById('pixCode');
            const copyBtn = document.getElementById('copyBtn');
            
            pixCode.select();
            document.execCommand('copy');
            
            copyBtn.textContent = 'Copiado!';
            copyBtn.classList.add('bg-green-400');
            copyBtn.classList.remove('bg-cyan-400');
            
            setTimeout(() => {
                copyBtn.textContent = 'Copiar';
                copyBtn.classList.remove('bg-green-400');
                copyBtn.classList.add('bg-cyan-400');
            }, 2000);
        }

        function contactSupport() {
            window.open('https://wa.me/5545991277260?text=Olá, preciso de ajuda com meu pedido', '_blank');
        }

        window.addEventListener('beforeunload', function() {
            if (statusInterval) {
                clearInterval(statusInterval);
            }
        });
    </script>
</body>
</html>
