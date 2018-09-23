<?php

/**
 * @author John Snook
 * @date Sep 20, 2018
 * @license https://snooky.biz/site/license
 * @copyright 2018 John Snook Consulting
 * Description of _list
 */
/* @var \johnsnook\cryptobook\models\Section[] $sections */
/* @var \johnsnook\cryptobook\models\Chapter $chapter */
/* @var \yii\web\View $this */
use johnsnook\cryptobook\TocAsset;
use johnsnook\cryptobook\models\Chapter;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\jui\Sortable;

TocAsset::register($this);
$this->registerJsVar('toc', $chapter->toc->getValue());
$this->registerJsVar('reindexSectionUrl', Url::toRoute(['chapter/reindex', 'id' => $chapter->id]));

$items = [];
$i = 1;
foreach ($chapter->toc as $key) {
    $section = $sections[$key];
    $content = '';

    $content .= Html::beginTag('span', ['class' => ' ']);
    $content .= Html::a(Html::tag('i', '', [
                        'class' => 'glyphicon glyphicon-move drag-handle',
                        'data-toggle' => 'tooltip',
                        'title' => 'Reorder this section'
    ]));
    $content .= ' ' . Html::a(Html::tag('i', '', [
                        'class' => 'glyphicon glyphicon-eye-open',
                        'data-toggle' => 'tooltip',
                        'title' => 'Read section'
                    ]), '#section-' . $key);
    $content .= ' ' . Html::a(Html::tag('i', '', [
                        'class' => 'glyphicon glyphicon-pencil',
                        'data-toggle' => 'tooltip',
                        'title' => 'Edit section'
                    ]), ['update', 'id' => $section->id]);
    $content .= Html::endTag('span');

    $content .= Html::beginTag('strong');
    $content .= 'Section <span class="chapterIndex">' . Chapter::intToRoman($i++) . '</span>: ';
    $content .= ucwords($section->title);
    $content .= Html::endTag('strong');

    $content .= Html::beginTag('span', ['class' => 'pull-right']);
    $content .= 'Last updated ' . Html::tag('time', '', [
                'class' => 'timeago',
                'datetime' => $section->updated_at
    ]);
    $content .= Html::endTag('span');

    $items[] = [
        'options' => ['data-id' => $section->id],
        'content' => $content
    ];
}

echo Sortable::widget([
    'items' => $items,
    'options' => [
        'id' => 'toc',
        'tag' => 'ol',
        'class' => 'list-group',
    ],
    'itemOptions' => ['tag' => 'li', 'class' => ' list-group-item'],
    'clientOptions' => [
        'cursor' => 'move', 'forcePlaceholderSize' => true,
        'change' => 'indexToc()'
    ],
]);
