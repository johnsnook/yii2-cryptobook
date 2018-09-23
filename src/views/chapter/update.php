<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model johnsnook\cryptobook\models\Chapter */

$this->title = 'Update Chapter: ' . $model->title;
$this->title = $model->book->title . ' - Table of Contents';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->book->title . ' Chapters',
    'url' => ['toc', 'book_slug' => $model->book_slug]
];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="chapter-update">
    <h3><?= Html::encode($this->title) ?></h3>
    <?php
    echo $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
