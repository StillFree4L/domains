<?php

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="/img/free-icon-computing-90808.png" rel="icon">
    <link href="/img/apple-touch-icon.png" rel="apple-touch-icon">
<?php
    $this->registerCssFile('https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700');
    $this->registerCssFile('@web/lib/bootstrap/css/bootstrap.min.css');
    $this->registerCssFile('@web/lib/font-awesome/css/font-awesome.min.css');
    $this->registerCssFile('@web/lib/animate/animate.min.css');
    $this->registerCssFile('@web/lib/ionicons/css/ionicons.min.css');
    $this->registerCssFile('@web/lib/owlcarousel/assets/owl.carousel.min.css');
    $this->registerCssFile('@web/lib/lightbox/css/lightbox.min.css');
    $this->registerCssFile('@web/css/style.css');
    ?>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>
<!--==========================
  Header
============================-->
<header id="header">
    <div class="container-fluid">
        <div id="logo" class="pull-left">
            <h1><?= Html::a('SK', ['/site/index'],['options'=>['class'=>'scrollto']]) ?></h1>
            <!-- Uncomment below if you prefer to use an image logo -->
            <!-- <a href="#intro"><img src="img/logo.png" alt="" title="" /></a>-->
        </div>

        <nav id="nav-menu-container">
            <ul class="nav-menu">
                <li class="menu-active"><?= Html::a('Главная', ['/site/index']) ?></li>
                <?php if (Yii::$app->user->isGuest): 
                    ?>
                <li><?= Html::a('Авторизация', ['/user/login']) ?></li>
                <?php endif; 
                ?>
                <?php //if (!Yii::$app->user->isGuest): 
                    ?>
                <li><?= Html::a('Выйти', ['/user/logout']) ?></li>
                <li><?= Html::a('Пользователи', ['/admin/auth/index']) ?></li>
                <li><?= Html::a('Заказы', ['/admin/repairs/index']) ?></li>
                <li><?= Html::a('Мастера', ['/admin/master/index']) ?></li>
                <li><?= Html::a('Журнал', ['/admin/repairs-audit/index']) ?></li>
                <?php //endif; 
                ?>
                <li><?= Html::a('Сертификаты', ['/site/sertificat']) ?></li>
                <li><?= Html::a('Контакты', ['/site/contact']) ?></li>
            </ul>
        </nav><!-- #nav-menu-container -->
    </div>
</header><!-- #header -->

<!--==========================
  Intro Section
============================-->
<section id="intro">
    <div class="intro-container">
        <div id="introCarousel" class="carousel  slide carousel-fade" data-ride="carousel">

            <ol class="carousel-indicators"></ol>

            <div class="carousel-inner" role="listbox">

                <div class="carousel-item active" style="background-image: url('/img/intro-carousel/1.jpg');">
                    <div class="carousel-container">
                        <div class="carousel-content">
                            <h2>Сообщите о проблеме</h2>
                            <p>Звоните в наш центр оставляете заявку. Если требуется диагностика и ремонт доставляете вашу технику к нам получаете квитанцию и ждете звонка мастера.</p>
                            <a href="#featured-services" class="btn-get-started scrollto">Прайс на услуги</a>
                        </div>
                    </div>
                </div>

                <div class="carousel-item" style="background-image: url('/img/intro-carousel/2.jpg');">
                    <div class="carousel-container">
                        <div class="carousel-content">
                            <h2>Диагностика</h2>
                            <p>Наш мастер производит диагностику, сообщит о проблеме и предложит решение. Если вы согласны на ремонт, то стоимость диагностики - БЕСПЛАТНО, если нет оплачиваете диагностику, забираете технику.</p>
                            <a href="#featured-services" class="btn-get-started scrollto">Прайс на услуги</a>
                        </div>
                    </div>
                </div>

                <div class="carousel-item" style="background-image: url('/img/intro-carousel/3.jpg');">
                    <div class="carousel-container">
                        <div class="carousel-content">
                            <h2>Ремонт</h2>
                            <p>Мастер выполняет заранее договоренный ремнт, звонит вам. Вы приходите предъявляете квитанцию, проверяете исправную технику производите денежный расчет с мастером.</p>
                            <a href="#featured-services" class="btn-get-started scrollto">Прайс на услуги</a>
                        </div>
                    </div>
                </div>

            </div>

            <a class="carousel-control-prev" href="#introCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon ion-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>

            <a class="carousel-control-next" href="#introCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon ion-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>

        </div>
    </div>
