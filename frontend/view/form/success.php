<?php declare(strict_types = 1);
namespace noxkiwi\core;

?>
<div class="rsCrudFeedback alert alert-success alert-dismissible" role="alert" id="crudAlert">
    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
    Der Eintrag wurde erfolgreich aktualisiert. <?= Response::getInstance()->get('CRUD_ACTION') ?>
</div>
