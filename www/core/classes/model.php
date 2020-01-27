<?php

class Model {

    protected $db;
    protected $tablename;

    function __construct () {

        global $app;

        $this->db = $app->db;

        // print_r($this->db);
        // print_r($app->db);

    }

}