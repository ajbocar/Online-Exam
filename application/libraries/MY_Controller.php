<?php
class MY_Controller extends Controller {
    
    private $view = array();

    private $view_path = '';

    public function __construct() {
        parent::__construct();
        $this->init();
    }

    /**
     * Override this method in child controllers to call initialization 
     * routines.
     */
    public function init() {}

    /**
     * Set a view variable.
     *
     * @param string $variable Name of the variable.
     * @param mixed $value Variable value.
     */
    public function set($variable, $value) {
        if (!in_array($variable, array('page_title', 'view'))) {
            $this->view[$variable] = $value;
        }
    }

    /**
     * It sets the view path. Useful to call it on the init() method.
     * Do NOT put a trailing slash.
     *
     * @param string $path The path.
     */
    public function set_view_path($path) {
        $this->view_path = $path;
    }

    /**
     * Renders a view. 
     *
     * @param string $title The page title.
     * @param string $view Filename of the view without extension. It defaults
     *                     to the name of the current controller action.
     * @param boolean $return If true, returns the translated viewg, otherwise
     *                        just renders the view normally.
     */
    public function render($title, $view='', $return=false) {
        $this->view['page_title'] = $title;

        $view = empty($view) ? $this->router->fetch_method() : $view;
        $this->view['view'] = $this->view_path . '/' .$view;

        $this->view['_front']  = $this->view_path;
        $this->view['_action'] = $this->router->fetch_method();
        
        $this->renderme();

        $output = $this->load->view(
            'layout', $this->view, $return
        );

        return $output;
    }

    /**
     * Override this method for custom view initializations that need to be
     * done all the time. Perhaps not as useful as I thought.
     */
    public function renderme() {}

    /**
     * Info discovery method.  It's not that pretty. When inspecting CI objects
     * you can get a lot of recursion. :S
     *
     * @param Object $object The object to be inspected.
     */
    public function inspect($object) {
        $methods = get_class_methods($object);
        $vars    = get_class_vars(get_class($object));
        $ovars   = get_object_vars($object);
        $parent  = get_parent_class($object);

        $output  = 'Parent class: ' . $parent . "\n\n";
        $output .= "Methods:\n";
        $output .= "--------\n";
        foreach ($methods as $method) {
            $meth = new ReflectionMethod(get_class($object), $method);
            $output .= $method . "\n";
            $output .= $meth->__toString();
        }

        $output .= "\nClass Vars:\n";
        $output .= "-----------\n";
        foreach ($vars as $name => $value) {
            $output .= $name . ' = ' . print_r($value, 1) . "\n";
        }

        $output .= "\nObject Vars:\n";
        $output .= "------------\n";
        foreach ($ovars as $name => $value) {
            $output .= $name . ' = ' . print_r($value, 1) . "\n";
        }

        echo '<pre>', $output, '</pre>';
    }
}