<?php

use yii\helpers\Html;
use yii\helpers\Markdown;

/* @var $this yii\web\View */
/* @var $model johnsnook\cryptobook\models\Section */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->book->title . ' Sections',
    'url' => ['index', 'book_id' => $model->book_id]
];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="section-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <style>
        .break{
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-all;
        }
        twitterwidget{
            margin-left:auto;
            margin-right:auto;
        }
    </style>
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
    <div class="container ">
        <?php
        echo Markdown::process($model->content, 'extra');
        ?>
    </div>

</div>
