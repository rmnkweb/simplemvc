<?php

class AbstractPass extends Model {

    private $searchFields = [
        [
            "title" => "Title",
            "type" => "string"
        ],
        [
            "title" => "Service",
            "type" => "string"
        ],
        [
            "title" => "Creator",
            "type" => "string"
        ],
        [
            "title" => "Objects",
            "type" => "string"
        ],
        [
            "title" => "Cars",
            "type" => "string"
        ],
    ];

    public function addValues($valuesArray) {
        $queryValues = "";
        foreach ($valuesArray as $values) {
            if ((isset($values["State"])) &&
                (isset($values["Priority"])) &&
                (isset($values["Title"])) &&
                (isset($values["Service"])) &&
                (isset($values["Creator"])) &&
                (isset($values["Executors"])) &&
                (isset($values["Changed"])) &&
                (isset($values["Created"])) &&
                (isset($values["Arrive"])) &&
                (isset($values["Depart"])) &&
                (isset($values["Objects"])) &&
                (isset($values["Cars"]))) {
                $State = $this->prepareValue($values["State"]);
                $Priority = $this->prepareValue($values["Priority"]);
                $Title = $this->prepareValue($values["Title"]);
                $Service = $this->prepareValue($values["Service"]);
                $Creator = $this->prepareValue($values["Creator"]);
                $Executors = $this->prepareValue($values["Executors"]);
                $Changed = $this->prepareValue($values["Changed"]);
                $Created = $this->prepareValue($values["Created"]);
                $Arrive = $this->prepareValue($values["Arrive"]);
                $Depart = $this->prepareValue($values["Depart"]);
                $Objects = $this->prepareValue($values["Objects"]);
                $Cars = $this->prepareValue($values["Cars"]);
                $queryValues .= "(\"$State\",\"$Priority\",\"$Title\",\"$Service\",\"$Creator\",\"$Executors\",\"$Changed\",\"$Created\",\"$Arrive\",\"$Depart\",\"$Objects\",\"$Cars\"), \n";
            }
        }
        $queryValues = substr_replace($queryValues, ";",  -3, -1);
        $queryStr = "INSERT INTO `" . $this->tablename . "` (`State`, `Priority`, `Title`, `Service`, `Creator`, `Executors`, `Changed`, `Created`, `Arrive`, `Depart`, `Objects`, `Cars`) VALUES " . $queryValues;

        // echo $queryStr;

        try{
            $this->db->query($queryStr);
        }
        catch(PDOException $exception){
            echo 'Database Error: ' . $exception->getMessage();
        }
    }

    private function prepareValue($value) {
        return htmlspecialchars(str_replace("\\", "/", $value));
    }

    public function clear() {
        if ($query = $this->db->query("TRUNCATE `" . $this->tablename . "`"))
            $result = true;
        else
            $result = false;
        return $result;
    }

    // params: $pagination = items per page; offset = pageNum*Pagination; $orderBy = db.table col name; $order = ASC/DESC; $filters = [["name", "value"], ...]
    public function getList($pagination = 10, $offset = 0, $orderBy = "id", $order = "ASC", $filters = [], $search = "") {
        $list = [];
        $dateNow = date('Y-m-d H:i:s', time());
        // $zeroDate = "0001-01-01 00:00:00";
        // $whereString = "WHERE (Arrive < '$dateNow' OR Arrive = '$zeroDate') AND (Depart > '$dateNow' OR Depart = '$zeroDate') AND active = 1 ";
        $whereString = "WHERE Arrive < '$dateNow' AND Depart > '$dateNow' AND active = 1 ";
        if (count($filters) > 0) {
            // $whereString = "WHERE ";
            foreach ($filters as $filter) {
                if ($whereString != "WHERE ") {
                    $whereString .= " AND ";
                }
                if ($filter["type"] === "string") {
                    $whereString .= $filter["name"] . " like " . "'%" . $filter["value"] . "%'";
                } else {
                    $whereString .= $filter["name"] . " = " . $filter["value"];
                }
            }
        }
        if ($search !== "") {
            $search = htmlspecialchars($search);
            $whereString .= " AND (";
            $i = 0;
            foreach ($this->searchFields as $field) {
                if ($i != 0) {
                    $whereString .= " OR ";
                } else {
                    $i++;
                }
                if ($field["type"] === "string") {
                    $whereString .= $field["title"] . " like '%$search%'";
                } else {
                    $whereString .= $field["title"] . " = $search";
                }
            }
            $whereString .= ")";
        }
        $statement = $this->db->prepare("SELECT * FROM `" . $this->tablename . "` $whereString ORDER BY $orderBy $order LIMIT :offset, :pagination");
        $statement->bindValue(':offset', intval($offset), PDO::PARAM_INT);
        $statement->bindValue(':pagination', intval($pagination), PDO::PARAM_INT);
        $statement->execute();
        $list = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $list;
    }

