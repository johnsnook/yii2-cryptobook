<?php

namespace johnsnook\cryptobook;

use yii\web\AssetBundle;

/**
 * frontend application asset bundle.
 */
class IdleAsset extends AssetBundle {

    public $sourcePath = '@bower/jquery.idle';
    public $js = [
        'jquery.idle.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public static function register($view) {
        $idle = \Yii::$app->session->timeout * 1000;
        $view->registerJsVar('timeOut', $idle);
        $view->registerJsVar('countDown', null);

        $js = <<< JS
    $(document).idle({
        onIdle: function(){
            console.log('idle');
            countDown = setTimeout(function(){
                window.location.href = '/cryptobook/books' /* send 'em back to index */
            }, $idle);
        },
        onActive: function(){
            console.log('active');
            clearTimeout(countDown);
            $.ajax('/cryptobook/book/keepalive');
        },
        idle: 4000
    });
JS;
        $view->registerJs($js);
        return parent::register($view);
    }

}
