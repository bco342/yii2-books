<?php

namespace app\controllers;

use app\models\forms\SubscriptionForm;
use app\models\Notification;
use app\models\Author;
use Yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SubscriptionController extends Controller
{
    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionIndex($id): Response|string
    {
        $author = Author::findOne($id);
        if (!$author) {
            throw new NotFoundHttpException('Author not found.');
        }
        $model = new SubscriptionForm(['id' => $id]);

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $notification = new Notification([
                    'status' => Notification::STATUS_ACTIVE,
                    'to' => $model->guestPhone,
                    'message' => Yii::t('app',
                        'You are subscribed to Author: {author}',
                        ['author' => $author->full_name]
                    ),
                ]);
                $notification->save();

                Yii::$app->session->setFlash('success', Yii::t('app', 'Please check your phone to verify your subscription.'));
                return $this->redirect(['author/view', 'id' => $id]);
            }
            Yii::$app->session->setFlash('error', Yii::t('app', 'Error. Please check your phone number.'));
        }
        return $this->render('index', [
            'model' => $model,
            'author' => $author
        ]);
    }
}