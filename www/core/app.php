<?php


class App {

    private $config = [];

    public $db;
    public $controller;
    public $action;

    function __construct () {
        define("URI", $_SERVER['REQUEST_URI']);
        define("ROOT", $_SERVER['DOCUMENT_ROOT']);
    }

    function autoload () {
        spl_autoload_register(function ($class) {
            $class = strtolower($class);
            if (file_exists(ROOT . '/core/classes/' . $class . '.php')) {
                require_once ROOT . '/core/classes/' . $class . '.php';
            }
        });
    }

    function config () {
        require ROOT . '/core/config/session.php';
        require ROOT . '/core/config/database.php';

        try {
            $this->db = new PDO('mysql:host=' . $this->config['database']['hostname'] . ';dbname=' . $this->config['database']['dbname'],
                                $this->config['database']['username'], 
                                $this->config['database']['password'],
                                [
                                    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
                                ]
            );

            $this->db->query('SET NAMES utf8');
            $this->db->query('SET CHARACTER SET utf8');

            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Database Error: ' . $e->getMessage();
        }

    }

    function session() {
        session_start();
        session_name($this->config['sessionName']);
    }

    function routing() {
        $actionName = "defaultAction";

        $route = explode('/', URI);

        if (count($route) > 2) {
            // getting controller
            if (file_exists(ROOT . '/app/controllers/' . $route[1] . '.php')) {
                require_once ROOT . '/app/controllers/' . $route[1] . '.php';
                $controllerName = $route[1]."Controller";
                $this->controller = new $controllerName();
            } else {
                require_once ROOT . '/app/controllers/error404.php';
                $this->controller = new Error404Controller();
            }

            // getting action
            if ((count($route) > 3) AND (!empty($route[2]))) {
                $actionName = $route[2] . "Action";
            }
            if (method_exists($this->controller, $actionName) && is_callable(array($this->controller, $actionName))) {
                $this->action = $actionName;
                // $this->controller->setModel($route[1]);
            } else {
                $actionName = "defaultAction";
                // echo "No action";
            }
        } else {
            require_once ROOT . '/app/controllers/main.php';
            $this->controller = new MainController();
        }

        $this->controller->$actionName();
    }
    
}