<form method="POST" class="passForm">
    <?  if (isset($viewdata_errors) AND (!empty($viewdata_errors))) : ?>
        <div class="passFormErrors">
            <?  foreach($viewdata_errors as $error) : ?>
                <p><?=$error?></p>
            <?  endforeach; ?>
        </div>
    <?  endif; ?>
    <input type="hidden" name="id" value="<?=(isset($viewdata_form_fields["id"]) ? $viewdata_form_fields["id"] : "")?>" />
    <div class="passFormRow">
        Организация: <br />
        <input name="title" value="<?=(isset($viewdata_form_fields["title"]) ? $viewdata_form_fields["title"] : "")?>" />
    </div>
    <div class="passFormRow">
        <button>Сохранить</button>
    </div>
</form>