<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model johnsnook\cryptobook\models */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">

    <?php
    $form = ActiveForm::begin();
//    $key = KeyProtectedByPassword::createRandomPasswordProtectedKey('shitman');
//    $model->key = $key->saveToAsciiSafeString();
    echo $form->field($model, 'title')->textInput();
    if ($model->isNewRecord) {
        echo $form->field($model, 'key')->passwordInput();
    }
    ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
