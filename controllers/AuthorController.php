<?php

namespace app\controllers;

use app\models\Subscription;
use Yii;
use app\models\Author;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AuthorController implements the CRUD actions for Author model.
 */
class AuthorController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'subscribe'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'subscribe' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Author models.
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Author::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Author model.
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException|InvalidConfigException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $booksDataProvider = new ActiveDataProvider([
            'query' => $model->getBooks(),
        ]);

        return $this->render('view', [
            'model' => $model,
            'booksDataProvider' => $booksDataProvider,
        ]);
    }

    /**
     * Creates a new Author model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Author();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Author model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Author model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Subscribe to author's updates
     * @param integer $id Author ID
     * @return \yii\web\Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionSubscribe(int $id, string $guestPhone): \yii\web\Response
    {
        if (Yii::$app->request->isPost) {
            $author = $this->findModel($id);
            $subscription = new Subscription();

            if ($subscription->load(Yii::$app->request->post()) &&$subscription->save()) {
                Yii::$app->session->setFlash('success', 'Successfully subscribed to author');
                $subscription->sendSms($guestPhone, $author);
            } else {
                Yii::$app->session->setFlash('error', 'Error subscribing to author');
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the Author model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Author the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Author
    {
        if (($model = Author::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
