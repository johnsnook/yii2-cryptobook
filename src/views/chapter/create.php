<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model johnsnook\cryptobook\models\Chapter */

$this->title = 'Create Chapter';
$this->params['breadcrumbs'][] = [
    'label' => $model->book->title . ' Chapters',
    'url' => ['index', 'book_id' => $model->book_id]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chapter-create">
    <h3><?= Html::encode($this->title) ?></h3>
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
