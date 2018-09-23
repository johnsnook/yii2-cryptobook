<?php

namespace johnsnook\cryptobook\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "book.super".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 */
class Super extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'book.super';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['title'], 'required'],
            [['title'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return[
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getCreatedAt() {
        if ($this->created_at === 'NOW()') {
            $this->refresh();
        }
        $dt = new \DateTime($this->created_at);
        return $dt->format(static::$dateFormat);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'created_at' => 'Created At',
        ];
    }

}
