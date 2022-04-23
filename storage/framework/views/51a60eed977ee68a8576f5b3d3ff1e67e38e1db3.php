<?php if(Auth::user() && $u->ban): ?>
Вы были забанены! Причина: Нарушение правил сайта.
<?php else: ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo e($settings->title); ?></title>
  <meta charset="UTF-8">

  <meta content="<?php echo e($settings->desc); ?>" name="description">
  <meta content="<?php echo e($settings->keys); ?>" name="keywords">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
  <meta content="<?php echo e(csrf_token()); ?>" name="csrf-token">
  <meta property="og:image" content="https://<?php echo e($settings->domain); ?>/assets/images/logo_m.png" />
  <link rel="icon" type="image/png" href="https://<?php echo e($settings->domain); ?>/assets/images/favicon.png">
  <link id="css_theme" rel="stylesheet" href="/assets/css/style_dark.css?v=32">
  <link rel="stylesheet" href="https://<?php echo e($settings->domain); ?>/assets/css/notifyme.css">
 <link rel="stylesheet" href="/assets/css/tooltipster.bundle.min.css?v=1">
<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
    integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <script type="text/javascript" src="https://<?php echo e($settings->domain); ?>/assets/js/jquery-1.9.1.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
  <script type="text/javascript" src="https://<?php echo e($settings->domain); ?>/assets/js/jquery.slimscroll.min.js"></script>
  <script type="text/javascript" src="https://<?php echo e($settings->domain); ?>/assets/js/jquery.cookie.js"></script>
  <script type="text/javascript" src="https://<?php echo e($settings->domain); ?>/assets/js/jquery.kinetic.min.js"></script>
  <script type="text/javascript" src="https://<?php echo e($settings->domain); ?>/assets/js/tooltipster.bundle.min.js?v=1"></script>
  <script type="text/javascript" src="https://<?php echo e($settings->domain); ?>/assets/js/notifyme.min.js"></script>
  <script type="text/javascript" src="https://<?php echo e($settings->domain); ?>/assets/js/script.js?v=26"></script>
<script>
const domain = 'luckyroul.ru';
const mainPort = '8443';
</script>
  <?php if(auth()->guard()->check()): ?>
  <script>
  const USER_ID = '<?php echo e($u->id); ?>';
  const youtuber = '<?php echo e($u->is_youtuber); ?>';
  const admin = '<?php echo e($u->is_admin); ?>';
  const moder = '<?php echo e($u->is_moder); ?>';
  var current_balance = '<?php echo e($u->balance); ?>';
  $('#money_mob').text(beautifyBalance(current_balance));
  </script>
  <?php else: ?>
  <script>
  const USER_ID = null;
  const youtuber = null;
  const admin = null;
  const moder = null;
  </script>
  <?php endif; ?>
</head>
<body>

  <!-- Навигация -->
<div class="navbar">

    <a class="navbar-brand" href="/">
      <img src="/assets/images/logo_new_year3.png" alt="">
    </a>

    <div class="menu">
      <ul class="menu__list">
        <li class="menu__list-item"><a href="/" class=" menu__list-item__link">Игры</a></li>
		<li class="menu__list-item"><a href="/reviews" class=" menu__list-item__link">Отзывы</a></li>
        <li class="menu__list-item"><a href="/bonus" class=" menu__list-item__link">Бонусы</a></li>
        <li class="menu__list-item"><a href="/top/daily" class=" menu__list-item__link">Топ игроков</a></li>
        <li class="menu__list-item"><a href="/faq" class=" menu__list-item__link">Помощь</a></li>
 <?php if(Auth::check() && $u->is_admin): ?>

       <li class="menu__list-item"><a href="/admin" class=" menu__list-item__link">Админ-панель</a></li>

         <?php endif; ?>
      </ul>
    </div>
