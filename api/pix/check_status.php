<?php
require __DIR__ . '/../bootstrap.php';

// /api/pix/check_status.php?id={id} or /api/pix/{id}/status
$id = preg_replace('/[^a-zA-Z0-9_\-]/','', $_GET['id'] ?? $_GET['txid'] ?? route_id_from_request('/api/pix/'));
if(!$id) http_json(400, ['error'=>'id ausente']);

try{
  $url = rtrim(API_ENDPOINT_STATUS,'/').'/'.urlencode($id);
  log_status("TRY GET $url");
  [$code,$json,$body,$errno,$err] = curl_json('GET',$url,null,20);
  log_status("HTTP $code BODY ".mb_substr($body ?? '',0,200));

  if($code>=200 && $code<300 && is_array($json)){
    $data = $json['data'] ?? $json;
    if(!empty($data['id'])) save_snapshot($id,$data);
    http_json($code, ['data'=>$data,'cached'=>load_snapshot($id)]);
  }

  $cached = load_snapshot($id);
  if($cached) http_json(200, ['data'=>null,'cached'=>$cached,'error'=>'live_fetch_failed']);

  http_json($code ?: 500, ['error'=>'transação não encontrada','id'=>$id]);

}catch(Exception $e){
  $cached = load_snapshot($id);
  if($cached) http_json(200, ['data'=>null,'cached'=>$cached,'error'=>$e->getMessage()]);
  http_json(500, ['error'=>$e->getMessage()]);
}
