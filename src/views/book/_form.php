<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */
/* @var $authors app\models\Author[] */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= Html::img($model->image, ['width' => '100px']) ?>
    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'authors')->checkboxList($authors, [
        'style' => 'max-height:20vh;display:flex;flex-flow:column wrap;overflow:auto'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), [
            'class' => 'btn btn-success'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
