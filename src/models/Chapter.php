<?php

namespace johnsnook\cryptobook\models;

use johnsnook\cryptobook\models\Updatable;
use johnsnook\cryptobook\behaviors\SecretBehavior;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use \yii\helpers\ArrayHelper;

/**
 * This is the model class for table "book.chapter".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property string $content
 * @property int[] $toc
 * @property string $book_id
 * @property Section[] $chapters
 */
class Chapter extends Updatable {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'book.chapter';
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
                    [['book_id'], 'required'],
                    [['content'], 'string'],
                    [['book_id'], 'default', 'value' => null],
                    [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::className(), 'targetAttribute' => ['book_id' => 'id']],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return ArrayHelper::merge(parent::attributeLabels(), [
                    'book_id' => 'Book ID',
                    'content' => 'Content',
        ]);
    }

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

    public function beforeDelete() {
        $this->book->removeChapter($this->id);
        $this->book->save();
        return parent::beforeDelete();
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            $this->book->appendChapter($this->id);
            $this->book->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBook() {
        return $this->hasOne(Book::className(), ['id' => 'book_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSections() {
        return $this->hasMany(Section::className(), ['chapter_id' => 'id']);
    }

    /**
     * Godamn fucking array expression
     *
     * @param integer $id
     */
    public function appendSection($id) {
        $toc = $this->toc->getValue();
        $toc[] = $id;
        $this->toc = array_values($toc);
    }

    /**
     * Godamn fucking array expression
     *
     * @param integer $id
     */
    public function removeSection($id) {
        $toc = $this->toc->getValue();
        if ($p = array_search($id, $toc)) {
            unset($toc[$p]);
            $this->toc = array_values($toc);
        }
    }

    /**
     * Converts an integer to roman numeral
     *
     * @param integer $integer
     * @return string
     */
    public static function intToRoman($integer) {
        // Convert the integer into an integer (just to make sure)
        $integer = intval($integer);
        $result = '';

        // Create a lookup array that contains all of the Roman numerals.
        $lookup = ['M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1];

        foreach ($lookup as $roman => $value) {
            // Determine the number of matches
            $matches = intval($integer / $value);

            // Add the same number of characters to the string
            $result .= str_repeat($roman, $matches);

            // Set the integer to be the remainder of the integer and the value
            $integer = $integer % $value;
        }

        // The Roman numeral should be built, return it
        return $result;
    }

}
