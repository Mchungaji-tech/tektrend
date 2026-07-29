<?php
/**
 * Home Controller
 * Serves the landing page
 */
class HomeController extends Controller {
    /**
     * Display the landing page
     */
    public function index() {
        if (Auth::check()) {
            $this->redirect('/dashboard');
            return;
        }

        $settings = [];
        $rows = $this->db->fetchAll("SELECT `key`, `value` FROM settings WHERE `group` IN ('general', 'email')");
        foreach ($rows as $row) {
            $settings[$row['key']] = $row;
        }

        $this->view('home/index', ['settings' => $settings]);
    }
}
