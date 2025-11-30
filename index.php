<?php
// ===================== Geo / Google logic =====================
// Regras (AGORA):
// - Não redireciona mais ninguém.
// - Só detecta: IP, se é Googlebot e se é Brasil (para debug / análise).
//
// Caso você reutilize este mesmo arquivo em index.html, não há mais
// qualquer redirecionamento automático.
@ini_set('display_errors','0'); @ini_set('log_errors','1');
header('Vary: CF-IPCOUNTRY, User-Agent');

function get_client_ip(): string {
    $keys = ['HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','HTTP_CLIENT_IP','REMOTE_ADDR'];
    foreach ($keys as $k) {
        if (!empty($_SERVER[$k])) {
            $ip = $_SERVER[$k];
            if ($k === 'HTTP_X_FORWARDED_FOR') $ip = trim(explode(',', $ip)[0]);
            return trim($ip);
        }
    }
    return '0.0.0.0';
}

/* ======= GOOGLE BOT ======= */
function ua_looks_like_google(): bool {
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $bots = ['Googlebot','AdsBot-Google','Mediapartners-Google','APIs-Google','FeedFetcher-Google','Google-Read-Aloud','DuplexWeb-Google'];
    foreach ($bots as $b) if (stripos($ua,$b)!==false) return true;
    return false;
}
function host_seems_google(string $host): bool {
    $host = strtolower($host);
    return (str_ends_with($host,'.googlebot.com') || str_ends_with($host,'.google.com'));
}
function reverse_dns_validates_google(string $ip): bool {
    $host = @gethostbyaddr($ip);
    if (!$host || $host===$ip) return false;
    if (!host_seems_google($host)) return false;
    $recs = @dns_get_record($host, DNS_A + DNS_AAAA);
    if (!$recs) return false;
    foreach ($recs as $r) {
        if (!empty($r['ip'])   && $r['ip']===$ip)   return true;
        if (!empty($r['ipv6']) && $r['ipv6']===$ip) return true;
    }
    return false;
}
function ip_in_cidrs(string $ip, array $cidrs): bool {
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) return false;
    $ipLong = ip2long($ip); if ($ipLong===false) return false;
    foreach ($cidrs as $cidr) {
        [$net,$bits] = explode('/',$cidr)+[null,32];
        $mask = -1 << (32-(int)$bits);
        $netL = ip2long($net); if ($netL===false) continue;
        if (($ipLong & $mask) === ($netL & $mask)) return true;
    }
    return false;
}
function is_googlebot(string $ip): bool {
    $cacheDir = __DIR__.'/.google-cache'; if(!is_dir($cacheDir)) @mkdir($cacheDir,0755,true);
    $key = preg_replace('/[^0-9a-f:\.]/i','_',$ip);
    $file = $cacheDir.'/'.$key.'.json'; $ttl=300;
    if (is_file($file) && (time()-filemtime($file)<$ttl)) {
        $j = @json_decode(@file_get_contents($file),true);
        if (isset($j['is_google'])) return (bool)$j['is_google'];
    }
    if (reverse_dns_validates_google($ip)) { @file_put_contents($file,'{"is_google":true}'); return true; }
    $ranges = ['66.249.0.0/16','64.233.160.0/19','72.14.192.0/18','74.125.0.0/16','209.85.128.0/17','66.102.0.0/20','216.239.32.0/19','108.177.8.0/21','173.194.0.0/16','207.126.144.0/20','142.250.0.0/15','35.191.0.0/16','130.211.0.0/16'];
    if (ip_in_cidrs($ip,$ranges)) { @file_put_contents($file,'{"is_google":true}'); return true; }
    if (ua_looks_like_google()) { @file_put_contents($file,'{"is_google":true}'); return true; }
    @file_put_contents($file,'{"is_google":false}'); return false;
}

