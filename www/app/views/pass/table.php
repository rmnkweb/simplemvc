<div class="pass">
    <div class="passFilter">
        <form>
            <input type="hidden" name="order" value="<?=((isset($viewdata_request["order"])) ? $viewdata_request["order"] : "")?>" />
            <input type="hidden" name="orderby" value="<?=((isset($viewdata_request["orderby"])) ? $viewdata_request["orderby"] : "")?>" />
            <input type="hidden" name="page" value="0" />

            <div class="passFilterItem">
                Поиск: <br />
                <input type="text" name="search" />
            </div>
            <div class="passFilterItem">
                <select name="filter_source">
                    <option value="all" <?=(((isset($viewdata_request["filter_source"])) AND ($viewdata_request["filter_source"] === "all")) ? "selected" : "")?>>Все</option>
                    <option value="inner" <?=(((isset($viewdata_request["filter_source"])) AND ($viewdata_request["filter_source"] === "inner")) ? "selected" : "")?>>Постоянные</option>
                    <option value="outer" <?=(((isset($viewdata_request["filter_source"])) AND ($viewdata_request["filter_source"] === "outer")) ? "selected" : "")?>>Временные</option>
                </select>
            </div>
            <div class="passFilterItem">
                <button class="passFilterItemSubmit">Фильтровать</button>
            <?  if (((isset($viewdata_request["search"])) AND (!(empty($viewdata_request["search"])))) OR
                    ((isset($viewdata_request["filter_organization"])) AND (!(empty($viewdata_request["filter_organization"])))) OR
                    ((isset($viewdata_request["filter_source"])) AND (!(empty($viewdata_request["filter_source"]))))) : ?>
                    <a href="?order=<?=((isset($viewdata_request["order"])) ? $viewdata_request["order"] : "ASC")?>&page=0&orderby=<?=((isset($viewdata_request["orderby"])) ? $viewdata_request["orderby"] : "id")?>&search=" class="passFilterItemCancel">Сбросить</a>
            <?  endif; ?>
            </div>
            <div class="passFilterItem">
            </div>
        </form>
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
                <th class="sortable">
                    <a href="?order=<?=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "Title") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?>&page=<?=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?>&orderby=Title">
                        Организация
                    </a>
                </th>
                <th class="sortable">
                    <a href="?order=<?=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "Service") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?>&page=<?=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?>&orderby=Service">
                        Тип пропуска
                    </a>
                </th>
                <th class="sortable">
                    <a href="?order=<?=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "Creator") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?>&page=<?=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?>&orderby=Creator">
                        Ответственный
                    </a>
                </th>
<!--                <th class="sortable">-->
<!--                    <a href="?order=--><?//=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "Executors") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?><!--&page=--><?//=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?><!--&orderby=Executors">-->
<!--                        Исполнитель-->
<!--                    </a>-->
<!--                </th>-->
<!--                <th class="sortable">-->
<!--                    <a href="?order=--><?//=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "Changed") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?><!--&page=--><?//=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?><!--&orderby=Changed">-->
<!--                        Changed-->
<!--                    </a>-->
<!--                </th>-->
<!--                <th class="sortable">-->
<!--                    <a href="?order=--><?//=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "Created") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?><!--&page=--><?//=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?><!--&orderby=Created">-->
<!--                        Created-->
<!--                    </a>-->
<!--                </th>-->
                <th class="sortable">
                    <a href="?order=<?=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "Objects") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?>&page=<?=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?>&orderby=Objects">
                        Комментарий
                    </a>
                </th>
                <th class="sortable">
                    <a href="?order=<?=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "Cars") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?>&page=<?=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?>&orderby=Cars">
                        Номерной знак
                    </a>
                </th>
                <th class="sortable">
                    <a href="?order=<?=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "Arrive") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?>&page=<?=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?>&orderby=Arrive">
                        Заезд
                    </a>
                </th>
                <th class="sortable">
                    <a href="?order=<?=((((isset($viewdata_request["orderby"])) AND (isset($viewdata_request["order"]))) AND (($viewdata_request["orderby"] == "Depart") AND ($viewdata_request["order"] == "ASC"))) ? "DESC" : "ASC")?>&page=<?=((isset($viewdata_request["page"])) ? $viewdata_request["page"] : 0)?>&orderby=Depart">
                        Выезд
                    </a>
                </th>
            <?  if ($this->checkPermission("admin") !== false) : ?>
                    <th></th>
            <?  endif; ?>
            </tr>
        <?  foreach ($viewdata_list as $listItem) : ?>
                <tr data-itemid="<?=$listItem["id"]?>">
