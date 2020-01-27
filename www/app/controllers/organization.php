<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/app/models/excelPass.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/app/models/PassInner.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/app/models/Organization.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OrganizationController extends Controller {

    public function defaultAction() {
        $this->listAction();
    }

    public function listAction() {

        if (!empty($this->request["page"])) {
            $page = $this->request["page"];
        } else {
            $page = 0;
        }
        $paginationPerPage = 20;
        $offset = $page * $paginationPerPage;

        $organization = new Organization();
        $organizationList = $organization->getList($paginationPerPage, $offset);
        $organizationCount = $organization->getRowCount();

        $this->view->render('template/header');
        $this->view->render('template/menu');
        $this->view->render('organization/table', ["list" => $organizationList, "request" => $this->request, "item_per_page" => $paginationPerPage, "item_count" => $organizationCount]);
        $this->view->render('template/footer');
    }

    public function createAction() {
        $this->checkAuth();

        if ($this->checkPermission("admin") !== false) {
            header('Location: /');
            die();
        }

        $organization = new Organization();

        $errors = [];

        $formSent = false;
        $newValues = [];
        if ((isset($this->request["title"]))) {
            $formSent = true;
            if ((!empty($this->request["title"]))) {
                $newValues["title"] = $this->request["title"];
            } else {
                $errors[] = "Все поля обязательны для заполнения.";
            }
        }

        if (($formSent) AND (empty($errors))) {
            if ($organization->addItem($newValues)) {
                header("Location: /organization/");
                die();
            } else {
                $errors[] = "Произошла непредвиденная ошибка создания.";
            }
        }

        $this->view->render('template/header');
        $this->view->render('template/menu');
        $this->view->render('organization/create', ["errors" => $errors, "form_fields" => $this->request]);
        $this->view->render('template/footer');
    }

    public function editAction() {
        $this->checkAuth();

        if ($this->checkPermission("admin") !== false) {
            header('Location: /');
            die();
        }

        $organization = new Organization();

        $errors = [];

        if (empty($this->request["id"])) {
            header("Location: /organization/");
            die();
        }

        $formSent = false;
        $newValues = [];
        if ((isset($this->request["title"])) AND
            (isset($this->request["id"]))) {
            $formSent = true;
            $formFields = $this->request;
            if ((!empty($this->request["title"]))) {
                $newValues["title"] = $this->request["title"];
            } else {
                $errors[] = "Все поля обязательны для заполнения.";
            }
        } else {
            $formFields = $organization->getDataById($this->request["id"]);
        }


        if (($formSent) AND (empty($errors))) {
            $organization->editItemById($this->request["id"], $newValues);

            header("Location: /organization/");
            die();
        }

        $this->view->render('template/header');
        $this->view->render('template/menu');
        $this->view->render('organization/edit', ["errors" => $errors, "form_fields" => $formFields]);
        $this->view->render('template/footer');
    }

    public function deleteAction() {
        $this->checkAuth();

        if ($this->checkPermission("admin") !== false) {
            header('Location: /');
            die();
        }

        $organization = new Organization();
        if ((isset($this->request["id"])) AND
            (isset($this->request["formSent"]))) {
            $filters[] = [
                "name" => "organization",
                "value" => $this->request["id"],
            ];
            $passInner = new PassInner();
            $itemList = $passInner->getListRaw($filters);
            $ids = [];
            foreach ($itemList as $item) {
                $ids[] = $item["id"];
            }
            $passInner->deactivateItemById($ids);
            $organization->deactivateItemById($this->request["id"]);

            header("Location: /organization/");
            die();
        } else {
            $formFields = $organization->getDataById($this->request["id"]);
        }

        $this->view->render('template/header');
        $this->view->render('template/menu');
        $this->view->render('organization/delete', ["form_fields" => $formFields]);
        $this->view->render('template/footer');
    }

    public function clearAction() {
        $this->checkAuth();

        if ($this->checkPermission("admin") !== false) {
            header('Location: /');
            die();
        }

        $organization = new Organization();

        if ((isset($this->request["id"])) AND
            (isset($this->request["formSent"]))) {
            $filters[] = [
                "name" => "organization",
                "value" => $this->request["id"],
            ];
            $passInner = new PassInner();
            $itemList = $passInner->getListRaw($filters);
            $ids = [];
            foreach ($itemList as $item) {
                $ids[] = $item["id"];
            }
            $passInner->deactivateItemById($ids);

            header("Location: /organization/");
            die();
        } else {
            $formFields = $organization->getDataById($this->request["id"]);
        }

        $this->view->render('template/header');
        $this->view->render('template/menu');
        $this->view->render('organization/clear', ["form_fields" => $formFields]);
        $this->view->render('template/footer');
    }

    public function uploadAction() {
        $this->checkAuth();

        if ($this->checkPermission("admin") !== false) {
            header('Location: /');
            die();
        }

        $organization = new Organization();

        $errors = [];
        if ((isset($this->request["id"])) AND
            (isset($this->request["Arrive"])) AND
            (isset($this->request["Depart"])) AND
            (isset($this->request["files"]["file"]))) {
            $formSent = true;
            $formFields = $this->request;
            if ((!empty($this->request["id"])) AND
                (!empty($this->request["Arrive"])) AND
                (!empty($this->request["Depart"])) AND
                (!empty($this->request["files"]["file"]))) {
                if ($this->request["files"]["file"]["tmp_name"]) {
                    $excelPass = new excelPass($this->request["files"]["file"]["tmp_name"]);
                    if ($newPassItems = $excelPass->getPassItems()) {
                        $passInner = new PassInner();

                        $uploadedCount = 0;
                        foreach($newPassItems as $item) {
                            // $item["Cars"] set in excelPass
                            $item["Arrive"] = date("Y-m-d H:i:s", strtotime($this->request["Arrive"]));
                            $item["Depart"] = date("Y-m-d H:i:s", strtotime($this->request["Depart"]));
                            $item["organization"] = $this->request["id"];

                            if ($passInner->addItem($item)) {
                                $uploadedCount++;
                            }
                        }

                        $this->view->render('template/header');
                        $this->view->render('template/menu');
                        $this->view->render('organization/uploadComplete', ["title" => $this->request["title"], "uplodaed_count" => $uploadedCount, "id" => $this->request["id"]]);
                        $this->view->render('template/footer');
                        return true;
                    } else {
                        $errors[] = "Ошибка структры файла.";
                    }
                } else {
                    $errors[] = "Ошибка загрузки файла.";
                }
            } else {
                $errors[] = "Все поля обязательны для заполнения.";
            }
        } else {
            $formFields = $organization->getDataById($this->request["id"]);
        }

        $this->view->render('template/header');
        $this->view->render('template/menu');
        $this->view->render('organization/upload', ["errors" => $errors, "form_fields" => $formFields]);
        $this->view->render('template/footer');
        return true;
    }
}