/* ======= É BRASIL? ======= */
function is_brazil(string $ip): bool {
    // a) Cloudflare
    if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
        return strtoupper($_SERVER['HTTP_CF_IPCOUNTRY']) === 'BR';
    }

    // b) GeoIP local
    if (function_exists('geoip_country_code_by_name')) {
        $code = @geoip_country_code_by_name($ip);
        if ($code) return strtoupper($code) === 'BR';
    }

    // c) PTR termina com .br
    $ptr = @gethostbyaddr($ip);
    if ($ptr && $ptr !== $ip) {
        $host = strtolower($ptr);
        if (str_ends_with($host, '.br')) {
            return true;
        }
    }

    // d) Cache + APIs
    $cacheDir = __DIR__.'/.geo-cache'; if(!is_dir($cacheDir)) @mkdir($cacheDir,0755,true);
    $key = preg_replace('/[^0-9a-f:\.]/i','_',$ip);
    $file = $cacheDir.'/'.$key.'.json'; $ttl = 3600;

    if (is_file($file) && (time()-filemtime($file)<$ttl)) {
        $j = @json_decode(@file_get_contents($file),true);
        if (isset($j['country'])) return strtoupper($j['country']) === 'BR';
    }

    $country = null;

    // ipwho.is
    $ch = curl_init('https://ipwho.is/'.rawurlencode($ip).'?fields=country_code');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER=>true, CURLOPT_CONNECTTIMEOUT=>1, CURLOPT_TIMEOUT=>2, CURLOPT_USERAGENT=>'geo-redirect/1.2'
    ]);
    $resp = curl_exec($ch); curl_close($ch);
    if (is_string($resp)) {
        $j = @json_decode($resp, true);
        if (isset($j['country_code']) && strlen($j['country_code'])===2) {
            $country = strtoupper($j['country_code']);
        }
    }

    // fallback ipapi.co
    if (!$country) {
        $ch = curl_init('https://ipapi.co/'.rawurlencode($ip).'/country/');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER=>true, CURLOPT_CONNECTTIMEOUT=>1, CURLOPT_TIMEOUT=>2, CURLOPT_USERAGENT=>'geo-redirect/1.2'
        ]);
        $resp = curl_exec($ch); curl_close($ch);
        if (is_string($resp) && strlen(trim($resp))===2) {
            $country = strtoupper(trim($resp));
        }
    }

    @file_put_contents($file, json_encode(['country'=>$country], JSON_UNESCAPED_SLASHES));
    return $country === 'BR';
}

/* ======= DECISÃO ======= */
$ip     = get_client_ip();
$google = is_googlebot($ip);
$br     = is_brazil($ip);

// DEBUG opcional: ?dbg=1
if (isset($_GET['dbg']) && $_GET['dbg']==='1') {
    header('Content-Type: text/plain; charset=UTF-8');
    echo "ip=$ip\n";
    echo "ua=".($_SERVER['HTTP_USER_AGENT'] ?? '')."\n";
    echo "cf_country=".($_SERVER['HTTP_CF_IPCOUNTRY'] ?? '')."\n";
    echo "ptr=".(@gethostbyaddr($ip) ?: '')."\n";
    echo "is_google=".($google?'1':'0')."\n";
    echo "is_brazil=".($br?'1':'0')."\n";
    echo "script=".basename($_SERVER['SCRIPT_NAME'] ?? '')."\n";
    exit;
}

// ---- NOVA LÓGICA: SEM REDIRECIONAMENTO ----
// Agora NINGUÉM é redirecionado mais. Todo mundo permanece no index.php.
// Mantemos só a detecção (para debug / análise / futuros logs).

$currentScript = basename($_SERVER['SCRIPT_NAME'] ?? '');
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Se quiser, futuramente você pode logar isso em arquivo ou banco, ex.:
// error_log(sprintf('[VISITA] ip=%s br=%d google=%d ua=%s', $ip, $br ? 1 : 0, $google ? 1 : 0, $ua));

// Sem header('Location...') aqui: apenas segue o carregamento normal do index.php.

?>

