<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\jui\Sortable;

/* @var $this yii\web\View */
/* @var $book yii\db\ActiveRecord */
/* @var $sections array */

$this->title = $book->title . ' - Table of Contents';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/js/jquery.timeago.js', ['depends' => ['yii\web\JqueryAsset']]);
$this->registerJsFile('/js/toc.js', ['depends' => ['yii\jui\JuiAsset']]);
$this->registerJsVar('toc', $book->toc->getValue());
$this->registerJsVar('reindexUrl', Url::toRoute(['book/reindex', 'id' => $book->id]));
?>
<style>
    i.drag-handle:hover{
        cursor: move;
    }

</style>
<div class="section-index">
    <h1><?= Html::encode($this->title) ?></h2>
        <p>
            <?= Html::a('New Section', ['create', 'book_id' => $book->id], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Update Book', ['book/update', 'id' => $book->id], ['class' => 'btn btn-primary']) ?>
            <?=
            Html::a('Delete Book', ['book/delete', 'id' => $book->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>

        </p>
        <?php
        $items = [];
//        dump($book->toc);
//        die();
//
        $i = 1;
        foreach ($book->toc as $key) {
            $chp = $sections[$key];
            $content = '';
//            $content .= Html::beginTag('div', ['class' => 'row']);

            $content .= Html::beginTag('div', ['class' => 'col-sm-1 text-center text-nowrap']);
            $content .= Html::a(Html::tag('i', '', [
                                'class' => 'glyphicon glyphicon-move drag-handle',
                                'data-toggle' => 'tooltip',
                                'title' => 'Reorder this section'
            ]));
            $content .= ' ' . Html::a(Html::tag('i', '', [
                                'class' => 'glyphicon glyphicon-eye-open',
                                'data-toggle' => 'tooltip',
                                'title' => 'Read section'
                            ]), ['view', 'id' => $chp->id]);
            $content .= ' ' . Html::a(Html::tag('i', '', [
                                'class' => 'glyphicon glyphicon-pencil',
                                'data-toggle' => 'tooltip',
                                'title' => 'Edit section'
                            ]), ['update', 'id' => $chp->id]);
            $content .= Html::endTag('div');

            $content .= Html::beginTag('div', ['class' => 'col-sm-7']);
            $content .= 'Section <span class="sectionIndex">' . $i++ . '</span>: ';
            $content .= ucwords($chp->title);
//            $content .= Html::a($chp->title, ['view', 'id' => $key]);
            $content .= Html::endTag('div');

            $content .= Html::beginTag('div', ['class' => 'col-sm-4 text-right']);
            $content .= 'Last updated ' . Html::tag('time', '', [
                        'class' => 'timeago',
                        'datetime' => $chp->updated_at
            ]);
            $content .= Html::endTag('div');

//            $content .= Html::endTag('div');

            $items[] = [
                'options' => ['data-id' => $chp->id],
                'content' => $content
            ];
        }

        echo Sortable::widget([
            'items' => $items,
            'options' => [
                'id' => 'toc',
                'tag' => 'div',
                'class' => 'list-group',
                'style' => 'list-style-type: decimal;'
            ],
            'itemOptions' => ['tag' => 'div', 'class' => 'row list-group-item'],
            'clientOptions' => [
                'cursor' => 'move', 'forcePlaceholderSize' => true,
                'change' => 'indexToc()'
            ],
        ]);
        ?>
</div>

