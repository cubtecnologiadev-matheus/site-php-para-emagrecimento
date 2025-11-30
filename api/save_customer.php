<?php
header('Content-Type: application/json; charset=utf-8');

$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: [];

if (!$data || empty($data['nome'])) {
  echo json_encode(['ok'=>false,'error'=>'Dados inválidos']); exit;
}

$base = __DIR__ . '/../dados_clientes';
if (!is_dir($base)) mkdir($base, 0775, true);

$slug = preg_replace('~[^a-z0-9]+~i','-', $data['nome']);
$folder = $base . '/' . $slug . '-' . date('Ymd-His');
if (!mkdir($folder, 0775, true)) {
  echo json_encode(['ok'=>false,'error'=>'Falha ao criar pasta']); exit;
}

file_put_contents($folder.'/cliente.json', json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

echo json_encode(['ok'=>true,'folder_id'=>basename($folder)]);
