<?php
// Funções auxiliares gerais

function cleanCPF($cpf) {
    return preg_replace('/[^0-9]/', '', $cpf);
}

function cleanPhone($phone) {
    return preg_replace('/[^0-9]/', '', $phone);
}

function formatCPF($cpf) {
    $cpf = cleanCPF($cpf);
    if (strlen($cpf) === 11) {
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }
    return $cpf;
}

function formatPhone($phone) {
    $phone = cleanPhone($phone);
    if (strlen($phone) === 11) {
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7, 4);
    } elseif (strlen($phone) === 10) {
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6, 4);
    }
    return $phone;
}

function formatMoney($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}

function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function generateRandomName() {
    $names = [
        'Ana Silva', 'Maria Santos', 'João Oliveira', 'Pedro Costa', 'Juliana Lima',
        'Carlos Souza', 'Fernanda Alves', 'Lucas Pereira', 'Mariana Rodrigues', 'Rafael Ferreira',
        'Camila Martins', 'Bruno Carvalho', 'Beatriz Ribeiro', 'Gabriel Almeida', 'Larissa Gomes',
        'Amanda Oliveira', 'Rodrigo Silva', 'Patricia Santos', 'Felipe Costa', 'Isabela Lima',
        'Thiago Souza', 'Leticia Alves', 'Gustavo Pereira', 'Natalia Rodrigues', 'Diego Ferreira',
        'Vanessa Martins', 'Marcelo Carvalho', 'Renata Ribeiro', 'Leonardo Almeida', 'Carolina Gomes'
    ];
    return $names[array_rand($names)];
}

function generateRandomCity() {
    $cities = [
        'São Paulo, SP', 'Rio de Janeiro, RJ', 'Belo Horizonte, MG', 'Curitiba, PR',
        'Porto Alegre, RS', 'Salvador, BA', 'Brasília, DF', 'Fortaleza, CE',
        'Recife, PE', 'Manaus, AM', 'Goiânia, GO', 'Belém, PA', 'Campinas, SP',
        'Guarulhos, SP', 'São Luís, MA', 'Maceió, AL', 'Natal, RN', 'Campo Grande, MS'
    ];
    return $cities[array_rand($cities)];
}

function generateRandomProduct() {
    $products = [
        'Kit Seca Tudo 7 Dias',
        'Kit Dieta Cetogênica',
        'Detox Líquido',
        'Kit Prometi SECAR'
    ];
    return $products[array_rand($products)];
}

function generateRandomTime() {
    $minutes = rand(1, 59);
    if ($minutes === 1) {
        return 'há 1 minuto';
    }
    return "há $minutes minutos";
}
