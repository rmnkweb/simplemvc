<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/app/models/PassInner.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/app/models/PassOuter.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/app/models/Organization.php";

class PassController extends Controller {

    public function defaultAction() {
        $this->listAction();
    }

    public function listAction() {

        if (!empty($this->request["orderby"])) {
            $orderBy = $this->request["orderby"];
        } else {
            $orderBy = "id";
        }
        if (!empty($this->request["order"])) {
            $order = $this->request["order"];
        } else {
            $order = "ASC";
        }
        if (!empty($this->request["page"])) {
            $page = $this->request["page"];
        } else {
            $page = 0;
        }
        $paginationPerPage = 10;
        $offset = $page * $paginationPerPage;

        // using search instead
        $filters = [];
        if (!empty($this->request["filter_cars"])) {
            $filters[] = [
                "name" => "Cars",
                "type" => "string",
                "value" => $this->request["filter_cars"],
            ];
        }
        if (!empty($this->request["filter_organization"])) {
            $filters[] = [
                "name" => "organization",
                "type" => "number",
                "value" => $this->request["filter_organization"],
            ];
        }

        if (!empty($this->request["filter_source"])) {
            $itemSource = $this->request["filter_source"];
        } else {
            $itemSource = "all";
        }

        if (!empty($this->request["search"])) {
            $search = $this->request["search"];
        } else {
            $search = "";
        }


        if (($itemSource === "all") OR ($itemSource == "inner")) {
            $passInner = new PassInner();
            $passInnerList = $passInner->getList($paginationPerPage, $offset, $orderBy, $order, $filters, $search);
            $passInnerCount = $passInner->getRowCount($filters, $search);
            foreach ($passInnerList as $key => $item) {
                $passInnerList[$key]["inner"] = true;
            }
        } else {
            $passInnerList = [];
            $passInnerCount = 0;
        }

        if (($itemSource === "all") OR ($itemSource == "outer")) {
            $passOuter = new PassOuter();
            $passOuterList = $passOuter->getList($paginationPerPage, $offset, $orderBy, $order, $filters, $search);
            $passOuterCount = $passOuter->getRowCount($filters, $search);
            foreach ($passOuterList as $key => $item) {
                $passOuterList[$key]["inner"] = false;
            }
        } else {
            $passOuterList = [];
            $passOuterCount = 0;
        }

        $passCount = (int) ($passInnerCount + $passOuterCount);
        $list = array_merge($passInnerList, $passOuterList);

        $organization = new Organization();
        foreach ($list as $key => $item) {
            if ((isset($item["organization"])) AND (!empty($item["organization"]))) {
                if ($organizationData = $organization->getDataById($item["organization"])) {
                    $list[$key]["organization"] = [
                        "id" => $organizationData["id"],
                        "title" => $organizationData["title"],
                    ];
                }
            }
        }

        $username = (isset($_SESSION["login"])) ? $_SESSION["login"] : false;

        $this->view->render('template/header');
        $this->view->render('template/menu');
        $this->view->render('pass/table', ["list" => $list, "request" => $this->request, "item_per_page" => $paginationPerPage, "item_count" => $passCount, "username" => $username]);
        $this->view->render('template/footer');
    }

