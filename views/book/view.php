<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'year',
            'description:ntext',
            'isbn',
            [
                'attribute' => 'authors',
                'value' => implode(', ', array_map(function($author) {
                    return $author->full_name;
                }, $model->authors)),
            ],
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => $model->image ? Html::img($model->image, ['width' => '200px']) : null,
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
