<?php

class Error404Controller extends Controller {

    function defaultAction() {
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        echo "404";
    }

}