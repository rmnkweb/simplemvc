<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/app/models/AbstractPass.php";

class PassOuter extends AbstractPass {

    public function __construct() {
        parent::__construct();

        $this->tablename = "PassOuter";
    }

}