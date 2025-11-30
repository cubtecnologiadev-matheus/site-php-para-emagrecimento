<?php
// painel-api.php
// API para listar acessos a partir de .geo-cache e .google-cache
// - Junta as infos por IP
// - Enriquecimento opcional (cidade/lat/lon/org) via ip-api.com se ?enrich=1
// - Filtro incremental por timestamp (?since=epoch_segundos)
// - Limite de retorno (?limit=1000, padrão 500)
// - (Opcional) token simples (?key=...) se quiser trancar o acesso público

date_default_timezone_set('America/Sao_Paulo');

@ini_set('display_errors','0'); @ini_set('log_errors','1');
header('Content-Type: application/json; charset=UTF-8');

$GEO_DIR    = __DIR__ . '/.geo-cache';
$GOOG_DIR   = __DIR__ . '/.google-cache';
$ACCESS_KEY = ''; // se quiser proteger: coloque algo como 'minha_senha' e acesse com ?key=minha_senha

// ---------- Auth simples (opcional) ----------
if ($ACCESS_KEY !== '') {
    if (!isset($_GET['key']) || $_GET['key'] !== $ACCESS_KEY) {
        http_response_code(403);
        echo json_encode(['error'=>'forbidden']);
        exit;
    }
}

// ---------- Helpers ----------
function safe_int($v, $default) {
    if (!isset($v)) return $default;
    if (!is_numeric($v)) return $default;
    return (int)$v;
}
function list_json_files($dir) {
    $out = [];
    if (!is_dir($dir)) return $out;
    $it = new DirectoryIterator($dir);
    foreach ($it as $f) {
        if ($f->isDot() || !$f->isFile()) continue;
        if (strtolower($f->getExtension()) !== 'json') continue;
        $out[] = $f->getPathname();
    }
    return $out;
}
function filename_to_ip($path) {
    $base = basename($path);
    // remove a última extensão .json
    if (substr($base, -5) === '.json') $base = substr($base, 0, -5);
    // os nomes já vêm quase "crus" (.: e : permanecem). Voltamos como está.
    return $base;
}
function read_json_silent($path) {
    $s = @file_get_contents($path);
    if ($s === false) return null;
    $j = @json_decode($s, true);
    if (!is_array($j)) return null;
    return $j;
}
function write_json_silent($path, $data) {
    @file_put_contents($path, json_encode($data, JSON_UNESCAPED_SLASHES), LOCK_EX);
}

function detect_bot_by_ip($ip) {
    // Googlebot IP ranges conhecidos
    $google_ranges = [
        '66.249.', '64.233.', '72.14.', '74.125.', '216.239.', 
        '66.102.', '34.', '35.', '108.177.', '172.217.',
        '142.250.', '142.251.', '8.8.', '2001:4860:'
    ];
    
    // Facebook crawler IP ranges conhecidos  
    $facebook_ranges = [
        '31.13.', '66.220.', '69.63.', '157.240.', '173.252.',
        '185.60.', '204.15.', '129.134.', '147.75.', '163.70.',
        '179.60.', '185.24.', '199.201.', '203.84.', '212.82.',
        '217.73.', '31.222.', '45.64.', '69.171.', '74.119.',
        '87.244.', '89.32.', '92.243.', '94.76.', '95.211.',
        '103.4.', '109.63.', '122.152.', '123.50.', '129.134.',
        '173.252.', '179.60.', '185.60.', '199.201.', '204.15.',
        '212.82.', '217.73.', '31.222.', '45.64.', '69.171.',
        '2a03:2880:', '2a03:2880:f', '2620:0:1cff:'
    ];
    
    foreach ($google_ranges as $range) {
        if (strpos($ip, $range) === 0) {
            return 'google';
        }
    }
    
    foreach ($facebook_ranges as $range) {
        if (strpos($ip, $range) === 0) {
            return 'facebook';
        }
    }
    
    return null;
}

