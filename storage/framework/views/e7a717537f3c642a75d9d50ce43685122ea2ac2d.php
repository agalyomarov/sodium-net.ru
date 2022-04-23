

<?php $__env->startSection('content'); ?>

<div class="game-container">
    <div class="game-cards">
        
        <a class="game-card game-card_mines game-card_small" href="/">
            <div class="game-card_bg game-card_mines-bg"></div>
            <div class="game-card__contents">
                <div class="game-card__title">
                    <div class="game-card__title-arrowed">Mines Скоро <span class="myicon-right-arrow"></span></div>
                </div>
                <div class="game-card__description">Мы формируем поле, состоящее из мин и бриллиантов. Чем больше мин на поле, тем выше коэффициенты. Ищите бриллианты, но опасайтесь мин!</div>
            </div>
            
        </a>
 
        <a class="game-card game-card_double game-card_big" href="/double">
            <div class="game-card_bg game-card_double-bg"></div>
            <div class="game-card__contents">
                <div class="game-card__title">
                    <div class="game-card__title-arrowed">Double <span class="myicon-right-arrow"></span></div>
                </div>
                <div class="game-card__description">Игроки вносят депозиты на желтый, черный или зеленый цвета рулетки. Желтый или черный удвоят вашу ставку, зеленый - умножит ее на 14!</div>
            </div>
        </a>

        <div class="flex-break"></div>

        <a class="game-card game-card_crash game-card_big" href="/crash">
            <div class="game-card_bg game-card_crash-bg"></div>
            <div class="game-card__contents">
                <div class="game-card__title">
                    <div class="game-card__title-arrowed">Crash <span class="myicon-right-arrow"></span></div>
                </div>
                <div class="game-card__description">Угадайте, до какой точки будет расти график. Чем выше точка - тем больше выигрыш!</div>
            </div>
        </a>

        <a class="game-card game-card_dice game-card_small" href="/dice">
            <div class="game-card_bg game-card_dice-bg"></div>
            <div class="game-card__contents">
                <div class="game-card__title">
                    <div class="game-card__title-arrowed">Dice <span class="myicon-right-arrow"></span></div>
                </div>
                <div class="game-card__description">Мы загадываем число от 1 до 100, а вам необходимо угадать, в какой диапазон оно попадет. Чем меньше диапазон - тем больше коэффициент!</div>
            </div>
        </a>

        <div class="flex-break"></div>

        <a class="game-card game-card_jackpot game-card_medium" href="/jackpot">
            <div class="game-card_bg game-card_jackpot-bg"></div>
            <div class="game-card__contents">
                <div class="game-card__title">
                    <div class="game-card__title-arrowed">Jackpot <span class="myicon-right-arrow"></span></div>
                </div>
                <div class="game-card__description">Перед началом раунда игроки вносят ставки в общий банк. Чем больше ваш вклад в банк - тем больше шансов выиграть джекпот!</div>
            </div>
        </a>

        <a class="game-card game-card_raffle game-card_medium" href="/battle">
            <div class="game-card_bg game-card_raffle-bg"></div>
            <div class="game-card__contents">
                <div class="game-card__title">
                    <div class="game-card__title-arrowed">Battle <span class="myicon-right-arrow"></span></div>
                </div>
                <div class="game-card__description">Battle</div>
            </div>
        </a>

    </div>

    <div class="footer">
        <div class="footer-information">
            <span class="footer-item copyright">© 2020 luckyroul. Все права защищены.</span>
            <div class="footer-terms">
                <!-- <span class="footer-item footer-term"><a href="/rules" class="footer-link">Условия использования</a></span> -->
                <!-- <span class="footer-item footer-term"><a href="/privacy-policy" class="footer-link">Политика конфиденциальности</a></span> -->
                <span class="footer-item footer-term"><a href="/fair" class="footer-link">Честная игра</a></span>
                <span class="footer-item footer-term"><a href="/faq" class="footer-link">FAQ</a></span>
            </div>
            <div class="footer-socials">
                <!-- <span class="footer-social footer-item">
                    <a href="https://vk.com/moneyx12" class="footer-link" target="_blank"><span class="myicon-vk footer-link__icon"></span> <span>vk.com/moneyx12</span></a>
                </span>
                <span class="footer-social footer-item">
                    <a href="https://t.me/moneyx12" class="footer-link" target="_blank"><span class="myicon-telegram footer-link__icon"></span> <span>t.me/moneyx12</span></a>
                </span>     -->
                <span class="footer-social footer-item">
                    <a href="mailto:support@money-x.io" class="footer-link" target="_blank"><span class="myicon-envelope footer-link__icon"></span> <span>support@luckyroul.ru</span></a>
                </span>
            </div>
        </div>
        <div class="footer-security">
            <div class="using-ssl">
                <span class="fa fa-lock"></span> 256-битное шифрование
            </div>
            <div class="rng-test">
                Исходный код генератора случайных чисел открыт и доступен на странице 
                <a href="/fair" class="gray-link">честная игра</a>.            
                ГСЧ протестирован NIST Test suite + Diehard Test suite. 
                <!-- <span class="rng-links">
                    <a target="_blank" href="/assets/media/output_dh_random_CS_1.txt" style="white-space: nowrap" class="gray-link">Отчет #1</a> | 
                    <a target="_blank" href="/assets/media/output_dh_random_CS_2.txt" style="white-space: nowrap" class="gray-link">Отчет #2</a>
                </span> -->
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>