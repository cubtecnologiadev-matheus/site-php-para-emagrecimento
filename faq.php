<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

$faqs = [
    [
        'category' => 'Produtos',
        'questions' => [
            [
                'q' => 'Como funcionam os kits de emagrecimento?',
                'a' => 'Nossos kits contêm receitas completas e balanceadas para almoço e jantar durante 7 dias. Cada receita é desenvolvida por nutricionistas e pensada para promover o emagrecimento saudável.'
            ],
            [
                'q' => 'Os kits são físicos ou digitais?',
                'a' => 'Os kits são 100% digitais. Após a compra, você recebe acesso imediato a todas as receitas em PDF, que podem ser baixadas e impressas.'
            ],
            [
                'q' => 'Posso repetir as receitas após os 7 dias?',
                'a' => 'Sim! As receitas são suas para sempre. Você pode repetir o ciclo quantas vezes quiser.'
            ]
        ]
    ],
    [
        'category' => 'Pagamento',
        'questions' => [
            [
                'q' => 'Quais formas de pagamento são aceitas?',
                'a' => 'Aceitamos pagamento via PIX. O pagamento é processado instantaneamente e você recebe acesso imediato ao conteúdo.'
            ],
            [
                'q' => 'É seguro comprar no site?',
                'a' => 'Sim! Utilizamos tecnologia de criptografia SSL e processamento seguro de pagamentos. Seus dados estão protegidos.'
            ],
            [
                'q' => 'Quanto tempo leva para receber o acesso?',
                'a' => 'Com pagamento via PIX, o acesso é liberado automaticamente em até 5 minutos após a confirmação do pagamento.'
            ]
        ]
    ],
    [
        'category' => 'Resultados',
        'questions' => [
            [
                'q' => 'Em quanto tempo verei resultados?',
                'a' => 'Os resultados variam de pessoa para pessoa, mas a maioria dos nossos clientes relata perda de peso já na primeira semana. A média é de 2-4kg por semana.'
            ],
            [
                'q' => 'Preciso fazer exercícios físicos?',
                'a' => 'Embora não seja obrigatório, recomendamos a prática de atividades físicas para potencializar os resultados e melhorar a saúde geral.'
            ],
            [
                'q' => 'As receitas são difíceis de fazer?',
                'a' => 'Não! Todas as receitas são práticas e fáceis de preparar, com ingredientes acessíveis encontrados em qualquer supermercado.'
            ]
        ]
    ],
    [
        'category' => 'Suporte',
        'questions' => [
            [
                'q' => 'Tenho suporte após a compra?',
                'a' => 'Sim! Nossa equipe está disponível via WhatsApp e e-mail para tirar todas as suas dúvidas.'
            ],
            [
                'q' => 'Posso trocar ou cancelar minha compra?',
                'a' => 'Sim, oferecemos garantia de 7 dias. Se não ficar satisfeito, devolvemos 100% do seu dinheiro.'
            ],
            [
                'q' => 'Como acesso o conteúdo comprado?',
                'a' => 'Após o pagamento, você receberá um e-mail com suas credenciais de acesso. Você também pode acessar através da área "Minha Conta" no site.'
            ]
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perguntas Frequentes - <?php echo SITE_NAME; ?></title>
    <meta name="description" content="Tire suas dúvidas sobre nossos produtos e serviços">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php"><h1>Da Mamãe <span>Fitness</span></h1></a>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php">Início</a></li>
                        <li><a href="produtos.php">Produtos</a></li>
                        <li><a href="sobre.php">Sobre Nós</a></li>
                        <li><a href="depoimentos.php">Depoimentos</a></li>
                        <li><a href="contato.php">Contato</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="dashboard.php">Minha Conta</a></li>
                            <li><a href="logout.php">Sair</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Entrar</a></li>
                            <li><a href="register.php">Cadastrar</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Perguntas Frequentes</h1>
            <p>Encontre respostas para as dúvidas mais comuns</p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <?php foreach ($faqs as $category): ?>
            <div class="faq-category">
                <h2 class="category-title">
                    <i class="fas fa-folder"></i> <?php echo htmlspecialchars($category['category']); ?>
                </h2>
                <div class="faq-list">
                    <?php foreach ($category['questions'] as $index => $faq): ?>
                    <div class="faq-item">
                        <div class="faq-question">
                            <i class="fas fa-question-circle"></i>
                            <h3><?php echo htmlspecialchars($faq['q']); ?></h3>
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p><?php echo htmlspecialchars($faq['a']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="cta-section">
        <div class="container">
            <h2>Não Encontrou Sua Resposta?</h2>
            <p>Entre em contato conosco e teremos prazer em ajudar</p>
            <a href="contato.php" class="btn btn-primary">Falar com Suporte</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- WhatsApp Button -->
    <a href="https://api.whatsapp.com/send?phone=<?php echo WHATSAPP_NUMBER; ?>&text=Olá!%20Gostaria%20de%20mais%20informações" 
       class="whatsapp-float" target="_blank" rel="noopener">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="assets/js/main.js"></script>
    <script>
        // FAQ Accordion
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const item = question.parentElement;
                const isActive = item.classList.contains('active');
                
                // Close all items
                document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
                
                // Open clicked item if it wasn't active
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