</section><!-- #intro -->

<?=$content?>

<!--==========================
  Footer
============================-->
<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-md-6 footer-info">
                    <h3>SK</h3>
                    <p>Обслуживание компьютеров, оргтехники, заправка картриджей, обслуживание пожарно-охранной сигнализации, видеонаблюдение и монтажные работы.</p>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Полезные ссылки</h4>
                    <ul>
                        <li><i class="ion-ios-arrow-right"></i> <?= Html::a('Главная', ['/site/index']) ?></li>
                        <li><i class="ion-ios-arrow-right"></i> <?= Html::a('Сертификаты', ['/site/sertificat']) ?></li>
                        <li><i class="ion-ios-arrow-right"></i><?= Html::a('Контакты', ['/site/contact']) ?></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-contact">
                    <h4>Контакты</h4>
                    <p>
                        проспект Н. Абдирова 47/1 офис 63 <br>
                        город Караганда<br>
                        Республика Казахстан <br>
                        <strong>Телефон:</strong> 8 (705) 910-22-00<br>
                        <strong>E-mail:</strong> Sk@Statuskrg.Kz Statuskaraganda@Gmail.Com<br>
                    </p>

                    <div class="social-links">
                        <a href="https://twitter.com/" class="twitter"><i class="fa fa-twitter"></i></a>
                        <a href="https://www.facebook.com/statuskaraganda/" class="facebook"><i class="fa fa-facebook"></i></a>
                        <a href="https://www.instagram.com/too_status_karaganda/" class="instagram"><i class="fa fa-instagram"></i></a>
                        <a href="https://api.whatsapp.com/send/?phone=77059102200&text&app_absent=0" class="whatsapp"><i class="fa fa-whatsapp"></i></a>
                        <a href="https://vk.com/s_krg" class="vk"><i class="fa fa-vk"></i></a>
                    </div>

                </div>

                <div class="col-lg-3 col-md-6 footer-newsletter">
                    <h4>Наша рассылка</h4>
                    <p>Подписка на новости позволит вам всегда быть в курсе событий, скиндок и акций в нашей компании.</p>
                    <form action="" method="post">
                        <input type="email" name="email"><input type="submit"  value="Подписаться">
                    </form>
                </div>

            </div>
        </div>
    </div>
</footer><!-- #footer -->

<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
<?php
$this->registerJsFile('@web/lib/jquery/jquery.min.js');
$this->registerJsFile('@web/lib/jquery/jquery-migrate.min.js');
$this->registerJsFile('@web/lib/bootstrap/js/bootstrap.bundle.min.js');
$this->registerJsFile('@web/lib/easing/easing.min.js');
$this->registerJsFile('@web/lib/superfish/hoverIntent.js');
$this->registerJsFile('@web/lib/superfish/superfish.min.js');
$this->registerJsFile('@web/lib/wow/wow.min.js');
$this->registerJsFile('@web/lib/waypoints/waypoints.min.js');
$this->registerJsFile('@web/lib/counterup/counterup.min.js');
$this->registerJsFile('@web/lib/owlcarousel/owl.carousel.min.js');
$this->registerJsFile('@web/lib/isotope/isotope.pkgd.min.js');
$this->registerJsFile('@web/lib/lightbox/js/lightbox.min.js');
$this->registerJsFile('@web/lib/touchSwipe/jquery.touchSwipe.min.js');
$this->registerJsFile('@web/contactform/contactform.js');
$this->registerJsFile('@web/js/main.js');
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

