<?php

namespace johnsnook\cryptobook\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;
use Defuse\Crypto\Key;

/**
 * This is the model class for table "book.book".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property array $toc Array of indexed chapter id's
 * @property string $key
 *
 * @property Chapter[] $chapters
 */
class Book extends Updatable {

    public $passphrase;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'book.book';
    }

    public function afterFind() {
        parent::afterFind();
        if (is_null($this->toc)) {
            $this->toc = [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [[
                'class' => SluggableBehavior::className(),
                'attribute' => 'title'
        ]]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
                    [['title'], 'unique'],
                    [['slug'], 'unique'],
                    [['key'], 'string'],
                    ['toc', 'each', 'rule' => ['integer']],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return ArrayHelper::merge(parent::attributeLabels(), [
                    'key' => 'Pass Phrase',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChapters() {
        return $this->hasMany(Chapter::className(), ['slug' => 'book_slug']);
    }

    /**
     * Retrieves the encryption key from the session, if available
     *
     * @param string $slug
     * @return boolean
     */
    public static function getDecryptKey($slug) {
        $session = Yii::$app->session;
        if ($session->has($slug)) {
            return Key::loadFromAsciiSafeString($session->get($slug));
        }
        return false;
    }

    /**
     * Godamn fucking array expression
     *
     * @param integer $id
     */
    public function appendChapter($id) {
        $toc = $this->toc->getValue();
        $toc[] = $id;
        $this->toc = array_values($toc);
    }

    /**
     * Godamn fucking array expression
     *
     * @param integer $id
     */
    public function removeChapter($id) {
        $toc = $this->toc->getValue();
        if ($p = array_search($id, $toc)) {
            unset($toc[$p]);
            $this->toc = array_values($toc);
        }
    }

}