    // returns all values
    public function getListRaw($filters = []) {
        $list = [];
        $dateNow = date('Y-m-d H:i:s', time());
        // $zeroDate = "0001-01-01 00:00:00";
        // $whereString = "WHERE (Arrive < '$dateNow' OR Arrive = '$zeroDate') AND (Depart > '$dateNow' OR Depart = '$zeroDate') AND active = 1 ";
        $whereString = "WHERE Arrive < '$dateNow' AND Depart > '$dateNow' AND active = 1 ";
        if (count($filters) > 0) {
            // $whereString = "WHERE ";
            foreach ($filters as $filter) {
                if ($whereString != "WHERE ") {
                    $whereString .= " AND ";
                }
                $whereString .= $filter["name"] . " like " . "'%" . $filter["value"] . "%'";
            }
        }
        $statement = $this->db->prepare("SELECT * FROM `" . $this->tablename . "` $whereString ORDER BY id ASC");
        $statement->execute();
        $list = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $list;
    }

    public function getRowCount($filters = [], $search = "") {
        $dateNow = date('Y-m-d H:i:s', time());
        // $zeroDate = "0001-01-01 00:00:00";
        // $whereString = "WHERE (Arrive < '$dateNow' OR Arrive = '$zeroDate') AND (Depart > '$dateNow' OR Depart = '$zeroDate') AND active = 1 ";
        $whereString = "WHERE Arrive < '$dateNow' AND Depart > '$dateNow' AND active = 1 ";
        if (count($filters) > 0) {
            // $whereString = "WHERE ";
            foreach ($filters as $filter) {
                if ($whereString != "WHERE ") {
                    $whereString .= " AND ";
                }
                if ($filter["type"] === "string") {
                    $whereString .= $filter["name"] . " like " . "'%" . $filter["value"] . "%'";
                } else {
                    $whereString .= $filter["name"] . " = " . $filter["value"];
                }
            }
        }
        if ($search !== "") {
            $search = htmlspecialchars($search);
            $whereString .= " AND (";
            $i = 0;
            foreach ($this->searchFields as $field) {
                if ($i != 0) {
                    $whereString .= " OR ";
                } else {
                    $i++;
                }
                if ($field["type"] === "string") {
                    $whereString .= $field["title"] . " like '%$search%'";
                } else {
                    $whereString .= $field["title"] . " = $search";
                }
            }
            $whereString .= ")";
        }
        if ($query = $this->db->query("SELECT count(*) FROM `" . $this->tablename . "` $whereString"))
            $rowCount = $query->fetchColumn();
        else
            $rowCount = false;
        return $rowCount;
    }

    public function getDataById($id = null) {
        if ($id) {
            $statement = $this->db->prepare("SELECT * FROM `" . $this->tablename . "` WHERE id = :id");
            $statement->bindValue(':id', intval($id), PDO::PARAM_INT);
            $statement->execute();
            if ($data = $statement->fetchAll(PDO::FETCH_ASSOC))
                return $data[0];
            else
                return false;
        } else {
            return false;
        }
    }

