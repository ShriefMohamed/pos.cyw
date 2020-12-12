<?php


namespace Framework\lib;


class Template
{
    private $controller;
    private $action;
    private $title;
    private $view;
    private $views = array();
    public $data = array();

    public function __construct($controller, $action)
    {
        if ($action == FrontController::NOT_FOUND_ACTION) {
            $this->view = VIEWS_PATH . 'notfound' . DS . 'notfound';
            $action = 'notfound';
        } else {
            $this->view = VIEWS_PATH . strtolower($controller) . DS . strtolower($action);
        }

        $this->controller = $controller;
        $this->action = $action;

        $title_action = $action == 'default' ? 'Home' : ucwords(str_replace('_', ' ', $action));
        $this->title = ($controller == 'index' && $action == 'default') ?
            WEBSITE_NAME :
            WEBSITE_NAME . ' | ' . $title_action;
    }

    public function SetData($data): Template
    {
        $this->data = $data;
        return $this;
    }

    public function SetViews($views): Template
    {
        $this->views = $views;
        return $this;
    }


    public function RenderPart($part)
    {
        if ($part) {
            require_once  PARTIALS_PATH . $part . '.php';
        }
    }

    public function Render($only_view = false)
    {
        if (!empty($this->views)) {
            if ($this->data) {extract($this->data);}

            if ($only_view == false) {
                require_once TEMPLATE_PATH . 'head.php';
            }
            foreach ($this->views as $value) {
                if ($value !== 'view') {
                    if (file_exists(TEMPLATE_PATH . $value . '.php')) {
                        require_once TEMPLATE_PATH . $value . '.php';
                    }
                } else {
                    if (file_exists($this->view . '.php')) {
                        require_once $this->view . '.php';
                    } else {
                        require_once VIEWS_PATH . 'notfound' . DS . 'notfound.php';
                    }
                }
            }
            if ($only_view == false) {
                require_once TEMPLATE_PATH . 'footer.php';
            }
        }
    }
}