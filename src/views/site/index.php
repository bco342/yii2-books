<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Book Management System');
?>
<div class="site-index">
    <div class="jumbotron text-center bg-transparent mt-3 mb-4">
        <h1 class="display-4"><?= Yii::t('app', 'Welcome!') ?></h1>
        <p class="lead"><?= Yii::t('app', 'Explore our Book Collection') ?></p>
    </div>

    <div class="body-content">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2><?= Yii::t('app', 'Latest Books') ?></h2>
                    <div>
                        <?= Html::a(Yii::t('app', 'Reports'), ['/report'], ['class' => 'btn btn-outline-primary']) ?>
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <?= Html::a(Yii::t('app', 'Add new book'), ['/book/create'], ['class' => 'btn btn-outline-success ml-2']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_book_item',
            'layout' => '<div class="row">{items}</div>{pager}',
            'itemOptions' => [
                'class' => 'col-md-3 mb-4'
            ],
        ]) ?>
    </div>
</div>