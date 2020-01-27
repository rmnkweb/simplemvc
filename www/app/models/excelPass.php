<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


class excelPass {

    private $filePath;
    private $spreadsheet;

    function __construct($filePath = null) {
        if (($filePath) AND (file_exists($filePath))) {
            $this->load($filePath);
        } else {
            throw new RuntimeException("Can't create excelPass object: no file providen");
        }
    }
    function __destruct() {
        if ($this->spreadsheet)
            unset($this->spreadsheet);
    }

    private function load($filePath) {
        if (($filePath) AND (file_exists($filePath))) {
            $fileMIME = mime_content_type($filePath);
            if ($fileMIME == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                $this->filePath = $filePath;

                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $this->spreadsheet = $reader->load($filePath);
            } else {
                throw new RuntimeException("Can't create excelPass object: file type incorrect");
            }
        } else {
            throw new RuntimeException("Can't create excelPass object: no file provided");
        }
    }

    public function getPassItems() {
        $worksheet = $this->spreadsheet->getActiveSheet();
        $rows = [];
        foreach ($worksheet->getRowIterator() AS $row) {
            $cellIterator = $row->getCellIterator();
            // $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }
            $rows[] = $cells;
        }
        if (count($rows) > 0) {
            $items = [];
            for ($i = 1; $i < count($rows); $i++) {
                $rows[$i][0] = $this->prepareValue($rows[$i][0]);
                $rows[$i][1] = $this->prepareValue($rows[$i][1]);
                $rows[$i][2] = $this->prepareValue($rows[$i][2]);
                $rows[$i][3] = $this->prepareValue($rows[$i][3]);
                $items[] = [
                    "Cars" => $rows[$i][0],
                    "Service" => $rows[$i][1],
                    "Creator" => $rows[$i][2],
                    "Objects" => $rows[$i][3],
                ];
            }
            return $items;
        } else {
            return false;
        }
    }

    private function prepareValue($value) {
        return htmlspecialchars(str_replace("\\", "/", $value));
    }
}