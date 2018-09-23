<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model johnsnook\cryptobook\models\Section */

$this->title = 'Create Section';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->chapter->book->title,
    'url' => ['chapters', 'book_id' => $model->chapter->book_id]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->chapter->title,
    'url' => ['chapter/view', 'id' => $model->chapter_id]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-create">
    <h3><?= Html::encode($this->title) ?></h3>
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
