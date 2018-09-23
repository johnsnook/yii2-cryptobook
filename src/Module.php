<?php

/**
 * This file is part of the Yii2 extension module, yii2-visitor
 *
 * @author John Snook
 * @date 2018-06-28
 * @license https://github.com/johnsnook/yii2-visitor/LICENSE
 * @copyright 2018 John Snook Consulting
 */

namespace johnsnook\cryptobook;

/**

 * @property array $urlRules Array of rules for a UrlManger configured to pretty Url
 *
 * @author John Snook <jsnook@gmail.com>
 */
class Module extends \yii\base\Module implements \yii\base\BootstrapInterface {

    /**
     * @var string The next release version string
     */
    const VERSION = 'v1.0.0';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        '/cryptobook' => '/cryptobook/book/index',
        '/cryptobook/<controller>s' => '/cryptobook/<controller>/index',
//        '/cryptobook/<controller>/<id:\d+>' => '/cryptobook/<controller>/view',
        '/cryptobook/<controller>/<action>/<id:\d+>' => '/cryptobook/<controller>/<action>',
    ];

    /**
     * Why don't you pull yourself up by the bootstraps like I did by being born
     * middle class and having parents who could help pay for college?
     *
     * If we're running in console mode set the controller space to our commands
     * folder.  If not, attach our main event to the the [[ap beforeAction
     *
     * @param \yii\web\Application $app
     */
    public function bootstrap($app) {
        if ($app->hasModule($this->id) && ($module = $app->getModule($this->id)) instanceof Module) {
            $um = $app->getUrlManager();
            $um->addRules($this->urlRules, true);
        }
    }

    /**
     * @return Database connection
     */
    public function getDb() {
        if (empty($this->db)) {
            return \Yii::$app->getDb();
        } else {
            return $this->db;
        }
    }

}
