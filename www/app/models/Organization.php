<?php

class Organization extends Model {

    private $searchFields = [
        [
            "title" => "title",
            "type" => "string"
        ],
    ];

    public function __construct() {
        parent::__construct();

        $this->tablename = "Organization";
    }

    public function addValues($valuesArray) {
        $queryValues = "";
        foreach ($valuesArray as $values) {
            if ((isset($values["title"]))) {
                $title = $this->prepareValue($values["title"]);
                $queryValues .= "(\"$title\"), \n";
            }
        }
        $queryValues = substr_replace($queryValues, ";",  -3, -1);
        $queryStr = "INSERT INTO `" . $this->tablename . "` (`title`) VALUES " . $queryValues;

        // echo $queryStr;

        try {
            $this->db->query($queryStr);
        }
        catch(PDOException $exception){
            echo 'Database Error: ' . $exception->getMessage();
        }
    }

    private function prepareValue($value) {
        return htmlspecialchars(str_replace("\\", "/", $value));
    }

    // params: $pagination = items per page; offset = pageNum*Pagination; $orderBy = db.table col name; $order = ASC/DESC; $filters = [["name", "value"], ...]
    public function getList($pagination = 20, $offset = 0) {
        $statement = $this->db->prepare("SELECT * FROM `" . $this->tablename . "` ORDER BY id ASC LIMIT :offset, :pagination");
        $statement->bindValue(':offset', intval($offset), PDO::PARAM_INT);
        $statement->bindValue(':pagination', intval($pagination), PDO::PARAM_INT);
        $statement->execute();
        $list = $statement->fetchAll(PDO::FETCH_ASSOC);
        $column = array_column($list, "title", "id");
        asort($column, SORT_STRING);
        usort($list, ["Organization", "sortByTitle"]);
        return $list;
    }

    private static function sortByTitle($arrayItem1, $arrayItem2) {
        return strnatcmp($arrayItem1["title"],$arrayItem2["title"]);
    }

    public function getRowCount() {
        if ($query = $this->db->query("SELECT count(*) FROM `" . $this->tablename . "`"))
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
        if (((isset($values["title"])) AND (!empty($values["title"])))) {
            $values["title"] = $this->prepareValue($values["title"]);
            $valuesStr = "('" . $values["title"] . "')";

            try{
                if ($this->db->query("INSERT INTO `" . $this->tablename . "` (`title`) VALUES $valuesStr"))
                    return true;
            }
            catch(PDOException $exception){
                echo 'Database Error: ' . $exception->getMessage();
                die();
            }
        } else {
            return false;
        }
    }

    public function editItemById($id, $values = []) {
        if (((isset($id)) AND (!empty($id))) AND
            ((isset($values["title"])) AND (!empty($values["title"])))) {
            $values["title"] = htmlspecialchars($values["title"]);
            $setStr = "";
            $setStr .= "title = '" . $values["title"] . "'";

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

            try {
                $this->db->query("UPDATE `" . $this->tablename . "` SET active = 0 WHERE $whereStr");
            }
            catch(PDOException $exception) {
                echo 'Database Error: ' . $exception->getMessage();
            }
        } else {
            return false;
        }
    }

}