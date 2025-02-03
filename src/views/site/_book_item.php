<?php
use yii\helpers\Html;

/** @var app\models\Book $model */
?>

<div class="card h-100">
    <?php if ($model->image): ?>
        <img src="<?= Html::encode($model->image) ?>" class="card-img-top" alt="<?= Html::encode($model->title) ?>" style="height: 200px; object-fit: cover;">
    <?php endif; ?>
    
    <div class="card-body">
        <h5 class="card-title"><?= Html::encode($model->title) ?></h5>
        <p class="card-text">
            <small class="text-muted">
                <?= Yii::t('app', 'By') ?>: <?= Html::encode(implode(', ', array_map(function($author) {
                    return $author->shortName;
                }, $model->authors))) ?>
            </small>
        </p>
        <p class="card-text">
            <?= Html::encode(\yii\helpers\StringHelper::truncate($model->description, 100)) ?>
        </p>
    </div>
    
    <div class="card-footer bg-transparent">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted"><?= Yii::t('app', 'Published') ?>: <?= Yii::$app->formatter->asDate($model->created_at) ?></small>
            <?= Html::a(Yii::t('app', 'View Details'), ['book/view', 'id' => $model->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
        </div>
    </div>
</div>