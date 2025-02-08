<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Books');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <?= Html::a(
            Yii::t('app', 'Create Book'),
            ['create'],
            ['class' => 'btn btn-success']
        ) ?>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'title',
            'year',
            'isbn',
            [
                'attribute' => 'authors',
                'value' => function ($model) {
                    return implode(', ', array_map(function ($author) {
                        return $author->full_name;
                    }, $model->authors));
                },
            ],
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->image ? Html::img($model->image,
                        ['width' => '100px']
                    ) : '';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => !Yii::$app->user->isGuest,
                    'delete' => !Yii::$app->user->isGuest,
                ],
            ],
        ],
    ]); ?>
</div>