<!--                    <td class="passTableState">--><?//=$listItem["State"]?><!--</td>-->
<!--                    <td class="passTablePriority">--><?//=$listItem["Priority"]?><!--</td>-->
                <?  if ((isset($listItem["organization"])) AND (!empty($listItem["organization"])) AND (is_array($listItem["organization"]))) : ?>
                        <td class="passTableTitle">
                            <a href="/pass/?filter_organization=<?=$listItem["organization"]["id"]?>"><?=$listItem["organization"]["title"]?></a>
                        </td>
                <?  else : ?>
                        <td class="passTableTitle"><?=$listItem["Title"]?></td>
                <?  endif; ?>
                    <td class="passTableService"><?=$listItem["Service"]?></td>
                    <td class="passTableCreator"><?=$listItem["Creator"]?></td>
<!--                    <td class="passTableExecutors">--><?//=$listItem["Executors"]?><!--</td>-->
<!--                    <td class="passTableChanged">--><?//=$listItem["Changed"]?><!--</td>-->
<!--                    <td class="passTableCreated">--><?//=$listItem["Created"]?><!--</td>-->
                    <td class="passTableObjects"><?=$listItem["Objects"]?></td>
                    <td class="passTableCars"><?=$listItem["Cars"]?></td>
                    <td class="passTableArrive"><?=$listItem["Arrive"]?></td>
                    <td class="passTableDepart"><?=$listItem["Depart"]?></td>
                <?  if ($this->checkPermission("admin") !== false) : ?>
                        <td class="passTableEdit">
                        <?  if ($listItem["inner"] === true) : ?>
                                <a href="/pass/edit/?id=<?=$listItem["id"]?>" class="edit"></a>
                                <a href="/pass/delete/?id=<?=$listItem["id"]?>&filter_organization=<?=((isset($viewdata_request["filter_organization"])) ? $viewdata_request["filter_organization"] : "")?>" class="delete confirmDelete"></a>
                        <?  endif; ?>
                        </td>
                <?  endif; ?>
                </tr>
        <?  endforeach; ?>
        </table>
        <div class="passPagination">
        <?  $pageNumber = ceil($viewdata_item_count / $viewdata_item_per_page);
            for ($i = 0; $i < $pageNumber;) : ?>
                <a href="?order=<?=(((isset($viewdata_request["order"])) AND ($viewdata_request["order"] == "ASC")) ? "ASC" : "DESC")?>&page=<?=$i?>&orderby=<?=((isset($viewdata_request["orderby"])) ? $viewdata_request["orderby"] : "id")?>&search=<?=((isset($viewdata_request["search"])) ? $viewdata_request["search"] : "")?>&filter_organization=<?=((isset($viewdata_request["filter_organization"])) ? $viewdata_request["filter_organization"] : "")?>&filter_source=<?=((isset($viewdata_request["filter_source"])) ? $viewdata_request["filter_source"] : "")?>" class="<?=(((isset($viewdata_request["page"])) AND ($viewdata_request["page"] == $i)) ? "current" : "")?>"><?= ++$i?></a>
        <?  endfor; ?>
        </div>
<?  endif; ?>
</div>