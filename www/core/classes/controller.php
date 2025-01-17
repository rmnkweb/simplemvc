<?php

class Controller {

    public $view;
    public $model;
    public $request;

    function __construct() {

        $this->view = new View();

        $params = [];
        if (!empty($_POST)) {
            foreach ($_POST as $key => $item) {
                $params[$key] = $item;
            }
        } elseif (!empty($_GET)) {
            foreach ($_GET as $key => $item) {
                $params[$key] = $item;
            }
        }
        if (!empty($_FILES)) {
            foreach ($_FILES as $key => $item) {
                $params["files"][$key] = $item;
            }
        }
        $this->request = $params;
    }

    function setModel($modelName) {

        $path = ROOT . '/app/models/' . $modelName . '.php';

        if (file_exists($path)) {
            require_once(ROOT . '/app/models/' . $modelName . '.php');
            $this->model = new $modelName();
        }
    }

    function checkAuth() {
        if (!isset($_SESSION['login'])) {
            header('Location: /authorization/');
            die();
        }
    }

    function checkPermission($group = false) {
        if ((isset($_SESSION['login'])) AND ($group !== false) AND ($group != $_SESSION['login'])) {
            return true;
        } else {
            return false;
        }
    }
}