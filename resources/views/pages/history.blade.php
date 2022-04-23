@extends('layout')

@section('content')
    
	<div class="game-container game-container_wallet">
        <div class="wallet-header">
            <a class="wallet-header__item " href="/wallet/deposit">Пополнение</a>
            <a class="wallet-header__item " href="/wallet/withdraw">Вывод</a>
            <a class="wallet-header__item active" href="/wallet/history">История</a>
        </div>
        <script type="text/javascript">
            $( document ).ready(function() { 
                $('.user-profile-caregories__category_pays').click(() => {
                    $('.user-profile-caregories__category_withdraws').removeClass('user-profile-caregories__category_active');
                    $('.user-profile-caregories__category_pays').addClass('user-profile-caregories__category_active');
                    $('.stats-table__pays').css('display', 'table');
                    $('.stats-table__withdraws').css('display', 'none');
                });

                $('.user-profile-caregories__category_withdraws').click(() => {
                    $('.user-profile-caregories__category_withdraws').addClass('user-profile-caregories__category_active');
                    $('.user-profile-caregories__category_pays').removeClass('user-profile-caregories__category_active');
                    $('.stats-table__pays').css('display', 'none');
                    $('.stats-table__withdraws').css('display', 'table');
                });
            });
        </script>
<!-- < src="/assets/js/walletHistory.js"></> -->
<div class="wallet-body">
    <div class="user-profile-full-stats gray-color user-profile-full-stats_dark">
        <div class="user-profile-caregories">
            <span class="user-profile-caregories__category user-profile-caregories__category_pays table-picker user-profile-caregories__category_active" data-load="pays">Платежи</span>
            <span class="user-profile-caregories__category user-profile-caregories__category_withdraws table-picker" data-load="withdraws">Выводы</span>
        </div>
        <div class="user-profile-full-stats__content">
            <div class="loader la-ball-clip-rotate la-2x" style="display: none;">
                <div></div>
            </div>
           <!--  <div class="user-profile-full-stats__error" style="display: none;">
                <span>Произошла ошибка загрузки. Пожалуйста, обновите страницу</span>
            </div> -->
            
            <table class="games-table stats-table__pays games-table__pays" style="display: table;">
                <thead>
                    <tr class="games-table__header-tr">
                        <th class="games-table__header-th">ID</th>
                        <th class="games-table__header-th">Сумма</th>
                        <th class="games-table__header-th">Способ</th>
                        <th class="games-table__header-th">Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <tbody>
                        @foreach($pays as $pay)
                        <tr class="games-table__body-tr">            
                            <td class="games-table__body-td">{{$pay->id}}</td>            
                            <td class="games-table__body-td">+{{$pay->price / 10}} ₽</td>            
                            <td class="games-table__body-td games-table__body-td_no_padding"> @if($pay->status == 1) Пополнение баланса @elseif($pay->status == 2) Реферальный код @else Промокод @endif </td>            
                            <td class="games-table__body-td status-success">
                                <span class="games-table__body-status">
                                    <span class="myicon-success games-table__body-status-image"></span>
                                    <span class="games-table__body-status-text">Выполнен</span>
                                </span>
                            </td>        
                        </tr>
                        @endforeach
                    </tbody>
                </tbody>
            </table>

            <table class="games-table stats-table__withdraws games-table__withdraws" style="display: none;">
                <thead>
                    <tr class="games-table__header-tr">
                        <th class="games-table__header-th">ID</th>
                        <th class="games-table__header-th">Сумма</th>
                        <th class="games-table__header-th">Система</th>
                        <th class="games-table__header-th">Статус</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdraws as $pay)
                    <tr class="games-table__body-tr">            
                        <td class="games-table__body-td">{{$pay->id}}</td>            
                        <td class="games-table__body-td">-{{$pay->sum / 10}} ₽</td>            
                        <td class="games-table__body-td games-table__body-td_no_padding"> {{$pay->system}} </td>            
                        <td class="games-table__body-td @if($pay->status == 0) status-pending @elseif($pay->status == 1) status-success @elseif($pay->status == 2) status-error @endif">
                            <span class="games-table__body-status">
                                <span class="myicon-success games-table__body-status-image"></span>
                                <span class="games-table__body-status-text">@if($pay->status == 0) Обработка @elseif($pay->status == 1) Выполнен @elseif($pay->status == 2) Отменен @endif</span>
                            </span>
                        </td>        
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="games-table stats-table__other games-table__other" style="display: none;">
                <thead>
                    <tr class="games-table__header-tr">
                        <th class="games-table__header-th">Дата</th>
                        <th class="games-table__header-th">Сумма</th>
                        <th class="games-table__header-th">Тип</th>
                        <th class="games-table__header-th">Примечание</th>
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
        </div>
        <div class="user-profile-full-stats__controls">
            <button class="user-profile-full-stats__btn-prev user-profile-full-stats__btn" disabled="">
                <span class="myicon-down-arrow user-profile-full-stats__btn__icon" style="transform:rotate(90deg);"></span>
                <span class="user-profile-full-stats__btn__text">Предыдущая страница</span>
            </button>
            <button class="user-profile-full-stats__btn-next user-profile-full-stats__btn" disabled="">
                <span class="user-profile-full-stats__btn__text">Следующая страница</span>
                <span class="myicon-down-arrow user-profile-full-stats__btn__icon" style="transform:rotate(-90deg);"></span>
            </button>
        </div>
    </div>
</div>

<div class="modal-window modal_withdraw-cancel-reason">
    <div class="modal-dialog modal-w500">
            <div class="modal htp-modal narrow-modal" style="width: 100%;"> 
                    <div class="heading">Причина отмены  <a class="myicon-close modal-close"></a></div>
                    <div class="htp-content">
                        <p class="htp-popup-message"></p>
                    </div>
            </div>  
    </div>
</div>  

<div class="modal-window modal_withdraw-bonus">
    <div class="modal-dialog">
            <div class="modal htp-modal narrow-modal" style="width: 100%;"> 
                    <div class="heading">Получить бонус  <a class="myicon-close modal-close"></a></div>
                    <div class="htp-content">
                        <p class="htp-popup-message">
                            Отправьте скриншот данного вывода и скриншот из платежной системы о поступлении средств в предложку <a href="https://vk.com/moneyx12" target="_blank" class="def_link">группы</a> и получите <span style="color: #fff;">3%</span> от вывода
                        </p>
                    </div>
            </div>  
    </div>
</div>  

    </div>
    	
@endsection	