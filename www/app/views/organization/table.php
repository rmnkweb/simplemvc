<div class="pass">
    <div class="passLinks">
        <a href="/organization/create">Добавить организацию</a>
    </div>
<?  if (!empty($viewdata_list)) : ?>
        <table class="passTable <?=(((isset($viewdata_username)) AND (!empty($viewdata_username))) ? "admin" : "")?>">
            <tr>
<!--                <th class="sortable">-->
<!--                    <a href="?order=--><?//=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "State") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?><!--&page=--><?//=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?><!--&orderby=State">-->
<!--                        State-->
<!--                    </a>-->
<!--                </th>-->
<!--                <th class="sortable">-->
<!--                    <a href="?order=--><?//=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "Priority") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?><!--&page=--><?//=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?><!--&orderby=Priority">-->
<!--                        Priority-->
<!--                    </a>-->
<!--                </th>-->
                <th>
                    Организация
                </th>
            <?  if ($this->checkPermission("admin") !== false) : ?>
                    <th></th>
            <?  endif; ?>
            </tr>
        <?  foreach ($viewdata_list as $listItem) : ?>
                <tr data-itemid="<?=$listItem["id"]?>">
                    <td class="passTableTitle"><?=$listItem["title"]?></td>
                <?  if ($this->checkPermission("admin") !== false) : ?>
                        <td class="passTableEdit">
                            <a href="/pass/?filter_organization=<?=$listItem["id"]?>" class="list"></a>
                            <a href="/organization/upload/?id=<?=$listItem["id"]?>" class="excel"></a>
                            <a href="/organization/edit/?id=<?=$listItem["id"]?>" class="edit"></a>
                            <a href="/organization/delete/?id=<?=$listItem["id"]?>" class="delete"></a>
                            <a href="/organization/clear/?id=<?=$listItem["id"]?>" class="clear"></a>
                        </td>
                <?  endif; ?>
                </tr>
        <?  endforeach; ?>
        </table>
        <div class="passPagination">
        <?  $pageNumber = ceil($viewdata_item_count / $viewdata_item_per_page);
            for ($i = 0; $i < $pageNumber;) : ?>
                <a href="?&page=<?=$i?>" class="<?=(((isset($viewdata_request["page"])) AND ($viewdata_request["page"] == $i)) ? "current" : "")?>"><?= ++$i?></a>
        <?  endfor; ?>
        </div>
<?  endif; ?>
</div>