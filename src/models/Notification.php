<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;

/**
 * Class Notification
 *
 * @property int $id
 * @property string $to
 * @property string $message
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
final class Notification extends ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'notifications';
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
            [['to', 'message', 'status'], 'required'],
            [['to', 'message', 'status'], 'string'],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_SUCCESS, self::STATUS_FAILED]],
        ];
    }

    /**
     * @param string $bookTitle
     * @param array $authorIds
     * @return void
     * @throws Exception
     */
    public static function addedNewBook(string $bookTitle, array $authorIds): void
    {
        $subscriptions = Subscription::find()
            ->joinWith(['author'])
            ->andWhere([Author::tableName() . '.id' => $authorIds])
            ->all();
        if (!empty($subscriptions)) {
            /** @var Subscription $subscription */
            foreach ($subscriptions as $subscription) {
                $notification = new Notification([
                    'status' => Notification::STATUS_ACTIVE,
                    'to' => $subscription->guest_phone,
                    'message' => Yii::t('app', "New book: {title} by {author}", [
                        'title' => $bookTitle,
                        'author' => $subscription->author->full_name,
                    ]),
                ]);
                $notification->save();
            }
        }
    }
}