    public function addItem($values = []) {
        $fieldsStr = "(";
        $valuesStr = "(";
        if ((isset($values["Priority"])) AND (!empty($values["Priority"]))) {
            $fieldsStr .= "`Priority`, ";
            if (is_numeric($values["Priority"])) {
                $valuesStr .= "" . $values["Priority"] . ", ";
            } else {
                $values["Priority"] = $this->prepareValue($values["Priority"]);
                $valuesStr .= "'" . $values["Priority"] . "', ";
            }
        }
        if ((isset($values["Title"])) AND (!empty($values["Title"]))) {
            $fieldsStr .= "`Title`, ";
            if (is_numeric($values["Title"])) {
                $valuesStr .= "" . $values["Title"] . ", ";
            } else {
                $values["Title"] = $this->prepareValue($values["Title"]);
                $valuesStr .= "'" . $values["Title"] . "', ";
            }
        }
        if ((isset($values["Service"])) AND (!empty($values["Service"]))) {
            $fieldsStr .= "`Service`, ";
            if (is_numeric($values["Service"])) {
                $valuesStr .= "" . $values["Service"] . ", ";
            } else {
                $values["Service"] = $this->prepareValue($values["Service"]);
                $valuesStr .= "'" . $values["Service"] . "', ";
            }
        }
        if ((isset($values["Creator"])) AND (!empty($values["Creator"]))) {
            $fieldsStr .= "`Creator`, ";
            if (is_numeric($values["Creator"])) {
                $valuesStr .= "" . $values["Creator"] . ", ";
            } else {
                $values["Creator"] = $this->prepareValue($values["Creator"]);
                $valuesStr .= "'" . $values["Creator"] . "', ";
            }
        }
        if ((isset($values["Objects"])) AND (!empty($values["Objects"]))) {
            $fieldsStr .= "`Objects`, ";
            if (is_numeric($values["Objects"])) {
                $valuesStr .= "" . $values["Objects"] . ", ";
            } else {
                $values["Objects"] = $this->prepareValue($values["Objects"]);
                $valuesStr .= "'" . $values["Objects"] . "', ";
            }
        }
        if ((isset($values["Cars"])) AND (!empty($values["Cars"]))) {
            $fieldsStr .= "`Cars`, ";
            if (is_numeric($values["Cars"])) {
                $valuesStr .= "" . $values["Cars"] . ", ";
            } else {
                $values["Cars"] = $this->prepareValue($values["Cars"]);
                $valuesStr .= "'" . $values["Cars"] . "', ";
            }
        }
        if ((isset($values["Arrive"])) AND (!empty($values["Arrive"]))) {
            $fieldsStr .= "`Arrive`, ";
            if (is_numeric($values["Arrive"])) {
                $valuesStr .= "" . $values["Arrive"] . ", ";
            } else {
                $values["Arrive"] = $this->prepareValue($values["Arrive"]);
                $valuesStr .= "'" . $values["Arrive"] . "', ";
            }
        }
        if ((isset($values["Depart"])) AND (!empty($values["Depart"]))) {
            $fieldsStr .= "`Depart`, ";
            if (is_numeric($values["Depart"])) {
                $valuesStr .= "" . $values["Depart"] . ", ";
            } else {
                $values["Depart"] = $this->prepareValue($values["Depart"]);
                $valuesStr .= "'" . $values["Depart"] . "', ";
            }
        }
        if ((isset($values["organization"])) AND (!empty($values["organization"]))) {
            $fieldsStr .= "`organization`, ";
            if (is_numeric($values["organization"])) {
                $valuesStr .= "" . $values["organization"] . ", ";
            } else {
                $values["organization"] = $this->prepareValue($values["organization"]);
                $valuesStr .= "'" . $values["organization"] . "', ";
            }
        }
        $fieldsStr .= "`Created`";
        $valuesStr .= "'" . date('Y-m-d H:i:s', time()) . "'";
        // if ($fieldsStr != "(") {
        //     $fieldsStr = substr($fieldsStr, 0, -2);
        // }
        // if ($valuesStr != "(") {
        //     $valuesStr = substr($valuesStr, 0, -2);
        // }
        $fieldsStr .= ")";
        $valuesStr .= ")";
        // echo "INSERT INTO `" . $this->tablename . "` $fieldsStr VALUES $valuesStr" . "<br/>";
        if (($fieldsStr != "()") AND ($valuesStr != "()")) {

            try{
                if ($this->db->query("INSERT INTO `" . $this->tablename . "` $fieldsStr VALUES $valuesStr"))
                    return true;
                else
                    return false;
            }
            catch(PDOException $exception){
                echo 'Database Error: ' . $exception->getMessage();
            }
        } else {
            return false;
        }
    }

