<?php
/**
 * View Renderer
 * Simple template rendering with layout support
 */

class View {
    private $data = [];
    private $layout = 'layouts/app';

    /**
     * Set layout
     */
    public function setLayout($layout) {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Render a view with data
     */
    public function render($view, $data = []) {
        $this->data = $data;

        // Extract data to variables
        extract($data, EXTR_SKIP);

        // Start output buffering
        ob_start();

        // Render view content
        $viewFile = VIEW_PATH . '/' . $view . '.php';
        if (!file_exists($viewFile)) {
            ob_end_clean();
            throw new Exception("View file not found: {$view}");
        }

        include $viewFile;
        $content = ob_get_clean();

        // Render layout if set
        if ($this->layout) {
            $layoutFile = VIEW_PATH . '/' . $this->layout . '.php';
            if (file_exists($layoutFile)) {
                ob_start();
                include $layoutFile;
                $layoutContent = ob_get_clean();
                echo $layoutContent;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    /**
     * Render a view without layout (for AJAX)
     */
    public function renderRaw($view, $data = []) {
        $this->data = $data;
        extract($data, EXTR_SKIP);

        $viewFile = VIEW_PATH . '/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: {$view}");
        }

        include $viewFile;
    }

    /**
     * Get data
     */
    public function getData() {
        return $this->data;
    }
}
