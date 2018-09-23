<?php

use yii\widgets\ActiveForm;
use johnsnook\cryptobook\SummernoteAsset;
use johnsnook\cryptobook\IdleAsset;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model johnsnook\cryptobook\models\Chapter */
/* @var $form yii\widgets\ActiveForm */

SummernoteAsset::register($this);
IdleAsset::register($this);
$this->registerJs('initFormHelp();', $this::POS_READY);
$this->registerJsVar('isDirty', false);
?>

<div class="post-form">
    <?php
    $form = ActiveForm::begin([
                'id' => 'frmChapter',
                'action' => false
    ]);
    ?>
    <div class="form-group">
        <?php
        if ($model->isNewRecord) {
            $new = Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']);
            echo Html::submitButton("$new Save", ['class' => 'btn btn-success btn-sm']);
        } else {
            $save = Html::tag('i', '', ['class' => 'glyphicon glyphicon-floppy-save']);
            $view = Html::tag('i', '', ['class' => 'glyphicon glyphicon-eye-open']);
            echo Html::submitButton("$save Save", ['id' => 'btnSave', 'class' => 'btn disabled btn-primary btn-sm']);
            echo Html::a("$view View", ['view', 'id' => $model->id], ['class' => 'btn btn-sm']);
        }
        ?>
    </div>

    <?= $form->field($model, 'title')->textInput(['autocomplete' => "off"]) ?>
    <?= $form->field($model, 'content')->hiddenInput() ?>
    <div id="summerContent" style="height:500px" ><?= $model->content ?></div>
    <?= $form->field($model, 'book_id')->hiddenInput()->label(false) ?>
    <?php ActiveForm::end(); ?>
</div>

<script type="text/javascript">
    function initFormHelp() {
        $("#frmChapter").submit(function (e) {
            if (<?= $model->isNewRecord ? 'false' : 'true' ?>) {
                $.ajax({
                    url: '/cryptobook/chapter/update/<?= $model->id ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response)
                    {
                        console.log(response);
                        $("#btnSave").addClass('disabled');
                        isDirty = false;
                    },
                    error: function (e) {
                        $("#btnSave")
                                .removeClass('btn-primary')
                                .addClass('btn-danger');
                        console.log(e);
                    }
                });
                return false;
            }
        });

        $(document).idle({
            onIdle: function () {
                if (isDirty) {
                    $("#frmChapter").submit();
                    console.log('autosave');
                }
            }, idle: 5000
        });


        $("#summerContent").summernote({
            callbacks: {
                onChange: function (contents, $editable) {
                    $("input#chapter-content").val(contents);
                    $("#btnSave").removeClass('disabled');
                    isDirty = true;
                }
            },
            lang: 'en-US',
            dialogsInBody: true,
            popover: {
                image: [
                    ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                    /* ['float', ['floatLeft', 'floatRight', 'floatNone']], */
                    /* Those are the old regular float buttons */
                    ['floatBS', ['floatBSLeft', 'floatBSNone', 'floatBSRight']],
                    /* Those come from the BS plugin, in any order, you can even keep both! */
                    ['remove', ['removeMedia']],
                ],
            }

        });
    }
</script>
