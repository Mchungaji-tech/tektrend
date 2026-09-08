<?php
/**
 * Marketplace Controller
 * Manage Awwwards-style design items, auction bidding and sales
 */

class MarketplaceController extends Controller {

    public function index() {
        $this->requireAuth();
        $items = $this->db->fetchAll(
            "SELECT * FROM design_items ORDER BY created_at DESC"
        );

        $totalBids = $this->db->fetchColumn("SELECT COUNT(*) FROM design_bids");
        $activeItems = $this->db->fetchColumn("SELECT COUNT(*) FROM design_items WHERE status = 'active'");

        $this->render('marketplace/index', [
            'pageTitle'   => 'Design Marketplace',
            'items'       => $items,
            'totalBids'   => $totalBids,
            'activeItems' => $activeItems
        ]);
    }

    public function create() {
        $this->requireAuth();
        $this->render('marketplace/create', ['pageTitle' => 'Add Design Item']);
    }

    public function store() {
        $this->requireAuth();
        $this->verifyCsrf();

        $rules = [
            'title'         => ['required' => true, 'label' => 'Design Title'],
            'category'      => ['required' => true, 'label' => 'Category'],
            'starting_bid'  => ['required' => true, 'numeric' => true, 'label' => 'Starting Bid'],
            'buy_now_price' => ['required' => true, 'numeric' => true, 'label' => 'Buy Now Price']
        ];

        $val = $this->validatePost($rules);
        if (!$val['valid']) {
            $this->session->flash('error', reset($val['errors']));
            redirect('/marketplace/create');
        }

        $title = $val['data']['title'];
        $slug = slugify($title) . '-' . rand(100, 999);
        $startingBid = (float)$val['data']['starting_bid'];
        $buyNowPrice = (float)$val['data']['buy_now_price'];

        $this->db->insert(
            "INSERT INTO design_items (title, slug, category, award_badge, description, short_description, image, demo_url, sale_type, starting_bid, current_bid, buy_now_price, bid_end_date, status, created_by) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', ?)",
            [
                $title, $slug, $val['data']['category'],
                $_POST['award_badge'] ?? 'Site of the Day',
                $_POST['description'] ?? null,
                $_POST['short_description'] ?? null,
                $_POST['image'] ?? 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80',
                $_POST['demo_url'] ?? null,
                $_POST['sale_type'] ?? 'both',
                $startingBid, $startingBid, $buyNowPrice,
                $_POST['bid_end_date'] ?? date('Y-m-d H:i:s', strtotime('+7 days')),
                $this->auth->id()
            ]
        );

        auditLog('create_design_item', 'design_items', null, "Created design item $title");
        $this->redirectWithSuccess('/marketplace', 'Design template published for live bidding!');
    }

    public function edit($id) {
        $this->requireAuth();
        $item = $this->db->fetch("SELECT * FROM design_items WHERE id = ?", [$id]);
        if (!$item) {
            $this->session->flash('error', 'Design item not found.');
            redirect('/marketplace');
        }

        $this->render('marketplace/edit', [
            'pageTitle' => 'Edit Design Item',
            'item'      => $item
        ]);
    }

    public function update($id) {
        $this->requireAuth();
        $this->verifyCsrf();

        $this->db->execute(
            "UPDATE design_items SET title = ?, category = ?, award_badge = ?, description = ?, short_description = ?, image = ?, demo_url = ?, sale_type = ?, starting_bid = ?, current_bid = ?, buy_now_price = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [
                $_POST['title'],
                $_POST['category'],
                $_POST['award_badge'] ?? 'Site of the Day',
                $_POST['description'] ?? null,
                $_POST['short_description'] ?? null,
                $_POST['image'] ?? null,
                $_POST['demo_url'] ?? null,
                $_POST['sale_type'] ?? 'both',
                $_POST['starting_bid'] ?? 0,
                $_POST['current_bid'] ?? 0,
                $_POST['buy_now_price'] ?? 0,
                $_POST['status'] ?? 'active',
                $id
            ]
        );

        auditLog('update_design_item', 'design_items', $id, "Updated design item $id");
        $this->redirectWithSuccess('/marketplace', 'Design item updated successfully!');
    }

    public function bids() {
        $this->requireAuth();
        $bids = $this->db->fetchAll(
            "SELECT b.*, d.title as design_title, d.award_badge, d.image 
             FROM design_bids b 
             LEFT JOIN design_items d ON b.design_item_id = d.id 
             ORDER BY b.created_at DESC"
        );

        $this->render('marketplace/bids', [
            'pageTitle' => 'Client Design Bids',
            'bids'      => $bids
        ]);
    }

    public function updateBidStatus($id) {
        $this->requireAuth();
        $this->verifyCsrf();
        $status = $_POST['status'] ?? 'accepted';
        $this->db->execute("UPDATE design_bids SET status = ? WHERE id = ?", [$status, $id]);
        auditLog('update_bid', 'design_bids', $id, "Updated bid status to $status");
        $this->redirectWithSuccess('/marketplace/bids', 'Bid status updated!');
    }

    public function delete($id) {
        $this->requireAuth();
        $this->db->execute("DELETE FROM design_items WHERE id = ?", [$id]);
        $this->db->execute("DELETE FROM design_bids WHERE design_item_id = ?", [$id]);
        auditLog('delete_design_item', 'design_items', $id, 'Deleted design item and associated bids');
        $this->redirectWithSuccess('/marketplace', 'Design item deleted.');
    }
}
