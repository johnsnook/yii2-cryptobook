<?php

namespace johnsnook\cryptobook\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "book.updatable".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 */
class Updatable extends Super {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'book.updatable';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [
                    'blameable' => [
                        'class' => BlameableBehavior::className(),
                        'createdByAttribute' => 'created_by',
                        'updatedByAttribute' => 'updated_by',
                    ],
        ]);
    }

    public function getCreated() {
        return ucfirst($this->createdBy->username) . ' @ ' . $this->createdAt;
    }

    public function getUpdated() {
        return ucfirst($this->updatedBy->username) . ' @ ' . $this->updatedAt;
    }

    public function getUpdatedAt() {
        if ($this->updated_at === 'NOW()') {
            $this->refresh();
        }
        $dt = new \DateTime($this->updated_at);
        return $dt->format(static::$dateFormat);
    }

    public function getUpdatedAgo() {
        if ($this->updated_at === 'NOW()') {
            $this->refresh();
        }
        return static::since((new \DateTime($this->updated_at))->getTimestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
                    [['created_by'], 'default', 'value' => 1],
                    [['created_by'], 'integer'],
                    [['updated_at'], 'safe'],
                    [['updated_by'], 'default', 'value' => 1],
                    [['updated_by'], 'integer'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return ArrayHelper::merge(parent::attributeLabels(), [
                    'created_by' => 'Created By',
                    'updated_at' => 'Updated At',
                    'updated_by' => 'Updated By',
        ]);
    }

}
