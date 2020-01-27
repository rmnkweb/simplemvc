<form method="POST" class="passForm">
<?  if (isset($viewdata_errors) AND (!empty($viewdata_errors))) : ?>
        <div class="passFormErrors">
        <?  foreach($viewdata_errors as $error) : ?>
                <p><?=$error?></p>
        <?  endforeach; ?>
        </div>
<?  endif; ?>
    <input type="hidden" name="id" value="<?=(isset($viewdata_form_fields["id"]) ? $viewdata_form_fields["id"] : "")?>" />
<!--    <div class="passFormRow">-->
<!--        Priority: <br />-->
<!--        <input name="Priority" value="--><?//=(isset($viewdata_form_fields["Priority"]) ? $viewdata_form_fields["Priority"] : "")?><!--" />-->
<!--    </div>-->
<?  if ((isset($viewdata_organization_list)) && (count($viewdata_organization_list) > 0)) : ?>
        <div class="passFormRow">
            Организация: <br />
            <select name="organization">
                <option value="">Выберите организацию</option>
            <?  foreach($viewdata_organization_list as $organization_item) : ?>
                    <option value="<?=$organization_item['id']?>" <?=(((isset($viewdata_form_fields["organization"])) && ($viewdata_form_fields["organization"] === $organization_item['id'])) ? "selected" : "")?>><?=$organization_item['title']?></option>
            <?  endforeach;?>
            </select>
        </div>
<?  endif; ?>
    <div class="passFormRow">
        Тип пропуска: <br />
        <input type="text" name="Service" value="<?=(isset($viewdata_form_fields["Service"]) ? $viewdata_form_fields["Service"] : "")?>" />
    </div>
    <div class="passFormRow">
        Ответственный: <br />
        <input type="text" name="Creator" value="<?=(isset($viewdata_form_fields["Creator"]) ? $viewdata_form_fields["Creator"] : "")?>" />
    </div>
    <div class="passFormRow">
        Комментарий: <br />
        <textarea name="Objects"><?=(isset($viewdata_form_fields["Objects"]) ? $viewdata_form_fields["Objects"] : "")?></textarea>
    </div>
    <div class="passFormRow">
        Номерной знак: <br />
        <input type="text" name="Cars" value="<?=(isset($viewdata_form_fields["Cars"]) ? $viewdata_form_fields["Cars"] : "")?>" />
    </div>
    <div class="passFormRow">
        Заезд: <br />
        <input type="text" name="Arrive" class="datetimepicker" autocomplete="no" value="<?=(isset($viewdata_form_fields["Arrive"]) ? $viewdata_form_fields["Arrive"] : "")?>" />
    </div>
    <div class="passFormRow">
        Выезд: <br />
        <input type="text" name="Depart" class="datetimepicker" autocomplete="no" value="<?=(isset($viewdata_form_fields["Depart"]) ? $viewdata_form_fields["Depart"] : "")?>" />
    </div>
    <div class="passFormRow">
        <button>Сохранить</button>
    </div>
</form>