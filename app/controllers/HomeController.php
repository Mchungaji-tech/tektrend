<?php
/**
 * Home Controller
 * Serves the public consultancy platform, Zoom booking, Awwwards design bidding, AI chatbot demo & team showcase
 */
class HomeController extends Controller {

    /**
     * Display the public consultancy landing page
     */
    public function index() {
        // Fetch settings
        $settings = [];
        try {
            $rows = $this->db->fetchAll("SELECT `key`, `value` FROM settings");
            foreach ($rows as $row) {
                $settings[$row['key']] = $row['value'];
            }
        } catch (\Throwable $e) {
            $settings = [];
        }

        // Fetch active design bidding items (Awwwards-style)
        $designItems = [];
        try {
            $designItems = $this->db->fetchAll(
                "SELECT * FROM design_items WHERE status = 'active' ORDER BY id DESC"
            );
        } catch (\Throwable $e) {
            $designItems = [];
        }

        // Fetch leadership & team members
        $team = [];
        try {
            $team = $this->db->fetchAll(
                "SELECT u.first_name, u.last_name, u.position, u.role, u.email, u.avatar, d.name as department_name 
                 FROM users u 
                 LEFT JOIN departments d ON u.department_id = d.id 
                 WHERE u.status = 'active' 
                 ORDER BY u.id ASC"
            );
        } catch (\Throwable $e) {
            $team = [];
        }

        // Fetch CMS content
        $cms = [];
        try {
            $contentRows = $this->db->fetchAll("SELECT `key`, `content` FROM content WHERE is_active = 1");
            foreach ($contentRows as $cr) {
                $cms[$cr['key']] = $cr['content'];
            }
        } catch (\Throwable $e) {
            $cms = [];
        }

        // Fetch portfolio demos safely with auto-creation fallback
        $portfolioDemos = [];
        try {
            $portfolioDemos = $this->db->fetchAll(
                "SELECT * FROM portfolio_demos WHERE is_featured = 1 ORDER BY sort_order ASC, id DESC LIMIT 12"
            );
        } catch (\Throwable $e) {
            if ($this->ensurePortfolioDemosTable()) {
                try {
                    $portfolioDemos = $this->db->fetchAll(
                        "SELECT * FROM portfolio_demos WHERE is_featured = 1 ORDER BY sort_order ASC, id DESC LIMIT 12"
                    );
                } catch (\Throwable $ex) {
                    $portfolioDemos = [];
                }
            }
        }

        // Disable dashboard layout for standalone luxury landing page
        $this->view->setLayout(null);
        $this->render('home/index', [
            'settings'       => $settings,
            'designItems'    => $designItems,
            'portfolioDemos' => $portfolioDemos,
            'team'           => $team,
            'cms'            => $cms
        ]);
    }

    /**
     * Public Zoom Consultation Booking Handler
     */
    public function bookConsultation() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $serviceType = trim($_POST['service_type'] ?? 'Web Architecture');
        $preferredDate = trim($_POST['preferred_date'] ?? date('Y-m-d', strtotime('+1 day')));
        $preferredTime = trim($_POST['preferred_time'] ?? '10:00 AM');
        $notes = trim($_POST['notes'] ?? '');

        if (empty($name) || empty($email)) {
            $this->session->flash('error', 'Please provide your name and email address to book a consultation.');
            redirect('/#consultation');
            return;
        }

        $zoomLink = 'https://zoom.us/j/' . rand(9000000000, 9999999999) . '?pwd=tektrend_consult_' . substr(md5(uniqid()), 0, 8);
        $meetingId = substr(chunk_split((string)rand(1000000000, 9999999999), 3, '-'), 0, -1);

        // Insert Consultation
        $consultId = $this->db->insert(
            "INSERT INTO consultations (name, email, phone, company, service_type, preferred_date, preferred_time, duration_minutes, zoom_link, meeting_id, status, notes) 
             VALUES (?, ?, ?, ?, ?, ?, ?, 45, ?, ?, 'confirmed', ?)",
            [$name, $email, $phone, $company, $serviceType, $preferredDate, $preferredTime, $zoomLink, $meetingId, $notes]
        );

        // Auto-create lead in CRM
        $this->db->insert(
            "INSERT INTO leads (source_id, assigned_to, first_name, last_name, email, phone, company, value, status, priority, notes, next_followup) 
             VALUES (1, 3, ?, '', ?, ?, ?, 5000.00, 'contacted', 'urgent', ?, ?)",
            [$name, $email, $phone, $company, "Booked Zoom session for $serviceType. Notes: $notes", $preferredDate . ' ' . date('H:i:s', strtotime($preferredTime))]
        );

