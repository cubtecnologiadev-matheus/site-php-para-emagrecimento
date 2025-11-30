<?php
require __DIR__ . '/../bootstrap.php';

// Suporta /api/pix/{id}/qrcode.png e ?id=...
$id = preg_replace('/[^a-zA-Z0-9_\-]/','', $_GET['id'] ?? route_id_from_request('/api/pix/'));
if(!$id){ http_response_code(400); echo 'id ausente'; exit; }

$snap = load_snapshot($id);
$code = $snap['pix']['qrcode'] ?? null;

if(!$code){
  $url = rtrim(API_ENDPOINT_STATUS,'/').'/'.urlencode($id);
  [, $json] = curl_json('GET',$url,null,15);
  $data = is_array($json) ? ($json['data'] ?? $json) : null;
  $code = $data['pix']['qrcode'] ?? null;
  if($data && !empty($data['id'])) save_snapshot($id,$data);
}
if(!$code){ http_response_code(404); echo 'qrcode not available'; exit; }

// Gera imagem via serviço (evita lib extra)
$pngUrl = 'https://quickchart.io/qr?size=520&text='.urlencode($code);
$ch = curl_init($pngUrl); curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_FOLLOWLOCATION=>true,CURLOPT_TIMEOUT=>15]);
$png = curl_exec($ch); curl_close($ch);
header('Content-Type: image/png'); echo $png;
