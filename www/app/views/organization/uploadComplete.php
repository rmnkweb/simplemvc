<form method="POST" class="passForm">
    <?  if (isset($viewdata_errors) AND (!empty($viewdata_errors))) : ?>
        <div class="passFormErrors">
            <?  foreach($viewdata_errors as $error) : ?>
                <p><?=$error?></p>
            <?  endforeach; ?>
        </div>
    <?  endif; ?>
    <input type="hidden" name="id" value="<?=(isset($viewdata_form_fields["id"]) ? $viewdata_form_fields["id"] : "")?>" />
    <input type="hidden" name="formSent" value="1" />
    <div class="passFormRow">
        Для организации <b><?=(isset($viewdata_title) ? $viewdata_title : "")?></b> было загружено <b><?=(isset($viewdata_uplodaed_count) ? $viewdata_uplodaed_count : "")?></b> записей.
    </div>
    <div class="passFormRow">
        <a href="/pass/?filter_organization=<?=(isset($viewdata_id) ? $viewdata_id : "")?>">Просмотреть записи от этой организации</a>
        <a href="/organization/">Вернуться к списку организаций</a>
    </div>
</form>