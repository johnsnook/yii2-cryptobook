<?php

namespace johnsnook\cryptobook;

use yii\web\AssetBundle;

/**
 * Summernote asset bundle.
 */
class SummernoteAsset extends AssetBundle {

    public $sourcePath = '@bower/summernote/dist';
    public $css = ['summernote-bs4.css'];
    public $js = ['summernote-bs4.min.js'];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_END];

    /**
     *
     * @param \yii\web\View $view
     */
    public static function register($view) {
        $view->registerJs(file_get_contents(__DIR__ . '/assets/js/summernote-floats-bs.min.js'));
        parent::register($view);
    }

}
