<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Exception;
use yii\db\Expression;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $title
 * @property int $year
 * @property string|null $description
 * @property string|null $isbn
 * @property string|null $image
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Author[] $authors
 */
class Book extends ActiveRecord
{
    private array $_authors = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'year'], 'required'],
            [['year'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 13],
            [['isbn'], 'unique'],
            [['image'], 'string', 'max' => 255],
            [['authors'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'year' => Yii::t('app', 'Year'),
            'description' => Yii::t('app', 'Description'),
            'isbn' => Yii::t('app', 'ISBN'),
            'image' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if (!empty($this->_authors)) {
            BookAuthors::updateAuthors($this->id, $this->_authors);

            if ($insert) {
                Notification::addedNewBook($this->title, $this->_authors);
            }
        }
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%book_authors}}', ['book_id' => 'id']);
    }

    /**
     * Sets authors for the book on create new or update one.
     * @param array $value authors' IDs
     */
    public function setAuthors($value): void
    {
        if (is_array($value)) {
            $this->_authors = $value;
        }
    }

    /**
     * Gets a list of existed years in descending order.
     *
     * @return array
     */
    public static function getYears(): array
    {
        return self::find()->select('year')->distinct()->orderBy('year DESC')->column();
    }
}
