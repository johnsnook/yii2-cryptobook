<?php

/**
 * @author John Snook
 * @date Sep 20, 2018
 * @license https://snooky.biz/site/license
 * @copyright 2018 John Snook Consulting
 * Description of SecretBehavior
 */

namespace johnsnook\cryptobook\behaviors;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;

class SecretBehavior extends \yii\base\Behavior {

    public $secretAttributes = ['title', 'content'];

    public function afterFind() {
        if ($key = Book::getDecryptKey($this->book_id)) {
            try {
                $this->title = Crypto::decrypt($this->title, $key);
                $this->content = Crypto::decrypt($this->content, $key);
            } catch (WrongKeyOrModifiedCiphertextException $ex) {
                throw $ex;
            }
        }

        parent::afterFind();
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert) && ($key = Book::getDecryptKey($this->book_id))) {
            try {
                $this->title = Crypto::encrypt($this->title, $key);
                $this->content = Crypto::encrypt($this->content, $key);
            } catch (WrongKeyOrModifiedCiphertextException $ex) {
                throw $ex;
            }
            return true;
        }
        return false;
    }

}
