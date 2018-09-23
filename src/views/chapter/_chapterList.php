<?php

/**
 * @author John Snook
 * @date Sep 20, 2018
 * @license https://snooky.biz/site/license
 * @copyright 2018 John Snook Consulting
 * Description of _list
 */
/* @var array $models */
/* @var \yii\web\View $this */
use johnsnook\cryptobook\TocAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\jui\Sortable;

TocAsset::register($this);
$this->registerJsVar('toc', $book->toc->getValue());
$this->registerJsVar('reindexUrl', Url::toRoute(['book/reindex', 'slug' => $book->slug]));

$items = [];
$i = 1;
foreach ($book->toc as $key) {
    $chp = $models[$key];
    $content = '';

    $content .= Html::beginTag('div', ['class' => 'col-sm-1 text-center text-nowrap']);
    $content .= Html::a(Html::tag('i', '', [
                        'class' => 'glyphicon glyphicon-move drag-handle',
                        'data-toggle' => 'tooltip',
                        'title' => 'Reorder this chapter'
    ]));
    $content .= ' ' . Html::a(Html::tag('i', '', [
                        'class' => 'glyphicon glyphicon-eye-open',
                        'data-toggle' => 'tooltip',
                        'title' => 'Read chapter'
                    ]), ['view', 'id' => $chp->id]);
    $content .= ' ' . Html::a(Html::tag('i', '', [
                        'class' => 'glyphicon glyphicon-pencil',
                        'data-toggle' => 'tooltip',
                        'title' => 'Edit chapter'
                    ]), ['update', 'id' => $chp->id]);
    $content .= Html::endTag('div');

    $content .= Html::beginTag('strong', ['class' => 'col-sm-7']);
    $content .= 'Chapter <span class="chapterIndex">' . $i++ . '</span>: ';
    $content .= ucwords($chp->title);
    $content .= Html::endTag('strong');

    $content .= Html::beginTag('div', ['class' => 'col-sm-4 text-right']);
    $content .= 'Last updated ' . Html::tag('time', '', [
                'class' => 'timeago',
                'datetime' => $chp->updated_at
    ]);
    $content .= Html::endTag('div');

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
