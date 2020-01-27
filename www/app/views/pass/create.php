<form method="POST" class="passForm">
    <?  if (isset($viewdata_errors) AND (!empty($viewdata_errors))) : ?>
        <div class="passFormErrors">
            <?  foreach($viewdata_errors as $error) : ?>
                <p><?=$error?></p>
            <?  endforeach; ?>
        </div>
    <?  endif; ?>
<!--    <div class="passFormRow">-->
<!--        Priority: <br />-->
<!--        <input type="text" name="Priority" value="--><?//=(isset($viewdata_form_fields["Priority"]) ? $viewdata_form_fields["Priority"] : "")?><!--" />-->
<!--    </div>-->
<?  if ((isset($viewdata_organization_list)) && (count($viewdata_organization_list) > 0)) : ?>
        <div class="passFormRow">
            Организация<span class="passFormRowRequired">*</span>: <br />
            <select name="organization">
                <option value="">Выберите организацию</option>
            <?  foreach($viewdata_organization_list as $organization_item) : ?>
                    <option value="<?=$organization_item['id']?>"><?=$organization_item['title']?></option>
            <?  endforeach;?>
            </select>
        </div>
<?  endif; ?>
    <div class="passFormRow">
        Тип пропуска: <br />
        <input type="text" name="Service" value="" />
    </div>
    <div class="passFormRow">
        Ответственный<span class="passFormRowRequired">*</span>: <br />
        <input type="text" name="Creator" value="" />
    </div>
    <div class="passFormRow">
        Комментарий: <br />
        <textarea name="Objects"></textarea>
    </div>
    <div class="passFormRow">
        Номерной знак<span class="passFormRowRequired">*</span>: <br />
        <input type="text" name="Cars" value="" />
    </div>
    <div class="passFormRow">
        Заезд<span class="passFormRowRequired">*</span>: <br />
        <input type="text" name="Arrive" class="datetimepicker" autocomplete="no" value="" />
    </div>
    <div class="passFormRow">
        Выезд<span class="passFormRowRequired">*</span>: <br />
        <input type="text" name="Depart" class="datetimepicker" autocomplete="no" value="" />
    </div>
    <div class="passFormRow">
        <button>Добавить машину</button>
    </div>
</form>