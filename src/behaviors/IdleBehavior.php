<?php

/**
 * @author John Snook
 * @date Sep 20, 2018
 * @license https://snooky.biz/site/license
 * @copyright 2018 John Snook Consulting
 * Description of SecretBehavior
 */

namespace johnsnook\cryptobook\behaviors;

use johnsnook\cryptobook\IdleAsset;

class IdleBehavior extends \yii\base\Behavior {

    public function prepPage($view) {
        header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        IdleAsset::register($view);
    }

}
