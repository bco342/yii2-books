<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var ArrayDataProvider $dataProvider */
/** @var array $years */
/** @var int|null $year */

$this->title = Yii::t('app', 'Top Authors') . ($year ? " for $year" : '');
?>

<div class="report-top-authors">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="mb-3">
        <?= Html::a(Yii::t('app', 'All Time'),
            ['index'],
            ['class' => 'btn btn-outline-primary' . (!$year ? ' active' : '')]
        ) ?>
        <?php foreach ($years as $y): ?>
            <?= Html::a($y, ['index', 'year' => $y], [
                'class' => 'btn btn-outline-primary' . ($year == $y ? ' active' : '')
            ]) ?>
        <?php endforeach; ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'name', 'label' => Yii::t('app', 'Author')],
            ['attribute' => 'books_count', 'label' => Yii::t('app', 'Books Count')],
        ],
    ]) ?>
</div>
