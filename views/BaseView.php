<?php
/**
 * The BaseView class represents a base view in the application.
 *
 * @abstract
 */
abstract class BaseView
{

    /**
     * @var string $template The template file name
     */
    protected $template;

    /**
     * @var array $data An associative array of data to be used in the view
     */
    protected $data = array();

    /**
     * Constructor method to create a new instance of BaseView.
     *
     * @param string $template The template file name
     */
    public function __construct($template)
    {
        $this->template = $template;
    }

    /**
     * Set data for the view.
     *
     * @param array $data An associative array of data to be used in the view
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Render the view.
     *
     * @throws Exception If the template file is not found
     */
    public function render()
    {
        $path = 'templates/' . $this->template . '.php';

        if (file_exists($path)) {
            $_data = $this->data;
            ob_start();
            include($path);
            $content = ob_get_clean();
            $this->renderLayout($content);
        } else {
            throw new Exception('Template ' . $this->template . ' not found!');
        }
    }

    /**
     * Render the layout file.
     *
     * @param string $content The content to be included in the layout
     *
     * @throws Exception If the layout file is not found
     */
    protected function renderLayout($content)
    {
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