    public function createAction() {
        $this->checkAuth();

        if ($this->checkPermission("admin") !== false) {
            header('Location: /');
            die();
        }

        $passInner = new PassInner();

        $errors = [];

        $formSent = false;
        $newValues = [];
        if ( //(isset($this->request["Priority"])) AND
            // (isset($this->request["Title"])) AND
            // (isset($this->request["Service"])) AND
            (isset($this->request["organization"])) AND
            (isset($this->request["Creator"])) AND
            // (isset($this->request["Objects"])) AND
            (isset($this->request["Cars"])) AND
            (isset($this->request["Arrive"])) AND
            (isset($this->request["Depart"]))) {
            $formSent = true;
            if (//(!empty($this->request["Priority"])) AND
                // (!empty($this->request["Title"])) AND
                // (!empty($this->request["Service"])) AND
                (!empty($this->request["Creator"])) AND
                // (!empty($this->request["Objects"])) AND
                (!empty($this->request["Cars"])) AND
                (!empty($this->request["Arrive"])) AND
                (!empty($this->request["Depart"]))) {
                //$newValues["Priority"] = $this->request["Priority"];
                $newValues["organization"] = (!empty($this->request["organization"])) ? $this->request["organization"] : NULL;
                $newValues["Title"] = (!empty($this->request["Title"])) ? $this->request["Title"] : NULL;
                $newValues["Service"] = (!empty($this->request["Service"])) ? $this->request["Service"] : NULL;
                $newValues["Creator"] = $this->request["Creator"];
                $newValues["Objects"] = (!empty($this->request["Objects"])) ? $this->request["Objects"] : NULL;
                $newValues["Cars"] = str_replace(" ", "", $this->request["Cars"]);
                $newValues["Arrive"] = date("Y-m-d H:i:s", strtotime($this->request["Arrive"]));
                $newValues["Depart"] = date("Y-m-d H:i:s", strtotime($this->request["Depart"]));
            } else {
                $errors[] = "Все поля обязательны для заполнения.";
            }
        }

        if (($formSent) AND (empty($errors))) {
            if ($passInner->addItem($newValues)) {
                header("Location: /pass/");
                die();
            }
        }

        $organization = new Organization();
        $organizationList = $organization->getList(200, 0);

        $this->view->render('template/header');
        $this->view->render('template/menu');
        $this->view->render('pass/create', ["errors" => $errors, "form_fields" => $this->request, "organization_list" => $organizationList]);
        $this->view->render('template/footer');
    }

    public function editAction() {
        $this->checkAuth();

        if ($this->checkPermission("admin") !== false) {
            header('Location: /');
            die();
        }

        $passInner = new PassInner();

        $errors = [];

        if (empty($this->request["id"])) {
            header("Location: /pass/");
            die();
        }

        $formSent = false;
        $newValues = [];
        if (//(isset($this->request["Priority"])) AND
            // (isset($this->request["Title"])) AND
            (isset($this->request["Creator"])) AND
            // (isset($this->request["Objects"])) AND
            (isset($this->request["Cars"])) AND
            (isset($this->request["Arrive"])) AND
            (isset($this->request["Depart"]))) {
            $formSent = true;
            $formFields = $this->request;
            if (//(!empty($this->request["Priority"])) AND
                // (!empty($this->request["Title"])) AND
                (!empty($this->request["Creator"])) AND
                // (!empty($this->request["Objects"])) AND
                (!empty($this->request["Cars"])) AND
                (!empty($this->request["Arrive"])) AND
                (!empty($this->request["Depart"]))) {
                //$newValues["Priority"] = $this->request["Priority"];
                // $newValues["Title"] = $this->request["Title"];
                $newValues["organization"] = (!empty($this->request["organization"])) ? $this->request["organization"] : NULL;
                $newValues["Creator"] = $this->request["Creator"];
                $newValues["Objects"] = $this->request["Objects"];
                $newValues["Cars"] = str_replace(" ", "", $this->request["Cars"]);
                $newValues["Arrive"] = date("Y-m-d H:i:s", strtotime($this->request["Arrive"]));
                $newValues["Depart"] = date("Y-m-d H:i:s", strtotime($this->request["Depart"]));
            } else {
                $errors[] = "Все поля обязательны для заполнения.";
            }
        } else {
            $formFields = $passInner->getDataById($this->request["id"]);
        }


        if (($formSent) AND (empty($errors))) {
            $passInner->editItemById($this->request["id"], $newValues);

            header("Location: /");
            die();
        }

        $organization = new Organization();
        $organizationList = $organization->getList(200, 0);

        $this->view->render('template/header');
        $this->view->render('template/menu');
        $this->view->render('pass/edit', ["errors" => $errors, "form_fields" => $formFields, "organization_list" => $organizationList]);
        $this->view->render('template/footer');
    }

    public function deleteAction() {
        $this->checkAuth();

        if ($this->checkPermission("admin") !== false) {
            header('Location: /');
            die();
        }

        $passInner = new PassInner();
        if ($passInner->deactivateItemById($this->request["id"])) {
            $filters = "";
            if (!empty($this->request["filter_cars"])) {
                $filters .= "filter_cars=" . $this->request["filter_cars"];
            }
            if (!empty($this->request["filter_organization"])) {
                if ($filters !== "") {
                    $filters .= "&";
                }
                $filters .= "filter_organization=" . $this->request["filter_organization"];
            }

            header("Location: /pass/?" . $filters);
            die();
        } else {
            echo "Ошибка удаления.";
            die();
        }
    }
}