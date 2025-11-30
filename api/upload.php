<?php
header('Content-Type: application/json; charset=utf-8');
$folder_id = $_POST['folder_id'] ?? '';
$base = __DIR__ . '/../dados_clientes';
$folder = realpath($base . '/' . $folder_id);

if (!$folder || strpos($folder, realpath($base)) !== 0) {
  echo json_encode(['ok'=>false,'error'=>'Folder inválido']); exit;
}

$ok = true; $errors = [];
foreach ($_FILES as $field => $file) {
  if ($file['error'] === UPLOAD_ERR_NO_FILE) continue;
  if ($file['error'] !== UPLOAD_ERR_OK) { $ok=false; $errors[]="$field erro"; continue; }

  $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  if (!in_array($ext,['jpg','jpeg','png','pdf'])) { $ok=false; $errors[]="$field extensão inválida"; continue; }

  $dest = $folder . '/' . preg_replace('~[^a-z0-9._-]+~i','_', $field.'_'.$file['name']);
  if (!move_uploaded_file($file['tmp_name'], $dest)) { $ok=false; $errors[]="$field move falhou"; }
}

echo json_encode($ok ? ['ok'=>true] : ['ok'=>false,'error'=>implode(', ',$errors)]);
