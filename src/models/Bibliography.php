<?php

namespace johnsnook\cryptobook\models;

use Yii;

/**
 * This is the model class for table "book.bibliography".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property string $url
 * @property string $book_slug
 */
class Bibliography extends Super {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'book.bibliography';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['title', 'url', 'book_slug'], 'required'],
            [['title', 'url', 'book_slug'], 'string'],
            [['created_at'], 'safe'],
            [['book_slug'], 'exist', 'skipOnError' => true, 'targetClass' => BookBook::className(), 'targetAttribute' => ['book_slug' => 'slug']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'created_at' => 'Created At',
            'url' => 'Url',
            'book_slug' => 'Book Slug',
        ];
    }

}
