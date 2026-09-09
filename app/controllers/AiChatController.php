<?php
/**
 * AI Chatbot Controller
 * Powered by Google Gemini Generative AI REST API
 */

class AiChatController extends Controller {

    /**
     * Handle chat messages from frontend widget
     */
    public function chat() {
        header('Content-Type: application/json');
        
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true) ?: $_POST;
        $userMessage = trim($input['message'] ?? '');

        if (empty($userMessage)) {
            echo json_encode([
                'success' => false,
                'reply' => "Hello! I am Nova, Tek Trend's AI Solutions Architect. Ask me anything about our software engineering, turnkey templates, custom architectures, or book a 1-on-1 Zoom consultation!"
            ]);
            exit;
        }

        // 1. Retrieve Google Gemini API Key
        $apiKey = $this->resolveApiKey();

        // 2. Call Google Gemini API if key exists
        if (!empty($apiKey)) {
            $geminiResponse = $this->callGeminiApi($userMessage, $apiKey);
            if (!empty($geminiResponse)) {
                echo json_encode([
                    'success'   => true,
                    'reply'     => $geminiResponse,
                    'model'     => 'gemini-1.5-flash',
                    'timestamp' => date('H:i')
                ]);
                exit;
            }
        }

        // 3. Intelligent fallback if key not configured or API call fails
        $fallbackReply = $this->generateIntelligentFallback($userMessage, empty($apiKey));
        echo json_encode([
            'success'   => true,
            'reply'     => $fallbackReply,
            'model'     => 'tektrend-engine',
            'timestamp' => date('H:i')
        ]);
        exit;
    }

    /**
     * Resolve Google Gemini API Key from environment or database settings
     */
    private function resolveApiKey() {
        if (!empty(GOOGLE_API_KEY)) {
            return GOOGLE_API_KEY;
        }

        $envKey = getenv('GOOGLE_API_KEY') ?: getenv('GEMINI_API_KEY');
        if (!empty($envKey)) {
            return $envKey;
        }

        try {
            $row = $this->db->fetch("SELECT value FROM settings WHERE `key` = 'google_api_key' OR `key` = 'gemini_api_key' AND value != '' LIMIT 1");
            if (!empty($row['value'])) {
                return trim($row['value']);
            }
        } catch (\Throwable $e) {}

        return '';
    }

    /**
     * Call Google Gemini 1.5 Flash REST API
     */
    private function callGeminiApi($userMessage, $apiKey) {
        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . urlencode($apiKey);

        $systemPrompt = "You are Nova, the official AI Solutions Architect at Tek Trend Innovations (tektrend.com). " .
            "Tek Trend is an elite software engineering and design consultancy delivering world-class PHP systems, Google Workspace automation, and Awwwards-caliber web platforms.\n\n" .
            "Live Prototype Templates in our collection:\n" .
            "- Studio Maven · Creative Design Studio ($49 turnkey code, /live_demo/graphic.html)\n" .
            "- GrowthPulse · Marketing & SEO Agency ($59 turnkey code, /live_demo/digital_markting.html)\n" .
            "- LuxeCart · Modern E-Commerce Store ($79 turnkey code, /live_demo/e-commerce.html)\n" .
            "- Vanguard & Sterling · Legal Platform ($69 turnkey code, /live_demo/law_firm.html)\n" .
            "- Grace Fellowship · Community & Faith Hub ($39 turnkey code, /live_demo/church.html)\n" .
            "- Titan Engineering · Industrial Automation ($79 turnkey code, /live_demo/engineering.html)\n" .
            "- Le Jardin · Gourmet Restaurant ($49 turnkey code, /live_demo/restaurant.html)\n" .
            "- Verve · Modern Tech Magazine ($39 turnkey code, /live_demo/magazine.html)\n" .
            "- Nexus Commercial · Enterprise SaaS Platform ($89 turnkey code, /live_demo/nexus.html)\n\n" .
            "Key Information:\n" .
            "- Custom Systems: Clients can commission tailored full-stack software built by our engineering team.\n" .
            "- Turnkey Templates: Ready-to-deploy clean source code with commercial usage rights.\n" .
            "- Consultations: Free 45-min Zoom consultations can be booked directly on our homepage.\n" .
            "- WhatsApp: Instant direct chat available at +254 707 246 273.\n" .
            "- Email: info@tektrend.com.\n\n" .
            "Guidelines: Keep answers helpful, concise, well-structured (use bullet points and bolding), warm, and authoritative. Suggest relevant prototypes, booking a Zoom session, or messaging on WhatsApp where helpful.";

        $payload = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemPrompt]
                ]
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $userMessage]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 700
            ]
        ];

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 12,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && !empty($response)) {
            $data = json_decode($response, true);
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            if (!empty($text)) {
                return trim($text);
            }
        }

        return '';
    }

    /**
     * Context-aware fallback if Gemini API is offline or key is unconfigured
     */
    private function generateIntelligentFallback($msg, $keyMissing = false) {
        $m = strtolower($msg);

        if (str_contains($m, 'price') || str_contains($m, 'cost') || str_contains($m, 'rate') || str_contains($m, 'how much')) {
            $res = "💡 **Turnkey Templates & Custom Engineering Pricing**:\n\n" .
                   "• **Turnkey HTML Templates**: $39 to $89 (Includes clean source code, commercial deployment rights, and documentation).\n" .
                   "• **Custom Software Builds**: From $500 – $4,500 based on architecture complexity, payment gateways, and custom database requirements.\n" .
                   "• **Monthly Architecture Retainer**: From $800/month.\n\n" .
                   "Would you like to explore our 9 working prototypes at `/live-demos` or request a custom quote?";
        } elseif (str_contains($m, 'template') || str_contains($m, 'prototype') || str_contains($m, 'demo')) {
            $res = "🚀 **Our Production HTML Prototypes**:\n\n" .
                   "1. **LuxeCart** · Modern E-Commerce ($79)\n" .
                   "2. **GrowthPulse** · Marketing & SEO Agency ($59)\n" .
                   "3. **Vanguard & Sterling** · Corporate Legal Advisory ($69)\n" .
                   "4. **Studio Maven** · Creative Branding Studio ($49)\n" .
                   "5. **Titan Engineering** · Industrial Automation ($79)\n" .
                   "6. **Nexus Commercial** · Enterprise SaaS Platform ($89)\n" .
                   "7. **Le Jardin** · Gourmet Dining & Table Booking ($49)\n" .
                   "8. **Grace Fellowship** · Community & Faith Hub ($39)\n" .
                   "9. **Verve** · Editorial Tech Magazine ($39)\n\n" .
                   "You can review any of these live on our **Live Demos** page and buy or request customization!";
        } elseif (str_contains($m, 'zoom') || str_contains($m, 'consult') || str_contains($m, 'meet') || str_contains($m, 'book')) {
            $res = "📅 **Schedule a 1-on-1 Zoom Consultation**:\n\n" .
                   "You can book directly with our Principal Solutions Architect right from our homepage! Pick your preferred date and 45-minute slot, and you'll receive your instant Zoom invitation.";
        } elseif (str_contains($m, 'whatsapp') || str_contains($m, 'contact') || str_contains($m, 'call') || str_contains($m, 'phone')) {
            $res = "📱 **Direct Contact Channels**:\n\n" .
                   "• **WhatsApp**: [+254 707 246 273](https://wa.me/254707246273)\n" .
                   "• **Email**: [info@tektrend.com](mailto:info@tektrend.com)\n" .
                   "• **Direct Call**: +254 707 246 273\n\n" .
                   "Our solutions architects respond within minutes during business hours.";
        } elseif (str_contains($m, 'custom') || str_contains($m, 'system') || str_contains($m, 'build') || str_contains($m, 'software')) {
            $res = "🛠️ **Custom System Engineering**:\n\n" .
                   "We engineer custom full-stack software tailored to your workflows:\n" .
                   "• Bespoke ERPs & Customer Portals\n" .
                   "• Automated Invoicing & M-Pesa / Stripe Payments\n" .
                   "• Google Workspace / Apps Script Enterprise Automations\n" .
                   "• Real-time Teleconferencing & Chat Systems\n\n" .
                   "Submit your requirements on any prototype card or book a Zoom call to begin!";
        } else {
            $res = "Welcome to **Tek Trend Innovations**! We deliver world-class software architecture, custom business web applications, and turnkey production templates.\n\n" .
                   "How can I assist you today? I can help you choose a turnkey template, scope a custom web build, or book a consultation.";
        }

        if ($keyMissing) {
            $res .= "\n\n*(💡 Admin Note: Set your Google Gemini API key in `.env` as `GOOGLE_API_KEY=AIza...` or in the admin Settings panel to unlock dynamic AI answers).*";
        }

        return $res;
    }
}
