<?php

class MainController extends Controller {

    function defaultAction() {
        header("Location: /pass/");
        die();
    }

}
