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
 * @property string $book_slug
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
                    [['image', 'book_slug'], 'required'],
                    [['image', 'book_slug'], 'string'],
                    [['created_at'], 'safe'],
                    [['book_slug'], 'exist', 'skipOnError' => true, 'targetClass' => Book::className(), 'targetAttribute' => ['book_slug' => 'slug']],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return ArrayHelper::merge(parent::rules(), [
                    'image' => 'Image',
                    'book_slug' => 'Book Slug',
        ]);
    }

}
