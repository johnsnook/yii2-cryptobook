<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model johnsnook\cryptobook\models\Section */

$this->title = 'Update Section: ' . $model->title;
$this->title = $model->book->title . ' - Table of Contents';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->chapter->book->title,
    'url' => ['chapter/toc', 'book_slug' => $model->chapter->book_slug]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->chapter->title,
    'url' => ['chapter/view', 'id' => $model->chapter_id]
];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="section-update">
    <h3><?= Html::encode($this->title) ?></h3>
    <?php
    echo $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