function geo_enrich($ip, $geoPath) {
    // CORREÇÃO: Usa ip-api.com (mais confiável e gratuita)
    $url = 'http://ip-api.com/json/' . rawurlencode($ip) . '?fields=status,message,country,countryCode,region,regionName,city,lat,lon,isp,org,as,query';
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 2,
        CURLOPT_TIMEOUT        => 3,
        CURLOPT_USERAGENT      => 'access-panel/1.0'
    ]);
    $resp = curl_exec($ch);
    curl_close($ch);

    $out = [];
    if (is_string($resp)) {
        $j = @json_decode($resp, true);
        if (is_array($j) && isset($j['status']) && $j['status'] === 'success') {
            // Mapeia campos do ip-api.com para nosso formato
            $out['country']       = $j['countryCode'] ?? null;
            $out['country_name']  = $j['country'] ?? null;
            $out['region']        = $j['regionName'] ?? null;
            $out['city']          = $j['city'] ?? null;
            $out['latitude']      = $j['lat'] ?? null;
            $out['longitude']     = $j['lon'] ?? null;
            $out['org']           = $j['isp'] ?? ($j['org'] ?? null);
            
            // Detecta bots pela organização
            $org = strtolower($out['org'] ?? '');
            if (strpos($org, 'google') !== false || strpos($org, 'gstatic') !== false) {
                $out['is_google'] = true;
            }
            if (strpos($org, 'facebook') !== false || strpos($org, 'meta') !== false) {
                $out['is_facebook'] = true;
            }
            
            // Log para debug
            error_log("ip-api.com success for IP: {$ip} - Country: " . ($out['country'] ?? 'null') . " - ISP: " . ($out['org'] ?? 'null'));
        } else {
            // Fallback: detecção manual por faixa de IP
            $out = manual_geo_detection($ip);
        }
    } else {
        // Fallback: detecção manual por faixa de IP
        $out = manual_geo_detection($ip);
    }

    // Detecta bots pelo IP se não detectou pela organização
    if (!isset($out['is_google']) && !isset($out['is_facebook'])) {
        $bot_type = detect_bot_by_ip($ip);
        if ($bot_type === 'google') {
            $out['is_google'] = true;
        } elseif ($bot_type === 'facebook') {
            $out['is_facebook'] = true;
        }
    }

    // Se não veio nada, mantém pelo menos o que já havia
    $existing = read_json_silent($geoPath) ?: [];
    $merged = array_merge($existing, array_filter($out, fn($v)=>$v!==null));

    // persiste enriquecimento
    @mkdir(dirname($geoPath), 0755, true);
    write_json_silent($geoPath, $merged);

    return $merged;
}

function manual_geo_detection($ip) {
    $out = [];
    
    // Detecção manual por faixas de IP brasileiras
    $ip_parts = explode('.', $ip);
    $first_octet = $ip_parts[0] ?? '';
    
    // Faixas de IP brasileiros conhecidas
    $br_ranges = ['177', '179', '187', '189', '191', '186', '200', '152', '138', '168', '170', '201'];
    
    // IPv6 brasileiros
    if (strpos($ip, '2804:') === 0 || strpos($ip, '2001:12f8:') === 0 || 
        strpos($ip, '2804:') === 0 || strpos($ip, '2a03:') === 0) {
        $out['country'] = 'BR';
        $out['country_name'] = 'Brazil';
        $out['region'] = 'Unknown';
        $out['city'] = 'Unknown';
    }
    // IPv4 brasileiros
    elseif (in_array($first_octet, $br_ranges)) {
        $out['country'] = 'BR';
        $out['country_name'] = 'Brazil';
        $out['region'] = 'Unknown';
        $out['city'] = 'Unknown';
    }
    // IPs dos EUA
    elseif (strpos($ip, '66.') === 0 || strpos($ip, '64.') === 0 || 
            strpos($ip, '72.') === 0 || strpos($ip, '216.') === 0 ||
            strpos($ip, '2001:') === 0) {
        $out['country'] = 'US';
        $out['country_name'] = 'United States';
        $out['region'] = 'Unknown';
        $out['city'] = 'Unknown';
    }
    // Outros países
    else {
        $out['country'] = 'UN';
        $out['country_name'] = 'Unknown';
        $out['region'] = 'Unknown';
        $out['city'] = 'Unknown';
    }
    
    error_log("Manual detection for IP: {$ip} - Country: " . ($out['country'] ?? 'null'));
    return $out;
}

// ---------- Montagem do índice por IP ----------
$items = []; // ip => data
$now   = time();

// PRIMEIRO: Processa Google Cache
foreach (list_json_files($GOOG_DIR) as $p) {
    $ip = filename_to_ip($p);
    $st = @stat($p);
    $mt = $st ? (int)$st['mtime'] : 0;
    $j  = read_json_silent($p) ?: [];
    $isGoogle = isset($j['is_google']) ? (bool)$j['is_google'] : false;
    
    // CORREÇÃO: Se já existe o IP, atualiza apenas se o timestamp for mais recente
    if (isset($items[$ip])) {
        if ($mt > $items[$ip]['mtime']) {
            $items[$ip]['mtime'] = $mt;
            $items[$ip]['is_google'] = $isGoogle;
        }
    } else {
        $items[$ip] = [
            'ip' => $ip, 
            'mtime' => $mt,
            'is_google' => $isGoogle,
            'is_facebook' => false
        ];
    }
}