<?php if(auth()->guard()->check()): ?>
    <div class="user">
            <div class="profile-component">
        <div class="money-block">
          <span class="money-block__money-icon myicon-coins"></span>
          <span id="money_mob" class="money-block__money-area"><?php echo e($u->balance); ?></span>
          <div class="money-block__actions">
            <a href="/wallet/deposit" class="wallet-link">
                Кошелек
            </a>
          </div>
        </div>
      </div>
          </div>

      <a class="menu-button profile-link " href="/profile">
      <i class="myicon-user"></i>
    </a>
    <?php else: ?>
    
      <a href="/" rel="popup" data-popup="popup-auth" class="auth-btn"
        title="Вывести"><div class="ico ico-vk"></div><i class="myicon-vk"></i> Авторизация</a>
    
          <?php endif; ?>
        <a class="menu-button chat-toggle btn-toggle3" type="button">
      <i class="myicon-chat"></i>
    </a>

  </div>

  <div class="mobile-menu">

    <div class="mobile-menu__contents">
      <div style="display: none;" class="mobile-menu__submenu mobile-menu__submenu_games">
        <div class="mobile-menu__submenu__vertical-divider"></div>
        <div class="active mobile-menu__submenu-item">
          <a href="/jackpot" class="mobile-menu__submenu-item__link mobile-menu__submenu-item_first-in-row">
            <div class="mobile-menu__submenu-item__link-wrapper">
              <span class="lightable mobile-menu__submenu-item__link-icon myicon-jackpot"></span>

             <div class="mobile-menu__submenu-item__link__right">
                <div class="mobile-menu__submenu-item__link-name">Jackpot</div>
                </div>
            </div>
          </a>
        </div>

        <div class=" mobile-menu__submenu-item mobile-menu__submenu-item_first-in-row">
          <a href="/pvp" class="mobile-menu__submenu-item__link">
            <div class="mobile-menu__submenu-item__link-wrapper">
              <span class="lightable mobile-menu__submenu-item__link-icon myicon-bomb"></span>
              <div class="mobile-menu__submenu-item__link__right">
                <div class="mobile-menu__submenu-item__link-name mobile-menu__submenu-item__link-name_no-margin">PVP</div>
              </div>
            </div>
          </a>
        </div>


        <div class=" mobile-menu__submenu-item mobile-menu__submenu-item_last-in-row">
          <a href="/double" class="mobile-menu__submenu-item__link">
            <div class="mobile-menu__submenu-item__link-wrapper">
              <span class="lightable mobile-menu__submenu-item__link-icon myicon-wheel"></span>
              <div class="mobile-menu__submenu-item__link__right">
                <div class="mobile-menu__submenu-item__link-name">Double</div>
                </div>
            </div>
          </a>
        </div>



        <div class=" mobile-menu__submenu-item">
          <a href="/crash" class="mobile-menu__submenu-item__link mobile-menu__submenu-item_first-in-row">
            <div class="mobile-menu__submenu-item__link-wrapper">
              <span class="lightable mobile-menu__submenu-item__link-icon myicon-graph"></span>
              <div class="mobile-menu__submenu-item__link__right">
                <div class="mobile-menu__submenu-item__link-name">Crash</div>
                </div>
            </div>
          </a>
        </div>



        <div class=" mobile-menu__submenu-item mobile-menu__submenu-item_last-in-row">
          <a href="/battle" class="mobile-menu__submenu-item__link">
            <div class="mobile-menu__submenu-item__link-wrapper">
              <span class="lightable mobile-menu__submenu-item__link-icon myicon-sword"></span>
              <div class="mobile-menu__submenu-item__link__right">
                <div class="mobile-menu__submenu-item__link-name">Battle</div>
                 </div>
            </div>
          </a>
        </div>


        <div class=" mobile-menu__submenu-item mobile-menu__submenu-item__games-no-balance mobile-menu__submenu-item_last-in-column mobile-menu__submenu-item_first-in-row">
          <a href="/dice" class="mobile-menu__submenu-item__link">
            <div class="mobile-menu__submenu-item__link-wrapper">
              <span class="lightable mobile-menu__submenu-item__link-icon myicon-dice"></span>
              <div class="mobile-menu__submenu-item__link__right">
                <div class="mobile-menu__submenu-item__link-name mobile-menu__submenu-item__link-name_no-margin">Dice</div>
              </div>
            </div>
          </a>
        </div>
		
		<div class=" mobile-menu__submenu-item mobile-menu__submenu-item__games-no-balance mobile-menu__submenu-item_last-in-column mobile-menu__submenu-item_first-in-row">
          <a href="/raffle" class="mobile-menu__submenu-item__link">
            <div class="mobile-menu__submenu-item__link-wrapper">
              <span class="lightable mobile-menu__submenu-item__link-icon myicon-raffle"></span>
              <div class="mobile-menu__submenu-item__link__right">
                <div class="mobile-menu__submenu-item__link-name mobile-menu__submenu-item__link-name_no-margin">Raffle</div>
              </div>
            </div>
          </a>
        </div>




      </div>



      <div style="display: none;" class="mobile-menu__submenu mobile-menu__submenu_more">
        <div class="mobile-menu__submenu__vertical-divider"></div>



        <div class=" mobile-menu__submenu-item_usual-icons mobile-menu__submenu-item__games-no-balance mobile-menu__submenu-item mobile-menu__submenu-item_last-in-row">
          <a href="/top/daily" class="mobile-menu__submenu-item__link">
            <div class="mobile-menu__submenu-item__link-wrapper">
              <span class="mobile-menu__submenu-item__link-icon myicon-info"></span>
              <div class="mobile-menu__submenu-item__link__right">
                <div class="mobile-menu__submenu-item__link-name mobile-menu__submenu-item__link-name_no-margin">Топ игроков</div>
              </div>
            </div>
          </a>
        </div>






        <div class=" mobile-menu__submenu-item_usual-icons mobile-menu__submenu-item__games-no-balance mobile-menu__submenu-item mobile-menu__submenu-item_last-in-row">
          <a href="/faq" class="mobile-menu__submenu-item__link">
            <div class="mobile-menu__submenu-item__link-wrapper">
              <span class="mobile-menu__submenu-item__link-icon myicon-info"></span>
              <div class="mobile-menu__submenu-item__link__right">
                <div class="mobile-menu__submenu-item__link-name mobile-menu__submenu-item__link-name_no-margin">Помощь</div>
              </div>
            </div>
          </a>
        </div>
 <?php if(Auth::check() && $u->is_admin): ?>

        <div class=" mobile-menu__submenu-item_usual-icons mobile-menu__submenu-item__games-no-balance mobile-menu__submenu-item mobile-menu__submenu-item_last-in-row">
          <a href="/admin" class="mobile-menu__submenu-item__link">
            <div class="mobile-menu__submenu-item__link-wrapper">
              <span class="mobile-menu__submenu-item__link-icon myicon-info"></span>
              <div class="mobile-menu__submenu-item__link__right">
                <div class="mobile-menu__submenu-item__link-name mobile-menu__submenu-item__link-name_no-margin">Админ-панель</div>
              </div>
            </div>
          </a>
        </div>

         <?php endif; ?>
        <div class="mobile-menu__submenu-item mobile-menu__submenu-item_social mobile-menu__submenu-item_last-in-row">
          <a href="<?php echo e($settings->vk_url); ?>" class="mobile-menu__submenu-item_social-link" target="_blank">
            <i class="myicon-vk"></i>
          </a>

        <a href="<?php echo e($settings->tg_url); ?>" class="mobile-menu__submenu-item_social-link" target="_blank">
            <i class="myicon-telegram"></i>
          </a>
          <div class="mobile-online"> 
            <span class="active-dot"></span>7<span class="on">0</span>
        
          </div>
                  </div>



      </div>



      <a href="#" data-submenu="mobile-menu__submenu_games" class="open-submenu mobile-menu__link">
        <span class="myicon-console mobile-menu__link-icon"></span>
        Игры
      </a>
      <a href="/profile" class="mobile-menu__link ">
        <span class="myicon-user mobile-menu__link-icon lightable"></span>
        Профиль
      </a>
      <a href="#" class="btn-toggle3 mobile-menu__link">
        <span class="myicon-chat mobile-menu__link-icon"></span>
        Чат
      </a>

      <a href="/bonus" class="mobile-menu__link ">
        <span class="myicon-bonus mobile-menu__link-icon"></span>
        Бонус
      </a>

      <a href="#" data-submenu="mobile-menu__submenu_more" class="open-submenu mobile-menu__link">
        <span class="myicon-menu mobile-menu__link-icon"></span>
        Еще
      </a>
    </div>
  </div>

  <div class="fixed-nav">
    <ul class="leftside"> 
	  <li>
        <a href="/jackpot" title="Jackpot"
          class="tooltip-game mode <?php echo e(Request::is('jackpot') ? 'active' : ''); ?>">
          <i class="myicon-jackpot"></i>
        </a>
      </li>
	   
      <li>
        <a href="/pvp" title="PVP"
          class="tooltip-game mode <?php echo e(Request::is('mines') ? 'active' : ''); ?>">
          <i class="myicon-bomb"></i>
        </a>
      </li>

      <li>
        <a href="/double" title="Double"
          class="tooltip-game mode <?php echo e(Request::is('double') ? 'active' : ''); ?>">
          <i class="myicon-wheel"></i>
        </a>
      </li>

      <li>
        <a href="/battle" title="Battle" class="tooltip-game mode <?php echo e(Request::is('battle') ? 'active' : ''); ?>">
          <i class="myicon-sword"></i>
        </a>
      </li>

      <li>
        <a href="/crash" title="Crash" class="tooltip-game mode <?php echo e(Request::is('crash') ? 'active' : ''); ?>">
          <i class="myicon-graph"></i>
        </a>
      </li>

      <li>
        <a href="/dice" title="Dice" class="tooltip-game mode <?php echo e(Request::is('dice') ? 'active' : ''); ?>">
          <i class="myicon-dice"></i>
        </a>
      </li>
	  
	  <li>
        <a href="/raffle" title="Raffle" class="tooltip-game mode <?php echo e(Request::is('raffle') ? 'active' : ''); ?>">
          <i class="myicon-raffle"></i>
        </a>
      </li>


      <li class="leftside__bottom-part">
        <a href="<?php echo e($settings->vk_url); ?>" title="Группа VK" target="_blank" class="mode mode_white tooltip-game">
          <i class="myicon-vk"></i>
        </a>
      </li>
      <li>
        <a href="<?php echo e($settings->tg_url); ?>" title="Группа Telegram" target="_blank" class="mode mode_white tooltip-game tooltipstered">
          <i class="myicon-telegram"></i>
        </a>
      </li>
      <li>
        <span class="mode mode_white tooltip-game site-onl tooltipstered">
          <span class="active-dot"></span>7<span class="on">0</span> 
        
      </span></li>


          </ul>
  </div>
