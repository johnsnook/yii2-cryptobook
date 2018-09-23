<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model johnsnook\cryptobook\models\Chapter */

$this->title = 'Create Chapter';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->book->title . ' Chapters',
    'url' => ['toc', 'book_slug' => $model->book_slug]
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
