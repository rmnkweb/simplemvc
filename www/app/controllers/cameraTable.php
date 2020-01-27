<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/app/models/PassInner.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/app/models/PassOuter.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class cameraTableController extends Controller {

    public function defaultAction() {
        return $this->csvAction();
    }

    public function xlsxAction() {
        $spreadsheet = $this->prepareExcel();

        $writer = new Xlsx($spreadsheet);
        if (headers_sent($file, $line)) {
            throw new RuntimeException("Unexpected output at $file:$line");
            die();
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="cameraTable.xlsx"');
        $writer->save("php://output");

        unset($spreadsheet);
    }
    public function xlsAction() {
        $spreadsheet = $this->prepareExcel();

        $writer = new Xls($spreadsheet);
        if (headers_sent($file, $line)) {
            throw new RuntimeException("Unexpected output at $file:$line");
            die();
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="cameraTable.xls"');
        $writer->save("php://output");

        unset($spreadsheet);
    }

    public function csvAction() {

        $passInner = new PassInner();
        $passInnerList = $passInner->getListRaw();
        $passOuter = new PassOuter();
        $passOuterList = $passOuter->getListRaw();

        $list = array_merge($passInnerList, $passOuterList);
        foreach ($list as $key => $item) {
            $tempDate = strtotime($list[$key]["Depart"]);
            $list[$key]["Depart"] = date("Y-m-d", $tempDate) . 'T' . date("H:i:s", $tempDate) . "Z";
            $tempDate = strtotime($list[$key]["Arrive"]);
            $list[$key]["Arrive"] = date("Y-m-d", $tempDate) . 'T' . date("H:i:s", $tempDate) . "Z";
            $list[$key]["Cars"] = trim($list[$key]["Cars"]);
            $list[$key]["Cars"] = str_replace("\n", " ", $list[$key]["Cars"]);
            $carNumPlates = explode(" ", $list[$key]["Cars"]);
            if (count($carNumPlates) === 0) {
                $carNumPlates = [$list[$key]];
            }
            foreach ($carNumPlates as $carNumPlate) {
                $newElem = $list[$key];
                $newElem["Cars"] = $this->prepareNumPlate($carNumPlate);
                array_push($list, $newElem);
                if (strpos($newElem["Cars"], "Y") !== false) {
                    $newElem["Cars"] = str_replace("Y", "#", $newElem["Cars"]);
                    array_push($list, $newElem);
                }
            }
            unset($list[$key]);
        }


        $fp = fopen('php://memory', 'w');

        fputcsv($fp, ["Licence Plate", "Owner", "Phone"], ";");
        foreach ($list as $item) {
            fputcsv($fp, [$item["Cars"], "", ""], ";");
        }

        fseek($fp, 0);
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="camera_list.csv";');
        fpassthru($fp);

        fclose($fp);
    }

    private function prepareExcel() {
        $passInner = new PassInner();
        $passInnerList = $passInner->getListRaw();

        $passOuter = new PassOuter();
        $passOuterList = $passOuter->getListRaw();

        $list = array_merge($passInnerList, $passOuterList);
        foreach ($list as $key => $item) {

            $tempDate = strtotime($list[$key]["Depart"]);
            $list[$key]["Depart"] = date("Y-m-d", $tempDate) . 'T' . date("H:i:s", $tempDate) . "Z";

            $tempDate = strtotime($list[$key]["Arrive"]);
            $list[$key]["Arrive"] = date("Y-m-d", $tempDate) . 'T' . date("H:i:s", $tempDate) . "Z";

            $list[$key]["Cars"] = trim($list[$key]["Cars"]);
            $list[$key]["Cars"] = str_replace("\n", " ", $list[$key]["Cars"]);
            $carNumPlates = explode(" ", $list[$key]["Cars"]);
            if (count($carNumPlates) === 0) {
                $carNumPlates = [$list[$key]];
            }
            foreach ($carNumPlates as $carNumPlate) {
                $newElem = $list[$key];
                $newElem["Cars"] = $this->prepareNumPlate($carNumPlate);
                array_push($list, $newElem);
                if (strpos($newElem["Cars"], "Y") !== false) {
                    $newElem["Cars"] = str_replace("Y", "#", $newElem["Cars"]);
                    array_push($list, $newElem);
                }
            }
            unset($list[$key]);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $sheet->setTitle("SurveilSheet");

        $sheet->getCell('A1')->setValue("plateNum");
        // $sheet->getCell('B1')->setValue("License Plate Color");
        $sheet->getCell('B1')->setValue("listType");
        $sheet->getCell('C1')->setValue("cardNo");
        $sheet->getCell('D1')->setValue("startTime");
        $sheet->getCell('E1')->setValue("endTime");
        $cell = 2;
        foreach($list as $item) {
            $sheet->getCell('A'.$cell)->setValue($item["Cars"]);
            // $sheet->getCell('B'.$cell)->setValue('White');
            $sheet->getCell('B'.$cell)->setValue('Whitelist');
            $sheet->getCell('C'.$cell)->setValue('0');
            $sheet->getCell('D'.$cell)->setValue($item["Arrive"]);
            $sheet->getCell('E'.$cell)->setValue($item["Depart"]);
            $cell++;
        }

        return $spreadsheet;
    }

    private function prepareNumPlate($numplate) {
        $translit_ru = [
            "А",
            "В",
            "С",
            "Е",
            "Н",
            "К",
            "М",
            "О",
            "Р",
            "Т",
            "Х",
            "У",
        ];
        $translit_en = [
            "A",
            "B",
            "C",
            "E",
            "H",
            "K",
            "M",
            "O",
            "P",
            "T",
            "X",
            "Y",
        ];

        return str_replace($translit_ru, $translit_en, $numplate);
    }
}