<!--
<div class="navbar">
    <button class="hamburger hamburger--arrowturn btn-toggle-menu" type="button">
      <span class="hamburger-box">
        <span class="hamburger-inner"></span>
      </span>
    </button>
    <div class="separator"></div>

    <a class="navbar-brand" href="/">
      <img src="/assets/images/logo_new_year.png" alt="">
    </a>


 <?php if(auth()->guard()->check()): ?>
    <div class="balance">

      <span class="myicon-coins"></span>
      <span id="money_mob"><?php echo e($u->balance); ?></span>
      <a href="/" rel="popup" data-popup="popup-pay" class="tooltip tooltipstered"><i class="myicon-plus"></i></a>
      <a href="/" rel="popup" data-popup="popup-withdraw" class="tooltip tooltipstered"><i class="myicon-minus"></i></a>
    </div>

        <ul class="navbar-nav">
        <li class="nav-item exit">
      <a class="nav-link" href="/logout">Выход</a>
    </li>
      </ul>

  <div class="user">

        <div class="profile">
      <img src="<?php echo e($u->avatar); ?>" class="profile-ava">
      <img class="rank tooltip tooltipstered" src="{" alt="">
      <div class="info">
        <div class="username">
          <?php echo e($u->username); ?>

          <a href="/logout" class="logout tooltip tooltipstered"><i class="myicon-logout"></i></a>
        </div>

        <div class="money">
          <span class="myicon-coins"></span>
          <span id="money"><?php echo e($u->balance); ?></span>
          <a href="/" rel="popup" data-popup="popup-pay" class="tooltip tooltipstered"><i class="myicon-plus"></i></a>
          <a href="/" rel="popup" data-popup="popup-withdraw" class="tooltip tooltipstered"><i class="myicon-minus"></i></a>
      </div>
      </div>
    </div>
      </div>
