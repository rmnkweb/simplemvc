<form method="POST" enctype="multipart/form-data" class="passForm">
    <?  if (isset($viewdata_errors) AND (!empty($viewdata_errors))) : ?>
        <div class="passFormErrors">
            <?  foreach($viewdata_errors as $error) : ?>
                <p><?=$error?></p>
            <?  endforeach; ?>
        </div>
    <?  endif; ?>
    <input type="hidden" name="id" value="<?=(isset($viewdata_form_fields["id"]) ? $viewdata_form_fields["id"] : "")?>" />
    <input type="hidden" name="title" value="<?=(isset($viewdata_form_fields["title"]) ? $viewdata_form_fields["title"] : "")?>" />
    <div class="passFormRow">
        Загрузка данных для организации <b><?=(isset($viewdata_form_fields["title"]) ? $viewdata_form_fields["title"] : "")?></b>
    </div>
    <div class="passFormRow">
        Заезд: <br />
        <input type="text" name="Arrive" class="datetimepicker" value="<?=(isset($viewdata_form_fields["Arrive"]) ? $viewdata_form_fields["Arrive"] : "")?>" />
    </div>
    <div class="passFormRow">
        Выезд: <br />
        <input type="text" name="Depart" class="datetimepicker" value="<?=(isset($viewdata_form_fields["Depart"]) ? $viewdata_form_fields["Depart"] : "")?>" />
    </div>
    <div class="passFormRow">
        Документ *.xlsx: <br />
        <input type="file" name="file" />
    </div>
    <div class="passFormRow">
        <button>Загрузить</button>
    </div>
</form>