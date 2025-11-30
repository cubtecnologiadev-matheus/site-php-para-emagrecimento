<?php
require __DIR__ . '/../bootstrap.php';

try{
  $in = json_input();
  $amountStr   = $in['amount'] ?? DEFAULT_AMOUNT;
  $amountCents = to_cents($amountStr);
  if($amountCents <= 0) throw new Exception('Valor inválido');

  $quantity = max(1, (int)($in['quantity'] ?? 1));
  $orderId  = $in['externalRef'] ?? ('ORDER-PIX-'.date('Ymd-His').'-'.bin2hex(random_bytes(2)));
  $ip       = client_ip();

  $cIn = $in['customer'] ?? [];
  $customer = [
    'name'      => $cIn['name']      ?? 'Cliente',
    'email'     => $cIn['email']     ?? '',
    'phone'     => only_digits($cIn['phone'] ?? ''),
    'birthdate' => $cIn['birthdate'] ?? '',
    'document'  => [
      'type'   => strtolower($cIn['document']['type'] ?? 'cpf'),
      'number' => only_digits($cIn['document']['number'] ?? ($cIn['document'] ?? ''))
    ],
    'address'   => $cIn['address'] ?? [
      'street'=>'Rua Exemplo','streetNumber'=>'1','complement'=>'',
      'neighborhood'=>'Centro','zipCode'=>'01001000','city'=>'São Paulo','state'=>'SP','country'=>'BR'
    ],
  ];

  $payload = [
    'amount'        => $amountCents,
    'currency'      => 'BRL',
    'paymentMethod' => 'pix',
    'installments'  => 1,
    'items'         => [[
      'title'=>'Pedido PIX','quantity'=>1,'tangible'=>true,'unitPrice'=>$amountCents,'externalRef'=>$orderId
    ]],
    'customer'      => $customer,
    'pix'           => ['expiresInDays'=>1],
    'postbackUrl'   => POSTBACK_URL ?: null,
    'metadata'      => json_encode(['origem'=>'site','qtd'=>$quantity,'orderId'=>$orderId], JSON_UNESCAPED_UNICODE),
    'externalRef'   => $orderId,
    'ip'            => $ip,
  ];
  if(!$payload['postbackUrl']) unset($payload['postbackUrl']);

  $url = API_ENDPOINT_CREATE;
  log_create("REQ POST $url");
  log_create("PAYLOAD ".json_encode($payload,JSON_UNESCAPED_UNICODE));

  [$code,$json,$body,$errno,$err] = curl_json('POST',$url,$payload);
  log_create("HTTP $code BODY ".mb_substr($body ?? '',0,400));

  if($errno) throw new Exception("cURL #$errno: $err");
  if($code < 200 || $code >= 300){
    $msg = is_array($json) ? ($json['message'] ?? $json['error'] ?? "HTTP $code") : "HTTP $code";
    throw new Exception($msg);
  }

  $data = $json['data'] ?? $json;
  $id   = strval($data['id'] ?? $data['transactionId'] ?? $data['objectId'] ?? '');
  if(!$id) throw new Exception('Resposta sem id');

  save_snapshot($id, [
    'id'=>$id,'status'=>$data['status'] ?? 'waiting_payment',
    'pix'=>['qrcode'=>$data['pix']['qrcode'] ?? null],
    'amount_cents'=>$amountCents,'quantity'=>$quantity,
    'raw'=>$json,'created_at'=>date('c'),'endpoint_used'=>$url
  ]);

  http_json(200, ['ok'=>true,'data'=>$data]);

}catch(Exception $e){
  log_create("ERROR: ".$e->getMessage());
  http_json(400, ['ok'=>false,'error'=>['message'=>$e->getMessage()]]);
}