<?php else: ?>
 <div class="user">

        <div class="profile">

    </div>
      </div>
      <?php endif; ?>
  <div class="separator"></div>
  <button class="chat-toggle btn-toggle3" type="button">
    <i class="myicon-chat"></i>
  </button>
</div>

<div class="fixed-nav active">
   <a class="btn-toggle-menu"></a>

  <ul class="leftside">

   <?php if(Auth::guest()): ?>
          <li><a href="/auth/vkontakte"><i class="myicon-vk"></i><div class="name">Авторизация</div></a></li>

  <?php endif; ?>
 <?php if(Auth::check() && $u->is_admin): ?>
    <li><a href="/admin" class="menu"><i class="fas fa-crown"></i><div class="name">Панель управления</div></a></li>
    <?php endif; ?>

    <li>
      <a href="/" class="mode <?php echo e(Request::is('jackpot') ? 'active' : ''); ?>">
        <i class="myicon-jackpot"></i>
        <div class="name">Jackpot</div>
        <span class="new">
          <b id="jackpot_bank"><?php echo e($gamesbank['jackpot']); ?></b>
          <i class="myicon-coins"></i>
        </span>
      </a>
    </li>
    <li><a href="/double" class="mode <?php echo e(Request::is('double') ? 'active' : ''); ?>"><i class="myicon-wheel"></i><div class="name">Double</div><span class="new"><b id="double_bank"><?php echo e($gamesbank['double']); ?></b> <i class="myicon-coins"></i></span></a></li>
    <li><a href="/battle" class="mode <?php echo e(Request::is('battle') ? 'active' : ''); ?>"><i class="myicon-sword"></i><div class="name">Battle</div><span class="new"><b id="battle_bank"><?php echo e($gamesbank['battle']); ?></b> <i class="myicon-coins"></i></span></a></li>
    <li><a href="/crash" class="mode <?php echo e(Request::is('crash') ? 'active' : ''); ?>"><i class="myicon-graph"></i><div class="name">Crash</div><span class="new"><b id="crash_bank"><?php echo e($gamesbank['crash']); ?></b> <i class="myicon-coins"></i></span></a></li>
    <li><a href="/dice" class="mode <?php echo e(Request::is('dice') ? 'active' : ''); ?>"><i class="myicon-dice"></i><div class="name">Dice</div></a></li>

