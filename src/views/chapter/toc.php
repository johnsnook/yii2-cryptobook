<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $book yii\db\ActiveRecord */
/* @var $chapters array */

$this->title = $book->title . ' - Table of Contents';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    i.drag-handle:hover{
        cursor: move;
    }

</style>
<div class="chapter-index">
    <h1><?= Html::encode($this->title) ?></h2>
        <p>
            <?= Html::a('New Chapter', ['create', 'book_slug' => $book->slug], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Update Book', ['book/update', 'slug' => $book->slug], ['class' => 'btn btn-primary']) ?>
            <?=
            Html::a('Delete Book', ['book/delete', 'slug' => $book->slug], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>

        </p>
        <?=
        $this->render('_chapterList', [
            'models' => $chapters,
            'book' => $book
        ])
        ?>
</div>

