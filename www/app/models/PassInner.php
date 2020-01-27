<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/app/models/AbstractPass.php";

class PassInner extends AbstractPass {

    public function __construct() {
        parent::__construct();

        $this->tablename = "PassInner";
    }

}