<?php if(auth()->guard()->check()): ?>
  <li><a href="/" class="menu" rel="popup" data-popup="popup-pay"><i class="myicon-wallet"></i><div class="name">Пополнить</div></a></li>
    <li><a href="" class="menu" rel="popup" data-popup="popup-withdraw"><i class="myicon-withdraw"></i><div class="name">Вывести</div></a></li>


    <li><a href="/top/partners" class="menu <?php echo e(Request::is('top/partners') ? 'active' : ''); ?>"><i class="myicon-team"></i><div class="name">Топ партнеров</div></a></li>
    <li><a href="/fair" class="menu <?php echo e(Request::is('fair') ? 'active' : ''); ?>"><i class="myicon-high-five"></i><div class="name">Честная игра</div></a></li>

        <li><a href="/pay/history" class="menu <?php echo e(Request::is('pay/history') ? 'active' : ''); ?>"><i class="myicon-clipboard"></i><div class="name">История счета</div></a></li>
    <li><a href="/ref" class="menu <?php echo e(Request::is('ref') ? 'active' : ''); ?>"><i class="myicon-collaboration"></i><div class="name">Реферальная система</div></a></li>
    <li><a href="/bonus" class="menu <?php echo e(Request::is('bonus') ? 'active' : ''); ?>"><i class="myicon-bonus"></i><div class="name">Бонусы</div></a></li>

    <li><a href="/faq" class="menu <?php echo e(Request::is('faq') ? 'active' : ''); ?>"><i class="myicon-info"></i><div class="name">FAQ</div></a></li>
    <li><a href="/rules" class="menu <?php echo e(Request::is('rules') ? 'active' : ''); ?>"><i class="myicon-documents"></i><div class="name">Правила использования</div></a></li>
    <li><a href="<?php echo e($settings->vk_url); ?>" target="_blank" class="menu"><i class="myicon-vk"></i><div class="name">Наша группа</div></a></li>

        <li><a href="/logout"><i class="myicon-logout"></i><div class="name">Выйти</div></a></li>
        <?php else: ?>
    <li><a href="/top/partners" class="menu "><i class="myicon-team"></i><div class="name">Топ партнеров</div></a></li>
    <li><a href="/fair" class="menu "><i class="myicon-high-five"></i><div class="name">Честная игра</div></a></li>


    <li><a href="/faq" class="menu "><i class="myicon-info"></i><div class="name">FAQ</div></a></li>
    <li><a href="/rules" class="menu "><i class="myicon-documents"></i><div class="name">Правила использования</div></a></li>
    <li><a href="<?php echo e($settings->vk_url); ?>" target="_blank" class="menu"><i class="myicon-vk"></i><div class="name">Наша группа</div></a></li>
<?php endif; ?>


  </ul>
</div>-->

<img src="https://<?php echo e($settings->domain); ?>assets/images/favicon.png" style="display: none">
<div class="content">
  <div class="main-width">
    <?php echo $__env->yieldContent('content'); ?>
  </div>
