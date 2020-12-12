<?php


namespace Framework\Lib;


use ArgumentCountError;
use Framework\models\UsersModel;
use Matrix\Exception;

class FrontController
{
	private $_controller = 'index';
	private $_action = 'default';
	private $_params = array();

	const NOT_FOUND_CONTROLLER = 'Framework\Controllers\NotFoundController';
	const NOT_FOUND_ACTION =     'NotFoundAction';
	
	public function __construct() 
	{
		// here the constructor will call the functions written down so it init them automatically
		$this->ParseUrl();
		$this->Dispatch();
	}


	##### ParseUrl ##########
	// Parameters :- None
	// Return Type :- None
	//Purpose :- get the url and cut it to pieces and use it in the dispatch function
	// the first pieces of the url we will call it the controller which is a php class
	// and the second one will be the action or the function at the controller (class)
	// and the last part will be the parameters and it will be an array
	// so when the url for example is: www.x.com/index/post/5
	// this means: the index class (controller), 
	// and the post function (action) and the parameter is 5 which in this
	// case the id of the post we want to display
	// and notice that if the url is empty, like: www.x.com
	// then we don't have any thing to cut and call, so in this case we already have a default value
	// for the class and action that should be used in this case
	// these dafault values are (index controller and default action)
	###########################
	private function ParseUrl()
	{
		$url = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), 3);

		if (isset($url[0]) AND $url[0] != '') {
			$this->_controller = $url[0];
		}
		if (isset($url[1]) AND $url[1] != '') {
			$this->_action = $url[1];
		}
		if (isset($url[2]) AND $url[2] != '') {
			$this->_params = explode('/', $url[2]);
		}
	}

	##### Dispatch ##########
	// Parameters :- None
	// Return Type :- None
	//Purpose :- get the pieces of the url and actually call it (require it).
	###########################
	private function Dispatch() 
	{
	    // Check if requested page is one of the protected pages which requires login such admin or pos etc,
        // then check if logged in & if the logged in user's role allowed to access that page, else redirect him to login
		if ($this->_controller == 'admin' || $this->_controller == 'pos' || $this->_controller == 'quotes' || $this->_controller == 'licenses') {
			if (!Session::Exists('loggedin') || Session::Get('loggedin')->role != 'admin') {
				$this->_controller = 'login';
				$this->_action = 'default';
			}
        } elseif ($this->_controller == 'customer') {
            if (!Session::Exists('loggedin') || Session::Get('loggedin')->role != 'customer') {
                $this->_controller = 'login';
                $this->_action = 'default';
            }
        } elseif ($this->_controller == 'technician') {
            if (!Session::Exists('loggedin') || Session::Get('loggedin')->role != 'technician') {
                $this->_controller = 'login';
                $this->_action = 'default';
            }
        } elseif ($this->_controller == 'login') {
            if (Session::Exists('loggedin')) {
                header("location: " . HOST_NAME . Session::Get('loggedin')->role);
            }
        }

        // Set last activity for admins
        if (Session::Exists('loggedin')) {
            $user = new UsersModel();
            $user->id = Session::Get('loggedin')->id;
            $user->lastSeen = date('Y-m-d h:i:s', SERVER_TIMESTAMP);
            $user->Save();
        }

		$controllerClassName = 'Framework\Controllers\\' . ucfirst($this->_controller) . 'Controller';

		// for the frontcontroller to be able to call a function at the controller automaticly just by spliting the url
		// we have to add Action after the function/action name
		// the DefaultAction get called if the url is: www.x.com/index/default -> index: the controller class name, default: the action/function's name

		$actionName = ucfirst($this->_action) . 'Action';

		// check first if this controller class exists, if not then take him to the not found class
		if (!class_exists($controllerClassName)) {
			$controllerClassName = self::NOT_FOUND_CONTROLLER;
		} 

		// if everything is ok so far then initiate the class
        // inside of the class that got initiated set these variables (the name on the controller and action and parameters)
        // in case of we needed to use them there and of course inside the controller class we don't have access
        // to this class so this is the perfect way to parse these values there
		$controller = new $controllerClassName($this->_controller, $this->_action, $this->_params);

		// check if the function (action) exists in the initiated class, if not then display 404 not found
		if (!method_exists($controller, $actionName)) {
			$this->_action = $actionName = self::NOT_FOUND_ACTION;
		}

		// finally call the function (action) at the class (controller) that got initiated
        try {
            $controller->$actionName(...$this->_params);
        } catch (ArgumentCountError $e) {
            Redirect::NotFound();
        }

		// now we called a class and also called an action in that class so this file will now go to the 
		// called class and action.
	}
}

?>