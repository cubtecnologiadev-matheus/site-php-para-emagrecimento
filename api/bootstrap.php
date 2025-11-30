<?php
// Desliga HTML nos erros (pra não quebrar JSON)
ini_set('display_errors', '0');
ini_set('html_errors', '0');
error_reporting(E_ALL);

// ========== CONFIG ==========
define('API_ENDPOINT_CREATE', 'https://api.anubispay.com.br/v1/transactions'); // endpoint completo
define('API_ENDPOINT_STATUS', 'https://api.anubispay.com.br/v1/transactions'); // base do GET {id}

define('PUBLIC_KEY', getenv('ANUBIS_PUBLIC_KEY') ?: 'chave aqui publica');
define('SECRET_KEY', getenv('ANUBIS_SECRET_KEY') ?: 'chave privada');

// Outras configs
define('AUTH_MODE', 'basic');     // basic ou bearer (normal: basic)
define('DEFAULT_AMOUNT', '19.90');
define('POSTBACK_URL', getenv('POSTBACK_URL') ?: '');       // ex.: 'https://seu-dominio.com/api/webhook' ou deixe ''

// ====== HELPERS / CORE ======
define('STORAGE_DIR', dirname(__DIR__).'/data/tmp');
@is_dir(STORAGE_DIR) || @mkdir(STORAGE_DIR, 0775, true);

function logx($file,$msg){ @file_put_contents($file,"[".date('c')."] $msg\n",FILE_APPEND); }
function log_create($m){ logx(STORAGE_DIR.'/create_log.txt',$m); }
function log_status($m){ logx(STORAGE_DIR.'/status_log.txt',$m); }

function only_digits($v){ return preg_replace('/\D+/', '', strval($v ?? '')); }
function to_cents($v){ if(is_int($v)) return $v; $n = floatval(str_replace(',','.',strval($v??'0'))); return (int)round($n*100); }
function client_ip(){
  foreach(['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','REMOTE_ADDR'] as $k){
    if(!empty($_SERVER[$k])){ $ip = $_SERVER[$k]; if($k==='HTTP_X_FORWARDED_FOR' && strpos($ip,',')!==false){$ip=trim(explode(',',$ip)[0]);} return $ip; }
  } return null;
}
function json_input(){ $raw=file_get_contents('php://input'); $j=json_decode($raw,true); return is_array($j)?$j:[]; }
function http_json($code,$arr){ http_response_code($code); header('Content-Type: application/json; charset=utf-8'); echo json_encode($arr,JSON_UNESCAPED_UNICODE); exit; }

function auth_header(){
  if(AUTH_MODE==='bearer'){ if(!SECRET_KEY) throw new Exception('SECRET_KEY ausente'); return 'Authorization: Bearer '.SECRET_KEY; }
  if(!PUBLIC_KEY || !SECRET_KEY) throw new Exception('PUBLIC_KEY/SECRET_KEY ausentes');
  return 'Authorization: Basic '.base64_encode(PUBLIC_KEY.':'.SECRET_KEY);
}
function curl_json($method,$url,$payload=null,$timeout=25){
  $h=['Accept: application/json','Content-Type: application/json',auth_header(),'User-Agent: PixBridge-PHP/1.3','Expect:'];
  $ch=curl_init(); curl_setopt_array($ch,[CURLOPT_URL=>$url,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HEADER=>true,
    CURLOPT_CUSTOMREQUEST=>strtoupper($method),CURLOPT_HTTPHEADER=>$h,CURLOPT_TIMEOUT=>$timeout,
    CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_SSL_VERIFYHOST=>2]);
  if($payload!==null) curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($payload,JSON_UNESCAPED_UNICODE));
  $resp=curl_exec($ch); $errno=curl_errno($ch); $err=curl_error($ch); $info=curl_getinfo($ch); curl_close($ch);
  $code=(int)($info['http_code']??0); $hsize=(int)($info['header_size']??0); $body=substr((string)$resp,$hsize);
  $json=json_decode($body,true); return [$code,$json,$body,$errno,$err];
}

function save_snapshot($id,$data){ @file_put_contents(STORAGE_DIR."/$id.json",json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); }
function load_snapshot($id){ $f=STORAGE_DIR."/$id.json"; return is_file($f)?json_decode(file_get_contents($f),true):null; }

// Para rotas REST via .htaccess
function route_id_from_request($prefix){
  $uri=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH); $pos=strpos($uri,$prefix); if($pos===false) return null;
  $cut=trim(substr($uri,$pos+strlen($prefix)),'/'); $parts=explode('/',$cut);
  return preg_replace('/[^a-zA-Z0-9_\-]/','',($parts[0]??''));
}

function cleanup_old_files($days = 7) {
    $cutoff = time() - ($days * 24 * 60 * 60);
    $files = glob(STORAGE_DIR . '/*.json');
    foreach ($files as $file) {
        if (filemtime($file) < $cutoff) {
            @unlink($file);
        }
    }
}

if (rand(1, 100) <= 5) { // 5% de chance a cada requisição
    cleanup_old_files();
}
