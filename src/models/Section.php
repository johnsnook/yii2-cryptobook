<?php

namespace johnsnook\cryptobook\models;

use Yii;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use johnsnook\cryptobook\behaviors\SecretBehavior;
use \yii\helpers\ArrayHelper;

/**
 * This is the model class for table "book.chapter_section".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property string $content
 * @property int $chapter_id
 */
class Section extends Updatable {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'book.chapter_section';
    }

    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [
                    'crypto' => [
                        'class' => SecretBehavior::className()
                    ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
                    [['chapter_id', 'content'], 'required'],
                    [['content'], 'string'],
                    [['chapter_id'], 'exist',
                        'skipOnError' => true,
                        'targetClass' => Chapter::className(),
                        'targetAttribute' => ['chapter_id' => 'id']
                    ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return ArrayHelper::merge(parent::attributeLabels(), [
                    'chapter_id' => 'Chapter Number',
                    'content' => 'Content',
        ]);
    }

    public function afterFind() {
        if ($key = Book::getDecryptKey($this->chapter->book_slug)) {
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
        if (parent::beforeSave($insert) && ($key = Book::getDecryptKey($this->chapter->book_slug))) {
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

    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            $this->chapter->appendSection($this->id);
            $this->chapter->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete() {
        $this->chapter->removeSection($this->id);
        $this->chapter->save();
        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChapter() {
        return $this->hasOne(Chapter::className(), ['id' => 'chapter_id']);
    }

}
