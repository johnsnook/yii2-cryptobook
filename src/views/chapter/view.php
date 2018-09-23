<?php

use yii\helpers\Html;
use johnsnook\cryptobook\models\Chapter;

/* @var $this yii\web\View */
/* @var $chapter johnsnook\cryptobook\models\Chapter */

$this->title = $chapter->title;
$this->params['breadcrumbs'][] = [
    'label' => $chapter->book->title . ' Chapters',
    'url' => ['index', 'book_id' => $chapter->book_id]
];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<style>
    hr{
        border: 0;
        height: 1px;
        background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
    }
    section{
        margin-bottom:50px;
    }
</style>

<div class="chapter-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $chapter->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $chapter->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
        <?= Html::a('Add Section', ['section/create', 'chapter_id' => $chapter->id], ['class' => 'btn btn-default']) ?>
    </p>

    <chapter>
        <h2><?= Html::encode($this->title) ?></h2>
        <div class="row">
            <div class="col-md-7 ">
                <?=
                $this->render('_sectionList', [
                    'sections' => $sections,
                    'chapter' => $chapter
                ])
                ?>
            </div>
        </div>
        <div class="col-md-5 "></div>
        <div class="container ">
            <?php
//        echo Markdown::process($chapter->content, 'extra');
            echo $chapter->content;
            $items = [];
            $i = 1;
            foreach ($chapter->toc as $key) {
                $section = $sections[$key];
                echo Html::beginTag('section', ['id' => 'section-' . $key]);
                echo Html::tag('h3', 'Section ' . Chapter::intToRoman($i++)
                        . ' - ' . Html::encode($this->title), ['class' => 'text-center']);
                echo Html::tag('hr', ['width' => '50%']);
                echo $section->content;
                echo Html::endTag('section');
            }
            ?>
        </div>
    </chapter>
</div>
