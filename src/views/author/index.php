<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t(  'app', 'Authors');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
    <p>
        <?= Html::a(Yii::t(  'app', 'Create Author'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'full_name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {subscribe}',
                'buttons' => [
                    'subscribe' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-bell"></span>',
                            ['subscribe', 'id' => $model->id],
                            [
                                'title' => Yii::t(  'app', 'Subscribe'),
                                'data-method' => 'post',
                                'data-confirm' => Yii::t(  'app', 'Are you sure you want to subscribe to this author?')
                            ]
                        );
                    },
                ],
                'visibleButtons' => [
                    'update' => !Yii::$app->user->isGuest,
                    'delete' => !Yii::$app->user->isGuest,
                    'subscribe' => !Yii::$app->user->isGuest,
                ],
            ],
        ],
    ]); ?>
</div>
