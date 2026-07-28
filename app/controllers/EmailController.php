<?php
/**
 * Email Marketing Controller
 * Manage email campaigns, subscribers
 */

class EmailController extends Controller {

    public function index() {
        $this->requireAuth();
        $campaigns = $this->db->fetchAll(
            "SELECT ec.*, u.first_name, u.last_name FROM email_campaigns ec LEFT JOIN users u ON ec.created_by = u.id ORDER BY ec.created_at DESC"
        );
        $totalSubscribers = $this->db->fetchColumn("SELECT COUNT(*) FROM email_subscribers WHERE status = 'active'");
        $totalSent = $this->db->fetchColumn("SELECT COALESCE(SUM(total_sent), 0) FROM email_campaigns");
        $this->render('emails/index', ['campaigns' => $campaigns, 'totalSubscribers' => $totalSubscribers, 'totalSent' => $totalSent]);
    }

    public function create() {
        $this->requireAuth();
        $subscribers = $this->db->fetchAll("SELECT id, email, first_name, last_name FROM email_subscribers WHERE status = 'active' ORDER BY first_name");
        $this->render('emails/create', ['subscribers' => $subscribers]);
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();
        $rules = ['name' => ['required' => true, 'label' => 'Campaign Name'], 'subject' => ['required' => true, 'label' => 'Subject']];
        $validation = $this->validatePost($rules);
        if (!$validation['valid']) { $this->session->flash('error', reset($validation['errors'])); $this->session->flash('old', $_POST); redirect('/emails/create'); }
        $data = $validation['data'];
        $campaignId = $this->db->insert(
            "INSERT INTO email_campaigns (name, subject, content_html, content_text, from_name, from_email, status, scheduled_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['name'], $data['subject'], $_POST['content_html'] ?? null, $_POST['content_text'] ?? null, $_POST['from_name'] ?? 'Tek Trend', $_POST['from_email'] ?? 'info@tektrend.com', $_POST['status'] ?? 'draft', $_POST['scheduled_at'] ?? null, $this->auth->id()]
        );
        auditLog('create', 'email_campaigns', $campaignId, 'Created campaign: ' . $data['name']);
        $this->redirectWithSuccess('/emails', 'Email campaign created successfully!');
    }

    public function show($id) {
        $this->requireAuth();
        $campaign = $this->db->fetch("SELECT * FROM email_campaigns WHERE id = ?", [$id]);
        if (!$campaign) { $this->session->flash('error', 'Campaign not found.'); redirect('/emails'); }
        $sends = $this->db->fetchAll(
            "SELECT es.*, s.email, s.first_name, s.last_name FROM email_sends es LEFT JOIN email_subscribers s ON es.subscriber_id = s.id WHERE es.campaign_id = ? ORDER BY es.sent_at DESC LIMIT 50",
            [$id]
        );
        $this->render('emails/show', ['campaign' => $campaign, 'sends' => $sends]);
    }

    public function send($id) {
        $this->requireAuth();
        $campaign = $this->db->fetch("SELECT * FROM email_campaigns WHERE id = ?", [$id]);
        if (!$campaign) { $this->session->flash('error', 'Campaign not found.'); redirect('/emails'); }
        $subscribers = $this->db->fetchAll("SELECT id, email FROM email_subscribers WHERE status = 'active'");
        $sentCount = 0;
        foreach ($subscribers as $sub) {
            $this->db->insert(
                "INSERT INTO email_sends (campaign_id, subscriber_id, status, sent_at) VALUES (?, ?, 'sent', NOW())",
                [$id, $sub['id']]
            );
            $sentCount++;
        }
        $this->db->execute("UPDATE email_campaigns SET status = 'sent', sent_at = NOW(), total_recipients = ?, total_sent = ? WHERE id = ?", [count($subscribers), $sentCount, $id]);
        auditLog('send', 'email_campaigns', $id, 'Sent campaign to ' . $sentCount . ' subscribers');
        $this->redirectWithSuccess("/emails/$id", 'Campaign sent to ' . $sentCount . ' subscribers!');
    }

    public function subscribers() {
        $this->requireAuth();
        $search = $_GET['search'] ?? '';
        $where = $search ? "WHERE email LIKE ? OR first_name LIKE ? OR last_name LIKE ?" : '';
        $params = $search ? ["%$search%", "%$search%", "%$search%"] : [];
        $total = $this->db->fetchColumn("SELECT COUNT(*) FROM email_subscribers " . ($where ? "WHERE " . substr($where, 6) : ''), $params);
        $pagination = $this->getPagination($total);
        $subscribers = $this->db->fetchAll(
            "SELECT * FROM email_subscribers $where ORDER BY subscribed_at DESC LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}",
            $params
        );
        $this->render('emails/subscribers', ['subscribers' => $subscribers, 'pagination' => $pagination, 'search' => $search]);
    }

    public function addSubscriber() {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->db->insert(
            "INSERT INTO email_subscribers (email, first_name, last_name, source, status) VALUES (?, ?, ?, 'manual', 'active')",
            [$_POST['email'], $_POST['first_name'] ?? null, $_POST['last_name'] ?? null]
        );
        $this->redirectWithSuccess('/emails/subscribers', 'Subscriber added successfully!');
    }
}
