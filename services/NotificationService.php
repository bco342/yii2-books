<?php

namespace app\services;

use Yii;
use app\models\Book;
use app\models\Notification;
use app\models\Subscription;

class NotificationService
{
    private SmsService $smsService;
    
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    
    /**
     * Notify subscribers about new books
     * 
     * @param Book $book The new book
     * @return void
     */
    public function notifyAboutNewBook(Book $book)
    {
        // Find all active subscriptions
        $subscriptions = Subscription::find()
            ->where(['status' => Notification::STATUS_ACTIVE])
            ->all();
            
        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;
            if ($user && $user->phone) {
                $message = $this->formatBookMessage($book);
                $this->smsService->send($user->phone, $message);
            }
        }
    }
    
    /**
     * Format book notification message
     * 
     * @param Book $book
     * @return string
     */
    private function formatBookMessage(Book $book): string
    {
        return Yii::t('app', 'New book available: {title}. Check it out in our library!', [
            'title' => $book->title
        ]);
    }
}
