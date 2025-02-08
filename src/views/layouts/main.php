<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\helpers\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag([
    'name' => 'viewport',
    'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no'
]);
$this->registerMetaTag([
    'name' => 'description',
    'content' => $this->params['meta_description'] ?? ''
]);
$this->registerLinkTag([
    'rel' => 'icon',
    'type' => 'image/x-icon',
    'href' => Yii::getAlias('@web/favicon.ico')
]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container">
            <?= Html::a(Yii::t('app', 'Book Management'),
                [Yii::$app->homeUrl],
                ['class' => 'navbar-brand']
            ) ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <?= Html::a(Yii::t('app', 'Books'),
                            ['/book'],
                            ['class' => 'nav-link']
                        ) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a(Yii::t('app', 'Authors'),
                            ['/author'],
                            ['class' => 'nav-link']
                        ) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a(Yii::t('app', 'Reports'),
                            ['/report'],
                            ['class' => 'nav-link']
                        ) ?>
                    </li>
                </ul>
                <ul class="navbar-nav ms-md-auto">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <li class="nav-item">
                            <?= Html::a(Yii::t('app', 'Login'),
                                ['/login'],
                                ['class' => 'nav-link pull-right']
                            ) ?>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <?= Html::beginForm(['/logout']) ?>
                            <?= Html::submitButton(
                                Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                                ['class' => 'nav-link btn btn-link logout']) ?>
                            <?= Html::endForm() ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<?= Alert::widget() ?>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; Book Management <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
