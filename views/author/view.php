<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Author */
/* @var $booksDataProvider yii\data\ActiveDataProvider */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        <?= Html::a('Subscribe', ['/subscription', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'full_name',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <h2>Books by <?= Html::encode($model->full_name) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $booksDataProvider,
        'columns' => [
            'title',
            'year',
            'isbn',
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function($model) {
                    return $model->image ? Html::img('@web/' . $model->image, ['width' => '100px']) : '';
                }
            ],
        ],
    ]); ?>

</div>
