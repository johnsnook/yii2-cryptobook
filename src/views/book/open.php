<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use Defuse\Crypto\KeyProtectedByPassword;

/* @var $this yii\web\View */
/* @var $model johnsnook\cryptobook\models */
/* @var $form yii\widgets\ActiveForm */
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->title . ' Passphrase',
    'url' => ['toc', 'book_slug' => $model->slug]
];
?>

<div class="book-form">
    <?php
    $form = ActiveForm::begin(['layout' => 'horizontal']);
    echo $form->field($model, 'title')->textInput()->staticControl();
    echo $form->field($model, 'passphrase')->passwordInput(['autofocus' => true]);
    echo $form->field($model, 'slug')->hiddenInput()->label(false);
    ?>
    <input name="request" type="hidden" value="<?= $request; ?>">
    <div class="form-group ">
        <div class="col-sm-6 col-sm-offset-3">
            <?= Html::submitButton('Open', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
