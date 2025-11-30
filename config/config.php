<?php
// Configurações do Sistema
define('SITE_NAME', 'Da Mamãe Fitness');
define('SITE_URL', 'http://localhost');
define('ADMIN_EMAIL', 'admin@damamaefitness.com.br');
define('CONTACT_EMAIL', 'contato@damamaefitness.com.br');
define('WHATSAPP_NUMBER', '(11) 98496-8625');
define('WHATSAPP_LINK', 'https://api.whatsapp.com/send?phone=5511984968625&text=Olá!%20Gostaria%20de%20mais%20informações');

// Configurações de Sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Diretórios
define('ROOT_PATH', dirname(__DIR__));
define('DATA_DIR', ROOT_PATH . '/data');
define('DATA_PATH', ROOT_PATH . '/data');
define('USERS_FILE', DATA_PATH . '/users.json');
define('ORDERS_FILE', DATA_PATH . '/orders.json');
define('COURSES_FILE', DATA_PATH . '/courses.json');
define('RECIPES_FILE', DATA_PATH . '/recipes.json');

// Criar diretórios se não existirem
if (!file_exists(DATA_PATH)) {
    mkdir(DATA_PATH, 0755, true);
}

// Inicializar arquivos JSON se não existirem
if (!file_exists(USERS_FILE)) {
    file_put_contents(USERS_FILE, json_encode([], JSON_PRETTY_PRINT));
}
if (!file_exists(ORDERS_FILE)) {
    file_put_contents(ORDERS_FILE, json_encode([], JSON_PRETTY_PRINT));
}
if (!file_exists(COURSES_FILE)) {
    file_put_contents(COURSES_FILE, json_encode([], JSON_PRETTY_PRINT));
}
if (!file_exists(RECIPES_FILE)) {
    file_put_contents(RECIPES_FILE, json_encode([], JSON_PRETTY_PRINT));
}

// Produtos disponíveis
$PRODUCTS = [
    1 => [
        'id' => 1,
        'name' => 'Programa Iniciante Fitness',
        'title' => 'Programa Iniciante Fitness',
        'description' => 'Treinos básicos + Nutrição para iniciantes - 7 dias',
        'price' => 49.90,
        'final_price' => 49.90,
        'discount' => 0,
        'image' => 'assets/images/produtos/kit-seca-tudo.jpg',
        'badge' => 'Mais Vendido',
        'category' => 'emagrecimento',
        'courses' => ['course_1'],
        'recipes' => ['recipe_1', 'recipe_2', 'recipe_3']
    ],
    2 => [
        'id' => 2,
        'name' => 'Programa Intermediário Power',
        'title' => 'Programa Intermediário Power',
        'description' => 'Treinos intensos + Dieta balanceada - 14 dias',
        'price' => 69.90,
        'final_price' => 69.90,
        'discount' => 0,
        'image' => 'assets/images/produtos/kit-cetogenico.jpg',
        'badge' => 'Novidade',
        'category' => 'emagrecimento',
        'courses' => ['course_2'],
        'recipes' => ['recipe_4', 'recipe_5', 'recipe_6']
    ],
    3 => [
        'id' => 3,
        'name' => 'Programa Avançado Seca',
        'title' => 'Programa Avançado Seca',
        'description' => 'Treino avançado + Nutrição otimizada - 21 dias',
        'price' => 89.90,
        'old_price' => 99.90,
        'final_price' => 79.90,
        'discount' => 11,
        'image' => 'assets/images/produtos/kit-detox.jpg',
        'badge' => 'Promoção',
        'category' => 'emagrecimento',
        'courses' => ['course_3'],
        'recipes' => ['recipe_7', 'recipe_8']
    ],
    4 => [
        'id' => 4,
        'name' => 'Programa Completo Elite',
        'title' => 'Programa Completo Elite',
        'description' => 'Treino completo + Acompanhamento total - 30 dias',
        'price' => 99.90,
        'final_price' => 99.90,
        'discount' => 0,
        'image' => 'assets/images/produtos/kit-prometi-secar.jpg',
        'badge' => 'Premium',
        'category' => 'emagrecimento',
        'courses' => ['course_1', 'course_2'],
        'recipes' => ['recipe_1', 'recipe_2', 'recipe_3', 'recipe_4', 'recipe_5']
    ]
];
