<?php
header('Content-Type: text/html; charset=windows-1251');
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Content-transfer-encoding: binary');
header('Content-Disposition: attachment; filename=camera_list.xls');
header('Content-Type: application/x-unknown');



?>
<?  if (!empty($viewdata_list)) : ?>
        <table border="1">
            <tr>
                <th>License Plate Number</th>
                <th>License Plate Color</th>
                <th>Belong to</th>
                <th>Card No</th>
                <th>Effective Start Time</th>
                <th>Effective End Time</th>
            </tr>
        <?  foreach($viewdata_list as $list_item) : ?>
                <tr>
                    <td>
                        <?=$list_item["Cars"]?>
                    </td>
                    <td>
                        White
                    </td>
                    <td>
                        Whitelist
                    </td>
                    <td>
                        0
                    </td>
                    <td>
                        <?=$list_item["Arrive"]?>
                    </td>
                    <td>
                        <?=$list_item["Depart"]?>
                    </td>
                </tr>
        <?  endforeach;?>
        </table>
<?  endif; ?>