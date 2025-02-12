<?php

use app\models\Author;
use app\models\forms\SubscriptionForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\View;

/** @var $this View */
/** @var $form ActiveForm */
/** @var $model SubscriptionForm */
/** @var Author $author */

$this->title = Yii::t('app', 'Subscription');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to subscribe to author: {author}',
            ['author' => $author->full_name]
        ) ?>:</p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'subscription-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'guestPhone')->textInput(
                ['autofocus' => true, 'type' => 'tel', 'pattern' => '+[7]-[0-9]{3}-[0-9]{7}']
            ) ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton('Subscribe', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
