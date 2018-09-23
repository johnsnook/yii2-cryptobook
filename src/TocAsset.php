<?php

namespace johnsnook\cryptobook;

use yii\web\AssetBundle;

/**
 * frontend application asset bundle.
 */
class TocAsset extends AssetBundle {

    public $sourcePath = __DIR__ . '/assets';
    public $js = [
        'js/jquery.timeago.js',
        'js/toc.js',
    ];
    public $depends = [
        'yii\jui\JuiAsset',
    ];

}