</div>
<div class="fixed-chat ">
  <i class="far fa-comments icon"></i>

  <div class="heading">
      <a class="close myicon-close btn-toggle3"></a>
      <small >Раздача: <span id="gifttimer">00:00</span></small>
      <small> | </small>
      <small>Онлайн чат</small>
    </div>
  <div class="messages">
    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 727px;"><div class="scroller" style="overflow: hidden; width: auto; height: 727px;">
            <?php if($messages != 0): ?> <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="item" id="chatm_<?php echo e($sms['time2']); ?>">
        <div class="sub-heading clear">
          <div class="avatar"><img class="avatar-img" src="<?php echo e($sms['avatar']); ?>" alt=""></div>
          <div class="name" onclick="var u = $(this); $('.chat-input').val(u.text() + ', '); return false;"><?php if($sms['admin']): ?><span style="color:#ff5722;">[Администратор]</span> <?php elseif($sms['moder']): ?> <span style="color:#4caf50;">[Модератор]</span> <?php echo e($sms['username']); ?> <?php elseif($sms['youtuber']): ?><span style="color:#ffc107;">[YouTube`r]</span> <?php echo e($sms['username']); ?> <?php else: ?> <?php echo e($sms['username']); ?> <?php endif; ?></div>
          <div class="date"><?php if(Auth::user() and $u->is_admin == 1 || $u->is_moder == 1): ?><span onclick="var u = $(this); $('.chat-input').val('/ban ' + u.text() + ' '); return false;"><?php echo e($sms['user_id']); ?></span> <?php else: ?><span onclick="var u = $(this); $('.chat-input').val('/send ' + u.text() + ' '); return false;"><?php echo e($sms['user_id']); ?></span><?php endif; ?> <?php if(Auth::user() and $u->is_admin == 1 || $u->is_moder == 1): ?><a class="delete" onclick="chatdelet(<?php echo e($sms['time2']); ?>)"><i class="far fa-times-circle"></i></a><?php endif; ?></div>
        </div>
        <div class="text"><?php echo $sms['messages']; ?></div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>

           </div><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 5px; position: absolute; top: 459px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 268.017px;"></div><div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
  </div>
    <div class="send-form">

    <input type="text" class="chat-input" placeholder="Введите сообщение">
	
    <a class="btn-send"><i class="fab fa-telegram-plane"></i></a>
  </div>

  </div>

<div class="overlay">
  <?php if(!Auth::guest()): ?>
  <div class="popup popup-pay">
    <a class="close"></a>
    <div class="heading">Пополнить баланс</div>
    <form action="/pay" method="GET">
      <input type="text" class="input-sum" name="num" min="0" placeholder="Введите сумму">
      <div class="sum-info">Минимальная сумма: 1р</div>
      <div class="sum-info">Максимальная сумма: 15000р</div>
      <?php if($settings->dep_perc): ?>
      <input type="text" class="input-sum" name="dep_code" placeholder="Введите промокод">
      <?php endif; ?>
      <button type="submit" class="btn-second">ОПЛАТИТЬ</button>
    </form>
  </div>

  <div class="popup popup-withdraw">
    <a class="close"></a>
    <div class="heading">Вывод средств</div>
    <div class="important">ВЫВОД ДОСТУПЕН ПОСЛЕ ПОПОЛНЕНИЯ СЧЕТА НА 100 РУБЛЕЙ!</div>
    <div class="important">Обработка вывода обычно осуществляется в течении часа. В некоторых случаях платеж может быть
      обработан до 24 часов. Минимальная сумма к выводу <b id="min_wid">1050</b> монет</div>
      <h3>Выберите платежную систему</h3>
     <div class="list-pay clear">
      <div class="picked-method">
            Способ:
            <a class="item active qiwi-option" style='cursor: pointer;' data-type="qiwi"><img src="https://money-x.fun/assets/images/qiwi.png" alt="">
              Qiwi</a>
            <a class="item yandex-option" style='cursor: pointer;'  data-type="yandex"><img src="https://money-x.fun/assets/images/yandex.png" alt="">
              Yandex.Money</a>
            <a class="item visa-option" style='cursor: pointer;'  data-type="visa"><img src="https://money-x.fun/assets/images/visa.png" alt=""> Visa /
              Mastercard</a>
          </div>

        </div>
      <div class="with-inputs">
        <div class="params">
          <h3>Сумма</h3>
          <input type="text" class="input-sum" id="value" placeholder="Мин. сумма: 1050 монет">
        </div>
        <div class="params">
          <h3>Номер кошелька</h3>
          <input type="text" class="input-num" id="wallet" placeholder="7900xxxxxxx">
        </div>
      </div>
      <div class="contcom">
        <span class="coms1">
          Комиссия: <p class="right coms1" id="com">4% + 1руб.</p>
        </span>
        <span class="coms1 bold">
          ИТОГ К ПОЛУЧЕНИЮ: <p class="right coms1 bold" id="valwithcom">0.00 руб.</p>
        </span>
        <div class="clear"></div>
      </div>
      <div class="check_rules">
        <input id="chh" type="checkbox">
        <label class="check" for="chh">Я подтверждаю правильность введенных данных</label>
      </div>
      <input id="withdraw" type="submit" class="btn-second" value="ВЫВЕСТИ" readonly disabled>
    </div>
    <div class="popup popup-promo">
      <a class="close"></a>
      <div class="heading">Ваши промокоды</div>
      <div class="ref">
        <table>
          <thead>
            <tr>
              <th>Промокод</th>
              <th>Кол-во активаций</th>
              <th>Сумма</th>
              <th>Действие</th>
            </tr>
          </thead>
          <tbody id="promoshare">
            <?php $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr data-id="<?php echo e($p->code); ?>">
              <td><?php echo e($p->code); ?></td>
              <td><?php echo e($p->count_use); ?></td>
              <td><?php echo e($p->amount); ?> <i class="fas fa-coins"></i></td>
              <td><a class="btn" href="javascript:sharePromo('<?php echo e($p->code); ?>')">Поделиться в чате</a></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>
    <div class="popup popup-terms">
      <a class="close"></a>
      <div class="heading">Условия пользовательского соглашения</div>
      <div class="rules">
        <h3 class="rules-title">Общие правила сайта</h3>
        <ol class="decimal-list">
          <li>Данный документ является договором на использование сервиса OBABLO.SITE. Ниже приведены правила и условия использования Сервиса. Пожалуйста, внимательно ознакомьтесь с ними.</li>
          <li>Сервисом могут пользоваться лица старше 18 лет.</li>
          <li>Игра "OBABLO.SITE" - беспроигрышная онлайн игра принадлежащая организатору и находящаяся по адресу в сети интернет https://obablo.site. Организатором предоставляются услуги пользователю по организации его развлечения, досуга и отдыха.</li>
          <li>Организаторы беспроигрышной онлайн игры OBABLO.SITE никого насильно не заставляют проводить свой досуг на этом проекте.</li>
          <li>Администрация Сервиса оставляет за собой право вносить изменения в текст данных Правил. Пользователь самостоятельно отслеживает изменения Правил, размещенных по адресу https://obablo.site/rules.</li>
        </ol>
        <h3 class="rules-title">Учетная запись, пароль, безопасность</h3>
        <ol class="decimal-list">
          <li>Для открытия Учетной Записи пользователь должен авторизоваться через Вконтакте.</li>
          <li>Пользователь несет полную ответственность за хранение конфиденциальной информации, за потерю доступа к своей Учетной Записи.</li>
          <li>Кроме того, пользователь несет полную ответственность за любые совершенные им действия.</li>
          <li>Сервис не несет ответственности за поступки, совершенные Пользователем в отношении третьих лиц.</li>
          <li>Пользователь обязуется сообщить Сервису о любом несанкционированном использовании его Учетной записи.</li>
        </ol>
        <h3 class="rules-title">Конфиденциальность</h3>
        <ol class="decimal-list">
          <li>Сервис обязуется не редактировать и не раскрывать любую конфиденциальную информацию, предоставленную Пользователем Сервису.</li>
          <li>Сервис также обязуется хранить личный пароль пользователя в зашифрованном виде.</li>
          <li>Личные данные могут быть предоставлены третьим лицам лишь в связи с нарушением законодательства, а также оскорблением или клеветой в отношении брендов или торговых марок третьих лиц.</li>
        </ol>
        <h3 class="rules-title">Пользователь</h3>
        <ol class="decimal-list">
          <li>Сервис оставляет за собой право в любой момент без предварительного оповещения блокировать Пользователя в связи с нарушением правил использования Сервиса или законодательства, а также аннулировать весь баланс, если это финансовое нарушение.</li>
          <li>Неприемлемы попытки несанкционированного доступа, попытки нанесения вреда Сервису.</li>
          <li>При добавлении на сайт любой информации, запрещены оскорбления, вымогательства, клевета, блеф, сообщения, содержащие вредоносную информацию (в т.ч. вирусы, трояны, черви и т.п.), а также информация, способная нанести вред третьим лицам.</li>
        </ol>
        <h3 class="rules-title">Выплаты</h3>
        <ol class="decimal-list">
          <li>Выплаты производятся в полуавтоматическом режиме после одобрения Администраторами.</li>
          <li>Комиссия за выплату лежит на Пользователе на платёжные системы, представленные в списке.</li>
          <li>При выводе бонусных средств может быть отказано без всяких причин.</li>
          <li>Администрация сайта может потребовать скан или фото паспорта для верификации.</li>
          <li>При отказе предоставить дополнительную информацию или верификации кошелька аккаунт может быть заблокирован.</li>
          <li>При нарушении правил баланс аккаунта может быть заморожен или аннулирован.</li>
        </ol>
        <h3 class="rules-title">Запрещено</h3>
        <ol class="decimal-list">
          <li>Запрещается публиковать фальсифицированные данные</li>
          <li>Запрещается передача любых материалов, которые могут нарушить интеллектуальную собственность третьих лиц</li>
          <li>Запрещаются фальшивые публикации информации с целью получения несанкционированных доступов к информации или данным третьих лиц</li>
          <li>Запрещается использование различных тактик и схем, в том числе и багов с целью получить выгоду</li>
          <li>Запрещается проводить попытки взлома сайта и использовать возможные ошибки в скриптах</li>
          <li>Запрещается регистрировать более одной Учетной Записи</li>
          <li>Запрещается махинации с реферальной системой, промокодами</li>
          <li>Запрещается использовать нецензурную лексику, оскорблять других участников и обвинять сайт в мошеничестве</li>
          <li>Запрещается пополнять счет на сайте с целью вывода денежных средств на другую платежную систему</li>
          <li>Запрещается выплата на одинаковые реквизиты с разных аккаунтов</li>
          <li>Запрещается передавать данные для доступа к Учетной записи третьим лицам</li>
          <li>Запрещается оскорблять, обзывать, ставить под сомнение профессиональную квалификацию и добросовестность физических и юридических лиц, в том числе Пользователей Сервиса и Администрации Сервиса.</li>
          <li>Запрещается выбирать себе аватары, содержащие сцены насилия, угрозы, сквернословия, разврат (порнография), дискриминация в любых проявлениях, коммерческая реклама и рекламные тексты</li>
          <li>Запрещается несанкционированное воздействие на сервер и (или) код игры.</li>
          <li>Запрещается создание и запуск несанкционированного Администрацией бота или иной автоматизированной (полуавтоматизированной) программы с целью совершения любых действий.</li>
        </ol>
        <h3 class="rules-title">Промокоды</h3>
        <ol class="decimal-list">
          <li>Запрещено активировать промокоды, которые выдаются в группе, с разных аккаунтов. У нарушителей будет отключена возможность активировать любые промокоды. В особых случаях возможна блокировка аккаунта.</li>
          <li>Запрещено выкладывать реферальные промокоды или промокоды, выданные в оффициальной группе, в чат. Наказание: блокировка чата.</li>
        </ol>

        <h3 class="rules-title">Чат</h3>
        <ol class="decimal-list">
          <li>В чате <b>категорически</b> запрещено:</li>
          <li>Спамить одинаковыми сообщениями</li>
          <li>Выпрашивать промокоды у Администраторов и участников проекта</li>
          <li>Рекламировать сторонние ресурсы и упоминать о них</li>
          <li>Выкладывать ссылки без одобрения Администрации</li>
          <li>Писать предложения, фразы и отдельные слова в верхнем реестре (CapsLock)</li>
          <li>В качестве наказания будет применена блокировка чата</li>
        </ol>

        <h3 class="rules-title">Соглашение с правилами сайта (оферты)</h3>
        <ol class="decimal-list">
          <li>Зарегистрировавшись в Сервисе, Вы, тем самым, соглашаетесь с правилами нашего сайта.</li>
        </ol>
        <h3 class="rules-title">Права</h3>
        <ol class="decimal-list">
          <li>Исключительное право на Сервис принадлежит Сервису</li>
          <li>Все права на материалы, представленные на нашем сайте, принадлежат Правообладателям</li>
        </ol>
      </div>
    </div>
    <div class="popup popup-fair">
      <a class="close"></a>
      <div class="heading">ЧЕСТНАЯ ИГРА</div>
      <div id="accordion">
        <h3 class="fair-title">Что такое "Честная игра"?</h3>
        <ol class="decimal-list">
          <li><b>Честная игра</b> - это уникальная система проверки честности игры.</li>
        </ol>
        <h3 class="fair-title">Как проверить результаты игры на честность?</h3>
        <ol class="decimal-list">
          <li>Перед игрой скопруйте хэш, после окончания игры Вы можете проверить хэш на <a href="/fair">странице проверки</a>.</li>
          <li>Так же любую игру можно легко проверить на честность, для этого просто нажмите кнопку "Проверить" (кнопки отображаются в конце игры или найти их в истории игр).</li>
        </ol>
      </div>
    </div>
    <div class="popup popup-auth">
        <div class="modal htp-modal">
            <a class="myicon-close modal-close close"></a>
            <div class="auth-modal">
                <div class="auth-modal_content">
                    <div class="auth-modal_content-inner">
                        <div class="auth-modal_heading">
                            Авторизация на сайте
                        </div>
                        <div class="auth-modal_warning">
                            Продолжая авторизацию, вы подтверждаете, что ознакомились с <a href="/rules"
                                class="gray-link">условиями использования</a> и используете только один аккаунт для доступа к
                            сайту. Использование более чем одного аккаунта запрещено. Если вы имеете более одного аккаунта на
                            сайте, пожалуйста, обратитесь в <a href="https://vk.com/im?sel=-123" target="_blank"
                                class="gray-link">поддержку</a>, и мы с радостью поможем решить эту проблему.
                        </div>
                        <div class="auth-modal_authorize-form">
                            <a class="auth-modal_vk-btn" href="/auth/vkontakte">
                                <span href="/auth/vkontakte" class="auth-modal_vk-link">
                                    <span class="myicon-vk-icon auth-modal_vk-icon"></span>
                                    <span class="auth-modal_continue-with-vk">Войти через ВКонтакте</span>
                                </span>
                            </a>
                        </div>
        
                        <div class="auth-modal_footer">
                            <span>
                                Данный сайт защищен reCAPTCHA с соответствующей политикой конфиденциальности Google <a
                                    href="https://policies.google.com/privacy" target="_blank" class="gray-link"><span>Политика
                                        конфиденциальности</span></a>
                                и условиями предоставления услуг <a href="https://policies.google.com/terms" target="_blank"
                                    class="gray-link"><span>Условия предоставления услуг</span></a>. </span>
                        </div>
                    </div>
        
                </div>
                <div class="auth-modal_img">
                    <img src="/assets/images/greeting.png" alt="">
                </div>
            </div>
        
        </div>
    </div>
  </div>
  <?php if(session('error')): ?>
  <script>
  $.notify({
    position : 'top-right',
    type: 'error',
    message: "<?php echo e(session('error')); ?>"
  });
  </script>
  <?php elseif(session('success')): ?>
  <script>
  $.notify({
    position : 'top-right',
    type: 'success',
    message: "<?php echo e(session('success')); ?>"
  });
  </script>
  <?php endif; ?>
</body>
</html>
<?php endif; ?>
