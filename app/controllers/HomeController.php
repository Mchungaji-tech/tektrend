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
        // If user is already logged in, redirect to dashboard
        if (Auth::check()) {
            $this->redirect('/dashboard');
            return;
        }
        // Render the landing page (no auth layout)
        $this->view('home/index');
    }
}