    public function editItemById($id, $values = []) {
        if (((isset($id)) AND (!empty($id))) AND
            // ((isset($values["Priority"])) AND (!empty($values["Priority"]))) AND
            // ((isset($values["Title"])) AND (!empty($values["Title"]))) AND
            ((isset($values["Creator"])) AND (!empty($values["Creator"]))) AND
            // ((isset($values["Objects"])) AND (!empty($values["Objects"]))) AND
            ((isset($values["Cars"])) AND (!empty($values["Cars"])))) {
            //$values["Priority"] = htmlspecialchars($values["Priority"]);
            $values["organization"] = htmlspecialchars($values["organization"]);
            // $values["Title"] = htmlspecialchars($values["Title"]);
            $values["Creator"] = htmlspecialchars($values["Creator"]);
            $values["Objects"] = htmlspecialchars($values["Objects"]);
            $values["Cars"] = htmlspecialchars($values["Cars"]);
            $setStr = "";
            //$setStr .= "Priority = '" . $values["Priority"] . "', ";
            //$setStr .= "Title = '" . $values["Title"] . "', ";
            $setStr .= "Creator = '" . $values["Creator"] . "', ";
            $setStr .= "Objects = '" . $values["Objects"] . "', ";
            $setStr .= "Cars = '" . $values["Cars"] . "', ";
            $setStr .= "organization = " . $values["organization"] . ", ";

            $dateNow = date('Y-m-d H:i:s', time());
            $setStr .= "Changed = '" . $dateNow . "'";

            try{
                $this->db->query("UPDATE `" . $this->tablename . "` SET $setStr WHERE id = $id");
            }
            catch(PDOException $exception){
                echo 'Database Error: ' . $exception->getMessage();
            }
        } else {
            return false;
        }
    }

    public function deleteItemById($id) {
        if (((isset($id)) AND (!empty($id)))) {

            try{
                $this->db->query("DELETE FROM `" . $this->tablename . "` WHERE id = $id");
            }
            catch(PDOException $exception){
                echo 'Database Error: ' . $exception->getMessage();
            }
        } else {
            return false;
        }
    }

    public function activateItemById($ids) {
        if (((isset($ids)) AND (!empty($ids)))) {

            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $whereStr = "";
            $i = 0;
            foreach ($ids as $id) {
                if ($i = 0) {
                    $whereStr .= "id = $id";
                    $i++;
                } else {
                    $whereStr .= " OR id = $id";
                }
            }

            try {
                $this->db->query("UPDATE `" . $this->tablename . "` SET active = 1 WHERE $whereStr");
            }
            catch(PDOException $exception) {
                echo 'Database Error: ' . $exception->getMessage();
            }
        } else {
            return false;
        }
    }
    public function deactivateItemById($ids) {
        if (((isset($ids)) AND (!empty($ids)))) {

            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $whereStr = "";
            $i = 0;
            foreach ($ids as $id) {
                if ($i == 0) {
                    $whereStr .= "id = $id";
                    $i++;
                } else {
                    $whereStr .= " OR id = $id";
                }
            }

            $dateNow = date('Y-m-d H:i:s', time());

            try {
                $this->db->query("UPDATE `" . $this->tablename . "` SET active = 0, Changed = '$dateNow' WHERE $whereStr");
                return true;
            }
            catch(PDOException $exception) {
                echo 'Database Error: ' . $exception->getMessage();
                return false;
            }
        } else {
            return false;
        }
    }

}