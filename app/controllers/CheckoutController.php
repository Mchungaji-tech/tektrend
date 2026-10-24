<?php
/**
 * Checkout & Paystack Payment Controller
 * Handles Paystack checkout initialization, verification, webhooks, and secure code downloads.
 */

class CheckoutController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->ensurePurchasesTable();
    }

    /**
     * Ensure the software_purchases table exists
     */
    private function ensurePurchasesTable() {
        try {
            $this->db->execute("CREATE TABLE IF NOT EXISTS `software_purchases` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` INT UNSIGNED DEFAULT NULL,
                `customer_email` VARCHAR(150) NOT NULL,
                `customer_name` VARCHAR(150) NOT NULL,
                `customer_phone` VARCHAR(50) DEFAULT NULL,
                `item_type` ENUM('portfolio_demo','design_item','custom_project') DEFAULT 'portfolio_demo',
                `item_id` INT UNSIGNED DEFAULT NULL,
                `item_title` VARCHAR(255) NOT NULL,
                `amount` DECIMAL(10,2) NOT NULL,
                `currency` VARCHAR(10) DEFAULT 'KES',
                `paystack_reference` VARCHAR(100) NOT NULL,
                `paystack_status` ENUM('pending','success','failed') DEFAULT 'pending',
                `download_token` VARCHAR(64) DEFAULT NULL,
                `download_count` INT UNSIGNED DEFAULT 0,
                `download_file` VARCHAR(255) DEFAULT NULL,
                `paid_at` DATETIME DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `paystack_reference` (`paystack_reference`),
                KEY `download_token` (`download_token`),
                KEY `user_id` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (\Throwable $e) {
            error_log('ensurePurchasesTable error: ' . $e->getMessage());
        }
    }

    /**
     * Initiate Paystack Checkout (AJAX or direct POST)
     */
    public function initiate() {
        $this->verifyCsrf();

        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $itemId = (int)($_POST['item_id'] ?? 0);
        $itemType = trim($_POST['item_type'] ?? 'portfolio_demo');
        $itemTitleInput = trim($_POST['item_title'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->respondError('Please provide a valid email address.');
            return;
        }

        if (empty($name)) {
            $this->respondError('Please provide your name.');
            return;
        }

        // Fetch and verify item price server-side (NEVER trust client amounts)
        $item = null;
        $amountUSD = 49.00;
        $itemTitle = $itemTitleInput ?: 'Software License';

        if ($itemType === 'design_item' && $itemId > 0) {
            $item = $this->db->fetch("SELECT * FROM design_items WHERE id = ? LIMIT 1", [$itemId]);
            if ($item) {
                $itemTitle = $item['title'];
                $amountUSD = (float)($item['buy_now_price'] ?? $item['starting_bid'] ?? 49.00);
            }
        } else {
            // portfolio_demos
            if ($itemId > 0) {
                $item = $this->db->fetch("SELECT * FROM portfolio_demos WHERE id = ? LIMIT 1", [$itemId]);
            }
            if (!$item && !empty($itemTitleInput)) {
                $item = $this->db->fetch("SELECT * FROM portfolio_demos WHERE title = ? LIMIT 1", [$itemTitleInput]);
            }

            if ($item) {
                // Security rule: Completed client projects (like Panda Realty) are NOT for sale
                if (isset($item['is_for_sale']) && (int)$item['is_for_sale'] === 0) {
                    $this->respondError("'" . sanitize($item['title']) . "' is a bespoke client deployment and is not for sale. You may request a custom system inspired by it instead.", 403);
                    return;
                }
                $itemTitle = $item['title'];
                $amountUSD = (float)($item['price'] ?? 49.00);
            }
        }

        // Convert USD price to Paystack currency if configured as KES
        $currency = defined('PAYSTACK_CURRENCY') ? PAYSTACK_CURRENCY : 'KES';
        $usdToKesRate = 130.0; // Standard exchange benchmark
        $chargedAmount = ($currency === 'KES') ? round($amountUSD * $usdToKesRate, 2) : $amountUSD;
        $amountInMinorUnits = (int)round($chargedAmount * 100);

        // Generate unique reference
        $reference = 'TEK-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

        // Determine current user ID if logged in
        $userId = $this->auth->check() ? $this->auth->id() : null;

        // If not logged in, check if user exists or link to existing customer
        if (!$userId) {
            $existingUser = $this->db->fetch("SELECT id FROM users WHERE email = ? LIMIT 1", [$email]);
            if ($existingUser) {
                $userId = $existingUser['id'];
            }
        }

        // Record order in pending state
        try {
            $this->db->insert(
                "INSERT INTO software_purchases (user_id, customer_email, customer_name, customer_phone, item_type, item_id, item_title, amount, currency, paystack_reference, paystack_status, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())",
                [$userId, $email, $name, $phone, $itemType, $item['id'] ?? $itemId, $itemTitle, $chargedAmount, $currency, $reference]
            );
        } catch (\Throwable $e) {
            error_log('Failed to create software_purchases order: ' . $e->getMessage());
            $this->respondError('Could not initialize order. Please try again.');
            return;
        }

        $secretKey = defined('PAYSTACK_SECRET_KEY') ? trim(PAYSTACK_SECRET_KEY) : '';
        $publicKey = defined('PAYSTACK_PUBLIC_KEY') ? trim(PAYSTACK_PUBLIC_KEY) : '';
        $callbackUrl = url('/checkout/callback?reference=' . urlencode($reference));

        // If Paystack is configured, initialize transaction with Paystack API
        if (!empty($secretKey)) {
            $payload = [
                'email'        => $email,
                'amount'       => $amountInMinorUnits,
                'currency'     => $currency,
                'reference'    => $reference,
                'callback_url' => $callbackUrl,
                'metadata'     => [
                    'customer_name'  => $name,
                    'customer_phone' => $phone,
                    'item_title'     => $itemTitle,
                    'item_id'        => $item['id'] ?? $itemId,
                    'custom_fields'  => [
                        [
                            'display_name'  => 'Software Package',
                            'variable_name' => 'software_package',
                            'value'         => $itemTitle
                        ]
                    ]
                ]
            ];

            $response = $this->curlPost('https://api.paystack.co/transaction/initialize', $payload, $secretKey);

            if (!empty($response['status']) && !empty($response['data']['authorization_url'])) {
                if ($this->isAjax()) {
                    $this->json([
                        'success'           => true,
                        'authorization_url' => $response['data']['authorization_url'],
                        'access_code'       => $response['data']['access_code'] ?? '',
                        'reference'         => $reference,
                        'public_key'        => $publicKey,
                        'amount'            => $chargedAmount,
                        'currency'          => $currency
                    ]);
                    return;
                }
                redirect($response['data']['authorization_url']);
                return;
            } else {
                $errMsg = $response['message'] ?? 'Could not communicate with Paystack payment gateway.';
                error_log('Paystack init error: ' . json_encode($response));
                $this->respondError($errMsg);
                return;
            }
        }

        // --- Demo / Sandbox Fallback (when PAYSTACK_SECRET_KEY is empty in local dev) ---
        // Allows testing the complete end-to-end checkout flow without live credentials
        if ($this->isAjax()) {
            $this->json([
                'success'           => true,
                'is_demo_mode'      => true,
                'authorization_url' => $callbackUrl . '&demo=1',
                'reference'         => $reference,
                'public_key'        => $publicKey,
                'amount'            => $chargedAmount,
                'currency'          => $currency,
                'message'           => 'Proceeding in sandbox verification mode.'
            ]);
            return;
        }

        redirect($callbackUrl . '&demo=1');
    }

    /**
     * Paystack Redirect / Callback Handler
     * Re-verifies payment server-side before fulfilling order
     */
    public function callback() {
        $reference = trim($_GET['reference'] ?? ($_GET['trxref'] ?? ''));
        $isDemo = !empty($_GET['demo']);

        if (empty($reference)) {
            $this->session->flash('error', 'Invalid payment reference.');
            redirect('/shop');
            return;
        }

        $order = $this->db->fetch("SELECT * FROM software_purchases WHERE paystack_reference = ? LIMIT 1", [$reference]);
        if (!$order) {
            $this->session->flash('error', 'Order not found for reference ' . sanitize($reference));
            redirect('/shop');
            return;
        }

        $secretKey = defined('PAYSTACK_SECRET_KEY') ? trim(PAYSTACK_SECRET_KEY) : '';
        $paymentVerified = false;

        if (!empty($secretKey)) {
            // Verify via Paystack API: GET https://api.paystack.co/transaction/verify/:reference
            $verifyRes = $this->curlGet('https://api.paystack.co/transaction/verify/' . urlencode($reference), $secretKey);
            if (!empty($verifyRes['status']) && ($verifyRes['data']['status'] ?? '') === 'success') {
                $verifiedAmountMinor = (int)($verifyRes['data']['amount'] ?? 0);
                $expectedMinor = (int)round(((float)$order['amount']) * 100);

                // Anti-tamper verification (minor units must match)
                if ($verifiedAmountMinor >= $expectedMinor) {
                    $paymentVerified = true;
                } else {
                    error_log("Paystack amount mismatch for ref $reference: got $verifiedAmountMinor, expected $expectedMinor");
                }
            } else {
                error_log("Paystack verify failed for ref $reference: " . json_encode($verifyRes));
            }
        } elseif ($isDemo || APP_DEBUG) {
            // Local dev mode without keys configured
            $paymentVerified = true;
        }

        if (!$paymentVerified) {
            $this->session->flash('error', 'Payment verification failed or was not completed. If money was deducted, please contact support.');
            redirect('/shop');
            return;
        }

        // Fulfill the purchase idempotently
        $token = $order['download_token'];
        if (empty($token) || $order['paystack_status'] !== 'success') {
            $token = bin2hex(random_bytes(24));
            $this->db->execute(
                "UPDATE software_purchases 
                 SET paystack_status = 'success', download_token = ?, paid_at = NOW() 
                 WHERE id = ? AND paystack_status <> 'success'",
                [$token, $order['id']]
            );

            // Provision or log in buyer so they have instant access
            $this->provisionBuyerAccount($order);
        }

        $this->session->flash('success', 'Payment successful! Your turnkey source code package is ready to download.');
        redirect('/checkout/success?ref=' . urlencode($reference));
    }

    /**
     * Thank You / Order Confirmation & Download Screen
     */
    public function success() {
        $reference = trim($_GET['ref'] ?? '');
        if (empty($reference)) {
            redirect('/shop');
            return;
        }

        $order = $this->db->fetch("SELECT * FROM software_purchases WHERE paystack_reference = ? AND paystack_status = 'success' LIMIT 1", [$reference]);
        if (!$order) {
            $this->session->flash('error', 'Order not found or payment pending.');
            redirect('/shop');
            return;
        }

        $this->view->setLayout(null);
        $this->render('checkout/success', [
            'order'    => $order,
            'settings' => $this->getSettings()
        ], null);
    }

    /**
     * Secure Code Download Stream
     */
    public function download($token) {
        $token = trim($token);
        if (empty($token)) {
            http_response_code(404);
            exit('Invalid download token.');
        }

        $order = $this->db->fetch("SELECT * FROM software_purchases WHERE download_token = ? AND paystack_status = 'success' LIMIT 1", [$token]);
        if (!$order) {
            http_response_code(404);
            exit('Download link expired or invalid.');
        }

        // Increment download count
        $this->db->execute("UPDATE software_purchases SET download_count = download_count + 1 WHERE id = ?", [$order['id']]);

        // Build safe filename
        $safeTitle = preg_replace('/[^a-zA-Z0-9_\-]/', '_', strtolower($order['item_title']));
        $zipFilename = 'tektrend_' . $safeTitle . '_package.zip';

        // Check if a pre-packaged zip exists in protected storage
        $storageDir = BASE_PATH . '/storage/downloads';
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0755, true);
        }

        $customFile = $order['download_file'] ?? '';
        $filePath = (!empty($customFile) && file_exists($storageDir . '/' . $customFile)) ? ($storageDir . '/' . $customFile) : null;

        // If a pre-packaged zip exists on disk, stream it directly
        if ($filePath && is_file($filePath)) {
            $this->streamZipFile($filePath, $zipFilename);
            exit;
        }

        // Dynamically bundle the project source code, deployment guide, and commercial license into a clean ZIP
        $this->streamDynamicProjectZip($order, $zipFilename);
        exit;
    }

    /**
     * Paystack Webhook Handler (Charge Success)
     */
    public function webhook() {
        $secretKey = defined('PAYSTACK_SECRET_KEY') ? trim(PAYSTACK_SECRET_KEY) : '';
        if (empty($secretKey)) {
            http_response_code(200);
            echo 'Webhook received (secret unconfigured)';
            exit;
        }

        $signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';
        $payload = file_get_contents('php://input');

        // HMAC-SHA512 verification over raw request body
        $expected = hash_hmac('sha512', $payload, $secretKey);
        if (!hash_equals($expected, (string)$signature)) {
            http_response_code(400);
            exit('Invalid signature');
        }

        $event = json_decode($payload, true);
        if (($event['event'] ?? '') === 'charge.success') {
            $ref = $event['data']['reference'] ?? '';
            if ($ref) {
                $token = bin2hex(random_bytes(24));
                $this->db->execute(
                    "UPDATE software_purchases 
                     SET paystack_status = 'success', download_token = COALESCE(download_token, ?), paid_at = NOW() 
                     WHERE paystack_reference = ? AND paystack_status <> 'success'",
                    [$token, $ref]
                );

                $order = $this->db->fetch("SELECT * FROM software_purchases WHERE paystack_reference = ? LIMIT 1", [$ref]);
                if ($order) {
                    $this->provisionBuyerAccount($order);
                }
            }
        }

        http_response_code(200);
        echo 'OK';
        exit;
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Auto-provisions or links a buyer user account and logs them in
     */
    private function provisionBuyerAccount($order) {
        try {
            $email = $order['customer_email'];
            $existingUser = $this->db->fetch("SELECT * FROM users WHERE email = ? LIMIT 1", [$email]);

            if ($existingUser) {
                // Link order to user if not linked
                if (empty($order['user_id'])) {
                    $this->db->execute("UPDATE software_purchases SET user_id = ? WHERE id = ?", [$existingUser['id'], $order['id']]);
                }
                if (!$this->auth->check()) {
                    $this->auth->loginUser($existingUser, 'Purchase Checkout');
                }
                return;
            }

            // Create client account for buyer
            $names = explode(' ', $order['customer_name'], 2);
            $first = $names[0] ?: 'Buyer';
            $last = $names[1] ?? 'Customer';
            $employeeId = 'CL' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
            $randPass = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT, ['cost' => BCRYPT_ROUNDS]);

            $newUserId = $this->db->insert(
                "INSERT INTO users (employee_id, first_name, last_name, email, password, role, status) VALUES (?, ?, ?, ?, ?, 'client', 'active')",
                [$employeeId, $first, $last, $email, $randPass]
            );

            // Link customer record
            $this->db->insert(
                "INSERT INTO customers (user_id, first_name, last_name, email, phone, status) VALUES (?, ?, ?, ?, ?, 'active')",
                [$newUserId, $first, $last, $email, $order['customer_phone'] ?? null]
            );

            $this->db->execute("UPDATE software_purchases SET user_id = ? WHERE id = ?", [$newUserId, $order['id']]);

            $newUser = $this->db->fetch("SELECT * FROM users WHERE id = ? LIMIT 1", [$newUserId]);
            if ($newUser && !$this->auth->check()) {
                $this->auth->loginUser($newUser, 'Purchase Checkout');
            }
        } catch (\Throwable $e) {
            error_log('provisionBuyerAccount error: ' . $e->getMessage());
        }
    }

    /**
     * Stream dynamically assembled project source ZIP
     */
    private function streamDynamicProjectZip($order, $filename) {
        $zip = new ZipArchive();
        $tmpFile = tempnam(sys_get_temp_dir(), 'tek_pkg_');

        if ($zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            exit('Could not prepare archive for download.');
        }

        // Add commercial license certificate
        $licenseTxt = "====================================================================\n" .
                      " TEKTREND SOFTWARES · COMMERCIAL SOFTWARE LICENSE CERTIFICATE\n" .
                      "====================================================================\n\n" .
                      "Issued To:       " . $order['customer_name'] . "\n" .
                      "Email:           " . $order['customer_email'] . "\n" .
                      "Product:         " . $order['item_title'] . "\n" .
                      "Transaction Ref: " . $order['paystack_reference'] . "\n" .
                      "Amount Paid:     " . $order['currency'] . " " . number_format($order['amount'], 2) . "\n" .
                      "Date Issued:     " . ($order['paid_at'] ?: date('Y-m-d H:i:s')) . "\n\n" .
                      "LICENSE TERMS:\n" .
                      "1. Grants the licensee a perpetual, worldwide commercial license to deploy,\n" .
                      "   customize, and operate this source code for unlimited client projects.\n" .
                      "2. Includes clean MVC structure, HTML5/CSS3 assets, and full database schemas.\n" .
                      "3. Source code verified and authenticated by Tektrend Softwares Eldoret, Kenya.\n" .
                      "   Support & Engineering Inquiries: tektrend.softwares@gmail.com\n" .
                      "====================================================================\n";
        $zip->addFromString('LICENSE.txt', $licenseTxt);

        // Add deployment instructions
        $readmeTxt = "# " . $order['item_title'] . "\n\n" .
                     "Delivered by Tektrend Softwares (https://tektrend.online)\n\n" .
                     "## Quick Setup Instructions\n" .
                     "1. Extract this package to your web server (Apache/cPanel/Nginx or local XAMPP/WAMP).\n" .
                     "2. Point your web root or virtual host to the extracted folder.\n" .
                     "3. If database tables are provided, import `database.sql` into MySQL.\n" .
                     "4. Configure environment settings in `.env` or `config.php`.\n" .
                     "5. Open your domain or localhost in any modern browser.\n\n" .
                     "For architecture consulting, custom backend extensions, or hosting setups,\n" .
                     "contact: tektrend.softwares@gmail.com\n";
        $zip->addFromString('README.md', $readmeTxt);

        // Include matching template HTML/CSS/JS if available
        $liveDemoDir = BASE_PATH . '/live_demo';
        $demoRow = null;
        if (!empty($order['item_id'])) {
            $demoRow = $this->db->fetch("SELECT * FROM portfolio_demos WHERE id = ? LIMIT 1", [$order['item_id']]);
        }

        if ($demoRow && !empty($demoRow['demo_url'])) {
            $targetRel = ltrim(parse_url($demoRow['demo_url'], PHP_URL_PATH), '/');
            $sourceHtmlPath = BASE_PATH . '/' . $targetRel;
            if (file_exists($sourceHtmlPath)) {
                $zip->addFile($sourceHtmlPath, 'index.html');
                // Also package enhance.css and enhance.js if present
                if (file_exists($liveDemoDir . '/enhance.css')) {
                    $zip->addFile($liveDemoDir . '/enhance.css', 'assets/css/enhance.css');
                }
                if (file_exists($liveDemoDir . '/enhance.js')) {
                    $zip->addFile($liveDemoDir . '/enhance.js', 'assets/js/enhance.js');
                }
            }
        }

        $zip->close();
        $this->streamZipFile($tmpFile, $filename);
        @unlink($tmpFile);
    }

    /**
     * Send HTTP headers and stream ZIP file to browser
     */
    private function streamZipFile($fullPath, $downloadName) {
        if (!file_exists($fullPath)) {
            http_response_code(404);
            exit('File not found.');
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $downloadName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
    }

    private function respondError($message, $code = 400) {
        if ($this->isAjax()) {
            $this->json(['success' => false, 'message' => $message], $code);
            return;
        }
        $this->session->flash('error', $message);
        redirect(backUrl());
    }

    private function curlPost($url, array $payload, $secretKey) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $secretKey,
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_SSL_VERIFYPEER => true
        ]);
        $body = curl_exec($ch);
        curl_close($ch);
        return json_decode((string)$body, true) ?: [];
    }

    private function curlGet($url, $secretKey) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $secretKey,
                'Accept: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_SSL_VERIFYPEER => true
        ]);
        $body = curl_exec($ch);
        curl_close($ch);
        return json_decode((string)$body, true) ?: [];
    }

    private function getSettings() {
        $settings = [];
        try {
            $rows = $this->db->fetchAll("SELECT `key`, `value` FROM settings");
            foreach ($rows as $row) {
                $settings[$row['key']] = $row['value'];
            }
        } catch (\Throwable $e) {}
        return $settings;
    }
}
