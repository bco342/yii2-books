<?php

namespace app\services;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use yii\httpclient\Client;

class SmsService extends Component
{
    /**
     * Send SMS notification
     *
     * @param string $phone Phone number
     * @param string $message Message text
     * @return bool Whether the message was sent successfully
     */
    public function send(string $phone, string $message): bool
    {
        $params = [
            'send' => $message,
            'to' => $phone,
            'apikey' => Yii::$app->params['smsPilotApiKey'],
            'format' => 'json',
        ];
        try {
            $client = new Client();
            $response = $client->get(Yii::$app->params['smsPilotApiUrl'], $params)->send();

            if (!$response->isOk) {
                throw new \Exception($response->data, (int)$response->statusCode);
            }
            $result = $response->getData();

            Yii::info("SMS sent to {$phone}: {$message}. Response: " . Json::encode($result), 'sms');

            return isset($result['send']) && intval($result['send'][0]['status']) >= 0;
        } catch (\Exception $e) {
            Yii::error("Failed to send SMS to {$phone}: " . $e->getMessage(), 'sms');
            return false;
        }
    }
}
