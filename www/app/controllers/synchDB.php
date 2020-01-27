<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/app/models/PassOuter.php";

class SynchDBController extends Controller {

    function defaultAction() {
        $synchDBData = $this->model->getData();

        if ($synchDBData) {
            $values = [];
            foreach ($synchDBData as $row) {
                if (isset($row["Id"])) {
                    $values[] = [
                        "State" => $row["State"],
                        "Priority" => $row["Priority"],
                        "Title" => $row["Title"],
                        "Service" => $row["Service"],
                        "Creator" => $row["Creator"],
                        "Executors" => $row["Executors"],
                        "Changed" => $row["Changed"],
                        "Created" => $row["Created"],
                        "Arrive" => $row["Arrive"],
                        "Depart" => $row["Depart"],
                        "Objects" => $row["Objects"],
                        "Cars" => $row["Cars"],
                    ];
                }
            }

            $passOuter = new PassOuter();
            $passOuter->clear();
            $passOuter->addValues($values);
        }
    }
}