<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['last_notification'])) {
    $_SESSION['last_notification'] = time();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Da Mamãe Fitness - Emagreça Treinando</title>
    <meta name="description" content="Transforme seu corpo através do treino físico. Programas completos de emagrecimento com foco em atividade física e resultados reais.">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="hero">
        <div class="hero-background">
            <picture>
                <source media="(max-width: 768px)" srcset="assets/images/index/hero-banner-mobile.jpg">
                <img src="assets/images/index/hero-banner.jpg" alt="Emagreça Treinando" class="hero-bg-image" loading="eager">
            </picture>
        </div>
        <div class="container">
            <div class="hero-content">
                <h2 class="hero-title">Emagreça Treinando —<br>Corpo Ativo, Mente Leve</h2>
                <p class="hero-subtitle">Transforme seu corpo através do movimento. Programas completos de treino para emagrecimento real, com foco, disciplina e energia. Seu resultado começa agora!</p>
                <div class="hero-cta">
                    <a href="produtos.php" class="btn btn-primary">Comece Seu Treino Agora</a>
                    <a href="sobre.php" class="btn btn-secondary">Conheça o Método</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Atualizando produtos com novos preços e otimizando carregamento de imagens -->
    <section class="featured-products">
        <div class="container">
            <h2 class="section-title">Programas de Treino Mais Procurados</h2>
            <div class="products-grid">
                <?php foreach ($PRODUCTS as $product): ?>
                <a href="produto.php?id=<?php echo $product['id']; ?>" class="product-card-link">
                    <div class="product-card">
                        <?php if (!empty($product['badge'])): ?>
                        <div class="product-badge"><?php echo htmlspecialchars($product['badge']); ?></div>
                        <?php endif; ?>
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 loading="lazy"
                                 width="300"
                                 height="300">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="product-footer">
                                <div class="product-price">
                                    <?php if (!empty($product['old_price'])): ?>
                                    <span class="old-price">R$ <?php echo number_format($product['old_price'], 2, ',', '.'); ?></span>
                                    <?php endif; ?>
                                    <span class="price">R$ <?php echo number_format($product['final_price'], 2, ',', '.'); ?></span>
                                </div>
                                <span class="btn btn-buy">Começar Agora</span>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="benefits">
        <div class="container">
            <h2 class="section-title">Por Que Treinar Com a Gente?</h2>
            <div class="benefits-grid">
                <div class="benefit-item">
                    <i class="fas fa-bolt"></i>
                    <h3>Mais Energia</h3>
                    <p>Sinta-se mais disposto e energizado no dia a dia</p>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-heart-pulse"></i>
                    <h3>Saúde em Dia</h3>
                    <p>Melhore sua saúde cardiovascular e bem-estar geral</p>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-trophy"></i>
                    <h3>Resultados Reais</h3>
                    <p>Transformação visível com foco e disciplina</p>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-smile-beam"></i>
                    <h3>Autoestima Elevada</h3>
                    <p>Conquiste confiança e amor próprio através do treino</p>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials-preview">
        <div class="container">
            <h2 class="section-title">Resultados Que Inspiram</h2>
            <div class="testimonials-slider">
                <div class="testimonial-item">
                    <div class="testimonial-image">
                        <img src="assets/images/depoimentos/maria.jpg" alt="Maria Silva" loading="lazy" width="100" height="100">
                    </div>
                    <div class="testimonial-content">
                        <p>"Perdi 12kg em 2 meses treinando! Me sinto mais forte, confiante e cheia de energia. Melhor decisão da minha vida!"</p>
                        <div class="testimonial-author">
                            <strong>Maria Silva</strong>
                            <span>São Paulo, SP</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="testimonial-image">
                        <img src="assets/images/depoimentos/joao.jpg" alt="João Santos" loading="lazy" width="100" height="100">
                    </div>
                    <div class="testimonial-content">
                        <p>"Os treinos são intensos mas funcionam! Eliminei 15kg e ganhei massa muscular. Recomendo demais!"</p>
                        <div class="testimonial-author">
                            <strong>João Santos</strong>
                            <span>Rio de Janeiro, RJ</span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="depoimentos.php" class="btn btn-secondary">Ver Mais Transformações</a>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <a href="https://api.whatsapp.com/send?phone=5511984968625&text=Olá!%20Quero%20começar%20meu%20treino!" 
       class="whatsapp-float" target="_blank" rel="noopener">
        <i class="fab fa-whatsapp"></i>
    </a>

    <div id="purchase-notification" class="purchase-notification">
        <div class="notification-content">
            <i class="fas fa-check-circle"></i>
            <div class="notification-text">
                <strong id="notification-name"></strong>
                <span id="notification-message"></span>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