// SEGUNDO: Processa Geo Cache (sobrescreve/atualiza dados geográficos)
foreach (list_json_files($GEO_DIR) as $p) {
    $ip = filename_to_ip($p);
    $st = @stat($p);
    $mt = $st ? (int)$st['mtime'] : 0;
    $j  = read_json_silent($p) ?: [];
    
    if (isset($items[$ip])) {
        // Atualiza dados geográficos e mantém o maior timestamp
        $items[$ip]['country']      = $j['country']      ?? ($j['country_code'] ?? $items[$ip]['country'] ?? null);
        $items[$ip]['country_name'] = $j['country_name'] ?? ($j['country']      ?? $items[$ip]['country_name'] ?? null);
        $items[$ip]['region']       = $j['region']       ?? $items[$ip]['region'] ?? null;
        $items[$ip]['city']         = $j['city']         ?? $items[$ip]['city'] ?? null;
        $items[$ip]['latitude']     = $j['latitude']     ?? $items[$ip]['latitude'] ?? null;
        $items[$ip]['longitude']    = $j['longitude']    ?? $items[$ip]['longitude'] ?? null;
        $items[$ip]['org']          = $j['org']          ?? $items[$ip]['org'] ?? null;
        
        // Atualiza flags de bots se presentes
        if (isset($j['is_google'])) {
            $items[$ip]['is_google'] = (bool)$j['is_google'];
        }
        if (isset($j['is_facebook'])) {
            $items[$ip]['is_facebook'] = (bool)$j['is_facebook'];
        }
        
        // Atualiza mtime se necessário
        if ($mt > $items[$ip]['mtime']) {
            $items[$ip]['mtime'] = $mt;
        }
    } else {
        // Cria nova entrada apenas se não existir
        $items[$ip] = [
            'ip' => $ip,
            'mtime' => $mt,
            'country' => $j['country'] ?? ($j['country_code'] ?? null),
            'country_name' => $j['country_name'] ?? ($j['country'] ?? null),
            'region' => $j['region'] ?? null,
            'city' => $j['city'] ?? null,
            'latitude' => $j['latitude'] ?? null,
            'longitude' => $j['longitude'] ?? null,
            'org' => $j['org'] ?? null,
            'is_google' => false,
            'is_facebook' => false
        ];
    }
}

// CORREÇÃO: Remove entradas duplicadas e inválidas
$items = array_filter($items, function($item) {
    return !empty($item['ip']) && filter_var($item['ip'], FILTER_VALIDATE_IP);
});

// Enriquecimento opcional (leve; só preenche o que falta)
$enrich = isset($_GET['enrich']) && $_GET['enrich'] == '1';
if ($enrich) {
    foreach ($items as $ip => &$row) {
        $needs = empty($row['country']) || empty($row['country_name']) || empty($row['org']);
        if ($needs) {
            $geoPath = $GEO_DIR . '/' . $ip . '.json';
            $enrichedData = geo_enrich($ip, $geoPath);
            // Mescla apenas os campos que estão faltando
            foreach ($enrichedData as $key => $value) {
                if (empty($row[$key]) && !empty($value)) {
                    $row[$key] = $value;
                }
            }
            // atualiza mtime após escrever
            $st = @stat($geoPath);
            if ($st) {
                $row['mtime'] = max($row['mtime'], (int)$st['mtime']);
            }
        }
    }
    unset($row);
}

// CORREÇÃO: Converte para array numérico e ordena por mtime desc
$items = array_values($items);
usort($items, fn($a,$b)=>($b['mtime'] <=> $a['mtime']));

// Filtro incremental
$since = safe_int($_GET['since'] ?? null, 0);
if ($since > 0) {
    $items = array_values(array_filter($items, fn($r)=>($r['mtime'] > $since)));
}

// Limite
$limit = max(1, min(5000, safe_int($_GET['limit'] ?? null, 500)));
if (count($items) > $limit) $items = array_slice($items, 0, $limit);

// Stats
$stats = ['total'=>0, 'br'=>0, 'non_br'=>0, 'google'=>0, 'facebook'=>0];
foreach ($items as $r) {
    $stats['total']++;
    if (!empty($r['is_google'])) $stats['google']++;
    if (!empty($r['is_facebook'])) $stats['facebook']++;
    $cc = strtoupper((string)($r['country'] ?? ''));
    if ($cc === 'BR') $stats['br']++; else $stats['non_br']++;
}

// Saída
echo json_encode([
    'now'     => $now,
    'stats'   => $stats,
    'entries' => array_map(function($r){
        $r['is_br']    = (strtoupper((string)($r['country'] ?? '')) === 'BR');
        $r['mtime_iso']= date('c', $r['mtime']);
        return $r;
    }, $items)
], JSON_UNESCAPED_SLASHES);
?>