        $this->session->flash('success', "🎉 Your Zoom Consultation session has been booked for $preferredDate at $preferredTime! Meeting ID: $meetingId. Our principal consultant will meet you online.");
        redirect('/#consultation');
    }

    /**
     * Public Design Bidding & Buy Now Handler
     */
    public function placeBid() {
        $itemId = (int)($_POST['design_item_id'] ?? 0);
        $bidderName = trim($_POST['bidder_name'] ?? '');
        $bidderEmail = trim($_POST['bidder_email'] ?? '');
        $bidderPhone = trim($_POST['bidder_phone'] ?? '');
        $bidAmount = (float)($_POST['bid_amount'] ?? 0);
        $message = trim($_POST['message'] ?? '');
        $isBuyNow = isset($_POST['buy_now']) && $_POST['buy_now'] == '1';

        $item = $this->db->fetch("SELECT * FROM design_items WHERE id = ? AND status = 'active' LIMIT 1", [$itemId]);

        if (!$item) {
            $this->session->flash('error', 'Design item not found or bidding has closed.');
            redirect('/#marketplace');
            return;
        }

        if (empty($bidderName) || empty($bidderEmail)) {
            $this->session->flash('error', 'Please enter your name and email to submit a bid.');
            redirect('/#marketplace');
            return;
        }

        if ($isBuyNow) {
            $bidAmount = $item['buy_now_price'];
        } else {
            if ($bidAmount <= $item['current_bid']) {
                $this->session->flash('error', 'Your bid must be greater than the current highest bid ($' . number_format($item['current_bid'], 2) . ').');
                redirect('/#marketplace');
                return;
            }
        }

        // Insert Bid
        $this->db->insert(
            "INSERT INTO design_bids (design_item_id, bidder_name, bidder_email, bidder_phone, bid_amount, message, status) 
             VALUES (?, ?, ?, ?, ?, ?, 'pending')",
            [$itemId, $bidderName, $bidderEmail, $bidderPhone, $bidAmount, $message]
        );

        // Update item current bid and count
        $newStatus = $isBuyNow ? 'sold' : 'active';
        $this->db->execute(
            "UPDATE design_items SET current_bid = ?, total_bids = total_bids + 1, status = ? WHERE id = ?",
            [$bidAmount, $newStatus, $itemId]
        );

        // Auto-create lead in CRM for sales rep follow-up
        $this->db->insert(
            "INSERT INTO leads (source_id, assigned_to, first_name, last_name, email, phone, company, value, status, priority, notes) 
             VALUES (3, 3, ?, '', ?, ?, 'Design Marketplace Bidder', ?, 'proposal', 'high', ?)",
            [$bidderName, $bidderEmail, $bidderPhone, $bidAmount, "Placed bid on '{$item['title']}' for " . formatCurrency($bidAmount) . ". Client message: $message"]
        );

        if ($isBuyNow) {
            $this->session->flash('success', "🚀 Congratulations! You initiated Buy Now for '{$item['title']}' at " . formatCurrency($bidAmount) . ". Our design team has reserved the code repository and will email the handover pack!");
        } else {
            $this->session->flash('success', "🔥 Bid of " . formatCurrency($bidAmount) . " successfully placed on '{$item['title']}'! You are currently the highest bidder.");
        }

        redirect('/#marketplace');
    }

    /**
     * Public AI Chatbot API Endpoint
     */
    public function apiAiChat() {
        header('Content-Type: application/json');
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true) ?: $_POST;
        $message = strtolower(trim($input['message'] ?? ''));

        if (empty($message)) {
            echo json_encode(['reply' => "Hello! I am TekTrend's AI Solutions Architect. Ask me about our software consulting, custom PHP systems, Google Workspace automation, Awwwards design bidding, or book a live Zoom session!"]);
            exit;
        }

        $reply = "";
        if (str_contains($message, 'price') || str_contains($message, 'cost') || str_contains($message, 'quote') || str_contains($message, 'rate')) {
            $reply = "💡 **Instant Estimate Guide**: Custom Enterprise Web Apps range from KSh 150,000 to KSh 850,000 depending on complexity. Our Awwwards-caliber design templates start at KSh 65,000 on live bid. Consulting retainers start at KSh 120,000/mo. Would you like to schedule a free 45-min Zoom consultation to scope your project?";
        } elseif (str_contains($message, 'zoom') || str_contains($message, 'consult') || str_contains($message, 'book') || str_contains($message, 'meet')) {
            $reply = "📅 **Schedule a Zoom Session**: You can book directly with David (CEO) or Alex (Chief Architect) right on this page! Simply scroll to the Consultation section, pick your preferred date and 45-minute slot, and you'll receive your instant Zoom link.";
        } elseif (str_contains($message, 'whatsapp') || str_contains($message, 'call') || str_contains($message, 'phone') || str_contains($message, 'contact')) {
            $reply = "📱 **WhatsApp Quick Connect**: You can chat directly with our engineering team on WhatsApp at **+254 707 246 273** or click the green WhatsApp button in the corner for instant response.";
        } elseif (str_contains($message, 'bid') || str_contains($message, 'marketplace') || str_contains($message, 'awwwards') || str_contains($message, 'template') || str_contains($message, 'buy')) {
            $reply = "🏆 **Design Marketplace**: We showcase exclusive, award-winning website systems open for live bidding. Check out 'Apex Luxury Real Estate' and 'Horizon Cloud SaaS' in our Marketplace section to place a bid or choose Buy Now!";
        } elseif (str_contains($message, 'tech') || str_contains($message, 'stack') || str_contains($message, 'php') || str_contains($message, 'google') || str_contains($message, 'ai')) {
            $reply = "⚡ **Our Core Tech Specializations**: High-performance PHP 8.x MVC, Google Apps Script & Sheets Enterprise ERP, MySQL/MariaDB database architecture, WebRTC teleconferencing, and custom OpenAI/LLM API integrations.";
        } else {
            $reply = "Thank you for reaching out! Tek Trend Innovations delivers high-impact software systems, AI automation, and Awwwards-standard digital experiences. Would you like to book a 1-on-1 Zoom consultation or explore our live design marketplace?";
        }

        echo json_encode([
            'reply'     => $reply,
            'timestamp' => date('H:i')
        ]);
        exit;
    }
}
