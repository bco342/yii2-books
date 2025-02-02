<?php

namespace app\models\forms;

use app\models\Author;
use app\models\Subscription;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * SubscriptionForm is the model behind the subscription form.
 */
class SubscriptionForm extends Model
{
    public string $guestPhone = '';
    // Author ID
    public string $id = '';

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            [['id', 'guestPhone'], 'required'],
            ['id', 'integer'],
            ['id', 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['id' => 'id']],
            ['guestPhone', 'string'],
            ['guestPhone', 'filter', 'filter' => [Subscription::class, 'formatPhoneNumber']],
            [['guestPhone'], 'string', 'min' => 10, 'max' => 12],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'guestPhone' => Yii::t('app', 'Phone'),
            'id' => Yii::t('app', 'Author ID'),
        ];
    }

    /**
     * @throws Exception
     */
    public function save(): bool
    {
        if ($this->validate()) {
            $attributes = [
                'guest_phone' => $this->guestPhone,
                'author_id' => $this->id,
            ];
            if (empty(Subscription::findOne($attributes))) {
                $subscription = new Subscription($attributes);
                return $subscription->save();
            } else {
                return true;
            }
        }
        return false;
    }
}