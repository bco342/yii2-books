<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "subscriptions".
 *
 * @property int $author_id
 * @property string $guest_phone
 * @property string $created_at
 *
 * @property Author $author
 */
class Subscription extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'subscriptions';
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
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['guest_phone', 'author_id'], 'required'],
            [['author_id'], 'integer'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
            [['guest_phone'], 'filter', 'filter' => [$this, 'formatPhoneNumber']],
            [['guest_phone'], 'string', 'min' => 12, 'max' => 12],
        ];
    }

    /**
     * Formats phone number to standard format
     * @param string $value The phone number to format
     * @return string Formatted phone number
     */
    public static function formatPhoneNumber(string $value): string
    {
        // Remove all non-digit characters
        $cleaned = preg_replace('/\D/', '', $value);

        if (empty($cleaned)) {
            return '';
        }
        
        // If number starts with 8, replace it with 7
        if ($cleaned[0] === '8') {
            $cleaned = '7' . substr($cleaned, 1);
        }
        
        // If number doesn't start with 7, add it
        if ($cleaned[0] !== '7') {
            $cleaned = '7' . $cleaned;
        }
        
        return '+' . $cleaned;
    }

    /**
     * Gets query for [[Author]].
     *
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
