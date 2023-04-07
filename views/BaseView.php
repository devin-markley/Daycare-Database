<?php

abstract class BaseView {
    protected $template;
    protected $data = array();

    public function __construct($template) {
        $this->template = $template;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function render() {
        $path = 'templates/' . $this->template . '.php';

        if (file_exists($path)) {
            extract($this->data);
            ob_start();
            include($path);
            $content = ob_get_clean();
            $this->renderLayout($content);
        } else {
            throw new Exception('Template ' . $this->template . ' not found!');
        }
    }

    protected function renderLayout($content) {
        $path = 'templates/BaseTemplate.php';

        if (file_exists($path)) {
            extract($this->data);
            include($path);
        } else {
            throw new Exception('Template not found!');
        }
    }
}

?>