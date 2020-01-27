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
        Очистить записи для организации <b><?=(isset($viewdata_form_fields["title"]) ? $viewdata_form_fields["title"] : "")?></b>?
    </div>
    <div class="passFormRow">
        <button>Очистить!</button>
    </div>
</form>