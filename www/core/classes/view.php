<?php

class View {

    function render($path, $data = []) {

        if (is_array($data))
            extract($data, EXTR_PREFIX_ALL, "viewdata");

        require(ROOT . '/app/views/' . $path . '.php');

    }

    function checkPermission($group = false) {
        if ((isset($_SESSION['login'])) AND ($group !== false) AND ($group === $_SESSION['login'])) {
            return true;
        } else {
            return false;
        }
    }
}