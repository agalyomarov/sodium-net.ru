@extends('panel')

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default card-view pa-0">
			<div class="panel-wrapper collapse in">
				<div class="panel-body pb-0">
                    <div class="form-wrap">
                        <form class="form-horizontal" method="post" action="/admin/settingSave">
                            <div class="tab-struct custom-tab-1">
                                <ul class="nav nav-tabs nav-tabs-responsive" id="myTabs_8" role="tablist">
                                    <li class="active" role="presentation">
                                        <a aria-expanded="true" data-toggle="tab" href="#site" id="profile_tab_8" role="tab"><span>Сайт</span></a>
                                    </li>
																		<li class="" role="presentation">
                                        <a aria-expanded="true" data-toggle="tab" href="#sends" id="profile_tab_8" role="tab"><span>Переводы</span></a>
                                    </li>
                                    <li class="" role="presentation">
                                        <a aria-expanded="false" data-toggle="tab" href="#jackpot" id="photos_tab_8" role="tab"><span>Рулетка</span></a>
                                    </li>
                                    <li class="" role="presentation">
                                        <a aria-expanded="false" data-toggle="tab" href="#double" id="earning_tab_8" role="tab"><span>Дабл</span></a>
                                    </li>
                                    <li class="" role="presentation">
                                        <a aria-expanded="false" data-toggle="tab" href="#pvp" id="earning_tab_8" role="tab"><span>PvP</span></a>
                                    </li>
                                    <li class="" role="presentation">
                                        <a aria-expanded="false" data-toggle="tab" href="#battle" id="earning_tab_8" role="tab"><span>Батл</span></a>
                                    </li>
                                    <li class="" role="presentation">
                                        <a aria-expanded="false" data-toggle="tab" href="#crash" id="earning_tab_8" role="tab"><span>Краш</span></a>
                                    </li>
                                    <li class="" role="presentation">
                                        <a aria-expanded="false" data-toggle="tab" href="#fake" id="earning_tab_8" role="tab"><span>Фейки</span></a>
                                    </li>
                                </ul>
                                <div class="tab-content pa-15" id="myTabContent_8">
                                    <div class="tab-pane fade active in" id="site" role="tabpanel">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Домен</label>
                                                <input type="text" class="form-control" name="domain" value="{{$settings->domain}}" placeholder="Домен">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Логотип</label>
                                                <input type="text" class="form-control" name="sitename" value="{{$settings->sitename}}" placeholder="Домен">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Описание</label>
                                                <input type="text" class="form-control" name="desc" value="{{$settings->desc}}" placeholder="Описание">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Ключи</label>
                                                <input type="text" class="form-control" name="keys" value="{{$settings->keys}}" placeholder="Ключи">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Титул</label>
                                                <input type="text" class="form-control" name="title" value="{{$settings->title}}" placeholder="Титул">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Группа VK</label>
                                                <input type="text" class="form-control" name="vk_url" value="{{$settings->vk_url}}" placeholder="Группа VK">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Телеграмм</label>
                                                <input type="text" class="form-control" name="tg_url" value="{{$settings->tg_url}}" placeholder="Телеграмм">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">VK Key</label>
                                                <input type="text" class="form-control" name="vk_key" value="{{$settings->vk_key}}" placeholder="VK Key">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">VK Secret</label>
                                                <input type="text" class="form-control" name="vk_secret" value="{{$settings->vk_secret}}" placeholder="VK Secret">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">ID Магазина FK</label>
                                                <input type="text" class="form-control" name="mrh_ID" value="{{$settings->mrh_ID}}" placeholder="ID Магазина FK">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">FK Кошелек</label>
                                                <input type="text" class="form-control" name="fk_wallet" value="{{$settings->fk_wallet}}" placeholder="FK Кошелек">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">FK Secret 1</label>
                                                <input type="text" class="form-control" name="mrh_secret1" value="{{$settings->mrh_secret1}}" placeholder="FK Secret 1">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">FK Secret 2</label>
                                                <input type="text" class="form-control" name="mrh_secret2" value="{{$settings->mrh_secret2}}" placeholder="FK Secret 2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">FK API Key</label>
                                                <input type="text" class="form-control" name="fk_api" value="{{$settings->fk_api}}" placeholder="FK API Key">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label mb-10">Боты</label>
												<select class="form-control" name="fake">
													<option value="1" @if($settings->fake == 1) selected @endif>Включены</option>
													<option value="0" @if($settings->fake == 0) selected @endif>Выключены</option>
												</select>
											</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Сумма пополнения для использования чата. 0 - Отключено</label>
                                                <input type="text" class="form-control" name="chat_dep" value="{{$settings->chat_dep}}" placeholder="Сумма пополнения">
                                            </div>
																						<div class="col-sm-6">
																								<label class="control-label mb-10">Монет за приглашенного пользователя</label>
																								<input type="text" class="form-control" name="ref_invite" value="{{$settings->ref_invite}}" placeholder="50">
																						</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Какой процент начислять при пополнении от 100р?. 0 - Отключено</label>
                                                <input type="text" class="form-control" name="dep_perc" value="{{$settings->dep_perc}}" placeholder="Процент">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Код пополнения</label>
                                                <input type="text" class="form-control" name="dep_code" value="{{$settings->dep_code}}" placeholder="Код пополнения">
                                            </div>
                                        </div>
                                    </div>
																		<div class="tab-pane fade in" id="sends" role="tabpanel">
																			<div class="row">
																				<div class="col-sm-6">
																						<label class="control-label mb-10">Минимальная сумма отправки монет</label>
																						<input type="text" class="form-control" name="min_sendwith" value="{{$settings->min_sendwith}}" placeholder="10">
																				</div>
																				<div class="col-sm-6">
																						<label class="control-label mb-10">Максимальная сумма отправки монет</label>
																						<input type="text" class="form-control" name="max_sendwith" value="{{$settings->max_sendwith}}" placeholder="10">
																				</div>
																			</div>
																			<div class="row">
																				<div class="col-sm-6">
																						<label class="control-label mb-10">Вывод для отправки монет в чате</label>
																						<input type="text" class="form-control" name="sendwith" value="{{$settings->sendwith}}" placeholder="200">
																				</div>
																				<div class="col-sm-6">
																						<label class="control-label mb-10">Вывод для создания промокода</label>
																						<input type="text" class="form-control" name="withpromo" value="{{$settings->withpromo}}" placeholder="200">
																				</div>
																			</div>
																		</div>
                                    <div class="tab-pane fade in" id="jackpot" role="tabpanel">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="control-label mb-10">Комиссия игры в %</label>
                                                <input type="text" class="form-control" name="jackpot_commission" value="{{$settings->jackpot_commission}}" placeholder="Комиссия игры">
                                            </div>
										</div>
                                        @foreach($rooms as $r)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel-heading">
                                                    <div class="text-center">
                                                        <h6 class="panel-title txt-dark">Комната "{{$r->title}}"</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label class="control-label mb-10">Таймер</label>
                                                <input type="text" class="form-control" name="time_{{$r->name}}" value="{{$r->time}}" placeholder="Таймер">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="control-label mb-10">Минимальная ставка</label>
                                                <input type="text" class="form-control" name="min_{{$r->name}}" value="{{$r->min}}" placeholder="Минимальная ставка">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="control-label mb-10">Максимальная ставка</label>
                                                <input type="text" class="form-control" name="max_{{$r->name}}" value="{{$r->max}}" placeholder="Максимальная ставка">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="control-label mb-10">Максимальное кол-во ставок для игрока</label>
                                                <input type="text" class="form-control" name="bets_{{$r->name}}" value="{{$r->bets}}" placeholder="Максимальная ставка">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="tab-pane fade in" id="double" role="tabpanel">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="control-label mb-10">Комиссия игры в %</label>
                                                <input type="text" class="form-control" name="double_commission" value="{{$settings->double_commission}}" placeholder="Комиссия игры">
                                            </div>
										</div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label mb-10">Таймер</label>
                                                <input type="text" class="form-control" name="roulette_timer" value="{{$settings->roulette_timer}}" placeholder="Таймер">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="control-label mb-10">Минимальная ставка</label>
                                                <input type="text" class="form-control" name="double_min_bet" value="{{$settings->double_min_bet}}" placeholder="Минимальная ставка">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="control-label mb-10">Максимальная ставка</label>
                                                <input type="text" class="form-control" name="double_max_bet" value="{{$settings->double_max_bet}}" placeholder="Максимальная ставка">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade in" id="pvp" role="tabpanel">
																			<div class="row">
																				<div class="col-sm-12">
																					<label class="control-label mb-10">Комиссия игры в %</label>
																					<input type="text" class="form-control" name="pvp_commission" value="{{$settings->pvp_commission}}" placeholder="Комиссия игры">
																				</div>
																			</div>
																			<div class="row">
																				<div class="col-sm-6">
																					<label class="control-label mb-10">Минимальная ставка</label>
																					<input type="text" class="form-control" name="pvp_min" value="{{$settings->pvp_min}}" placeholder="10">
																				</div>
																				<div class="col-sm-6">
																					<label class="control-label mb-10">Максимальная ставка</label>
																					<input type="text" class="form-control" name="pvp_max" value="{{$settings->pvp_max}}" placeholder="500">
																				</div>
																			</div>
                                    </div>
                                    <div class="tab-pane fade in" id="battle" role="tabpanel">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Таймер</label>
                                                <input type="text" class="form-control" name="battle_timer" value="{{$settings->battle_timer}}" placeholder="Таймер">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Минимальная ставка</label>
                                                <input type="text" class="form-control" name="battle_min_bet" value="{{$settings->battle_min_bet}}" placeholder="Минимальная ставка">
                                            </div>
																					</div>
																					<div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Максимальная ставка</label>
                                                <input type="text" class="form-control" name="battle_max_bet" value="{{$settings->battle_max_bet}}" placeholder="Максимальная ставка">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Комиссия игры в %</label>
                                                <input type="text" class="form-control" name="battle_commission" value="{{$settings->battle_commission}}" placeholder="Комиссия игры">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade in" id="crash" role="tabpanel">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="control-label mb-10">Таймер</label>
                                                <input type="text" class="form-control" name="crash_timer" value="{{$settings->crash_timer}}" placeholder="Таймер">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="control-label mb-10">Минимальная ставка</label>
                                                <input type="text" class="form-control" name="crash_min_bet" value="{{$settings->crash_min_bet}}" placeholder="Минимальная ставка">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="control-label mb-10">Максимальная ставка</label>
                                                <input type="text" class="form-control" name="crash_max_bet" value="{{$settings->crash_max_bet}}" placeholder="Максимальная ставка">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade in" id="fake" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel-heading">
                                                    <div class="text-center">
                                                        <h6 class="panel-title txt-dark">Double</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Минимальная ставка (1 монета)</label>
                                                <input type="text" class="form-control" name="double_fake_min" value="{{$settings->double_fake_min}}" placeholder="Минимальная ставка">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Максимальная ставка (Не больше установленой в игре)</label>
                                                <input type="text" class="form-control" name="double_fake_max" value="{{$settings->double_fake_max}}" placeholder="Максимальная ставка">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel-heading">
                                                    <div class="text-center">
                                                        <h6 class="panel-title txt-dark">Dice</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Минимальная ставка (10 монет)</label>
                                                <input type="text" class="form-control" name="dice_fake_min" value="{{$settings->dice_fake_min}}" placeholder="Минимальная ставка">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label mb-10">Максимальная ставка (Не больше установленой в игре)</label>
                                                <input type="text" class="form-control" name="dice_fake_max" value="{{$settings->dice_fake_max}}" placeholder="Максимальная ставка">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                   @if($u->superadmin) <button class="btn btn-success" type="submit">Сохранить</button> @endif
                                </div>
                            </div>
                        </form>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
