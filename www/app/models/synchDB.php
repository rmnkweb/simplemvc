<?php

class SynchDB extends Model {

    function __construct () {
        $filepath = ROOT . "/db/AlconIntraservice.db3";
        file_put_contents($filepath, fopen("http://test4.h807036968.nichost.ru/AlconIntraservice.db3", 'r'));
        $db = new SQLite3($filepath);
        $this->db = $db;
    }

    public function getData() {
        if ($query = $this->db->query("SELECT * FROM TaskInfos WHERE state = \"Открыта\"")) {
            $result = [];
            while ($row = $query->fetchArray(1)) {
                if (isset($row["Id"])) {
                    $result[] = $row;
                }
            }
        } else {
            $result = false;
        }

        return $result;
    }
}