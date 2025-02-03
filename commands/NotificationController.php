<?php

namespace app\commands;

use app\models\Notification;
use app\services\SmsService;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;

class NotificationController extends Controller
{
    private SmsService $smsService;

    public function init(): void
    {
        parent::init();
        $this->smsService = new SmsService();
    }

    public function actionList(): int
    {
        $conditions = [
            Notification::STATUS_ACTIVE => 'Active',
            Notification::STATUS_FAILED => 'Failed',
        ];
        foreach ($conditions as $status => $label) {
            $notifications = Notification::find()->andWhere(['status' => $status])->all();
            if ($notifications) {
                $countActiveNotifications = count($notifications);
                echo "{$label} notifications({$countActiveNotifications}):\n";
                /** @var $notification Notification */
                foreach ($notifications as $notification) {
                    echo "to: {$notification->to}, message: {$notification->message}\n";
                }
            } else {
                echo "No {$label} notifications\n";
            }
        }
        return ExitCode::OK;
    }

    public function actionTest(): int
    {
        echo "Sending test message...\n";
        $result = $this->smsService->send('79876543210', 'proverka');

        echo $result ? 'Message sent' : 'Message not sent';
        return ExitCode::OK;
    }

    /**
     * @throws Exception
     */
    public function actionSend(): int
    {
        echo "Sending active messages...\n";
        $notifications = Notification::find()
            ->andWhere(['status' => Notification::STATUS_ACTIVE])
            ->all();
        $countNotifications = count($notifications);
        echo "Found {$countNotifications} active messages...\n";

        /** @var $notification Notification */
        foreach ($notifications as $notification) {
            echo "Sending notification to {$notification->to}\n";
            if ($this->smsService->send($notification->to, $notification->message)) {
                echo "Message sent\n";
                $notification->status = Notification::STATUS_SUCCESS;
            } else {
                echo "Message not sent\n";
                $notification->status = Notification::STATUS_FAILED;
            }
            $notification->save();
        }
        return ExitCode::OK;
    }
}
