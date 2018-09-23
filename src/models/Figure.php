<?php

namespace johnsnook\cryptobook\models;

use Yii;

/**
 * This is the model class for table "book.figure".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property resource $image
 * @property string $book_id
 */
class Figure extends Super {

    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'book.figure';
    }

    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [
                    'crypto' => [
                        'class' => SecretBehavior::className(),
                        'secretAttributes' => ['title', 'image']
                    ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
                    [['image', 'book_id'], 'required'],
                    [['image', 'book_id'], 'string'],
                    [['created_at'], 'safe'],
                    [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::className(), 'targetAttribute' => ['book_id' => 'id']],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return ArrayHelper::merge(parent::rules(), [
                    'image' => 'Image',
                    'book_id' => 'Book Id',
        ]);
    }

}
