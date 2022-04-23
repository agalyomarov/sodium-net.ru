@extends('panel')

@section('content')
<div class="row">
	<div class="col-sm-4">
		<div class="panel panel-default card-view">
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="profile-box">
						<div class="profile-info text-center mb-15">
							<div class="profile-img-wrap">
								<img alt="user" class="inline-block mb-10" src="{{$user->avatar}}">
							</div>
							<h5 class="block mt-10 weight-500 capitalize-font txt-dark">
								{{$user->username}}
							</h5>
							<h6 class="block capitalize-font">
								@if($user->is_admin) Администратор @elseif($user->is_moder) Модератор @elseif($user->is_youtuber) YouTube`r @else Пользователь @endif
							</h6>
						</div>
						<div class="social-info">
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$dep}}р</span> <span class="counts-text block">сумма пополнений</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$with}}р</span> <span class="counts-text block">сумма выводов</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Бонусы
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$bonus}}р</span> <span class="counts-text block">сумма полученных бонусов</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$ref}}р</span> <span class="counts-text block">сумма за приглашенных рефералов</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки Jackpot
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$j_wb}}р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$j_lb}}р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки Double
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$do_wb}}р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$do_lb}}р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки Dice
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$di_wb}}р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$di_lb}}р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки PvP
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$p_wb}}р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$p_lb}}р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки Battle
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$b_wb}}р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$b_lb}}р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки Crash
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$cr_wb}}р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$cr_lb}}р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Общий подсчет
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$j_wb + $do_wb + $di_wb + $p_wb + $b_wb + $cr_wb}}р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font">{{$j_lb + $do_lb + $di_lb + $p_lb + $b_lb + $cr_lb}}р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
        <div class="panel panel-default card-view">
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="form-wrap">
                        <form class="form-horizontal" method="post" action="/admin/userSave">
                            <input name="id" value="{{$user->id}}" type="hidden">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Фамилия Имя</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="username" value="{{$user->username}}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">IP адрес</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="username" value="{{$user->ip}}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Баланс</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="balance" value="{{$user->balance}}" id="balance">
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" value="{{$user->balance/10}} руб." id="rub" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Старница VK</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="https://vk.com/id{{$user->user_id}}" readonly>
                                </div>
                            </div>
														<div class="form-group">
                                <label class="col-sm-3 control-label">Реферальный код</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="affiliate_id" value="{{$user->affiliate_id}}" id="affiliate_id">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Привилегии</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="priv">
                                        <option value="admin" @if($user->is_admin) selected @endif>Администратор</option>
                                        <option value="moder" @if($user->is_moder) selected @endif>Модератор</option>
                                        <option value="youtuber" @if($user->is_youtuber) selected @endif>YouTube`r</option>
                                        <option value="user" @if(!$user->is_admin && !$user->is_moder && !$user->is_youtuber) selected @endif>Пользователь</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Забанен</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="ban">
                                        <option value="0" @if($user->ban == 0) selected @endif>Нет</option>
                                        <option value="1" @if($user->ban == 1) selected @endif>Да</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button class="btn btn-success" type="submit">Сохранить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if(!$user->fake)
        <div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default border-panel card-view">
					<div class="panel-heading">
						<div class="pull-left">
							<h6 class="panel-title txt-dark">Активированные промокоды</h6>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="panel-wrapper collapse in">
						<div class="panel-body">
							<div class="table-wrap">
								<div class="table-responsive">
									<table class="table mb-0">
										<thead>
										  <tr>
											<th>Код</th>
											<th>Чей код</th>
											<th>Сумма</th>
											<th>Дата</th>
										  </tr>
										</thead>
										<tbody>
									  	  @foreach($acpromo as $p)
										  <tr>
											<td>{{ $p->code }}</td>
											@if(!empty(\App\Promocode::getUserId($p->code)->user_id))
											<td><img src="{{\App\User::getUser(\App\Promocode::getUserId($p->code)->user_id)->avatar}}" style="width:50px;border-radius:50%;margin-right:10px;vertical-align:middle;"> <a href="/admin/user/{{\App\Promocode::getUserId($p->code)->user_id}}">{{\App\User::getUser(\App\Promocode::getUserId($p->code)->user_id)->username}}</a></td>
											@else
											<td><img src="/assets/images/no_avatar.jpg" style="width:50px;border-radius:50%;margin-right:10px;vertical-align:middle;"> Администратор</td>
											@endif
											<td>{{ $p->price/10 }}р</td>
											<td>{{ \Carbon\Carbon::parse($p->created_at)->diffForHumans() }}</td>
										  </tr>
										  @endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default border-panel card-view">
					<div class="panel-heading">
						<div class="pull-left">
							<h6 class="panel-title txt-dark">Переводы от других пользователей</h6>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="panel-wrapper collapse in">
						<div class="panel-body">
							<div class="table-wrap">
								<div class="table-responsive">
									<table class="table mb-0">
										<thead>
										  <tr>
											<th>От кого</th>
											<th>Сумма</th>
											<th>Дата</th>
										  </tr>
										</thead>
										<tbody>
									  	  @foreach($sends_from as $s)
										  <tr>
											<td><a href="/admin/user/{{$s['id']}}">{{$s['username']}}</a></td>
											<td>{{$s['sum']}}</td>
											<td>{{$s['date']}}</td>
										  </tr>
										  @endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default border-panel card-view">
					<div class="panel-heading">
						<div class="pull-left">
							<h6 class="panel-title txt-dark">Переводы</h6>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="panel-wrapper collapse in">
						<div class="panel-body">
							<div class="table-wrap">
								<div class="table-responsive">
									<table class="table mb-0">
										<thead>
										  <tr>
											<th>Кому</th>
											<th>Сумма</th>
											<th>Дата</th>
										  </tr>
										</thead>
										<tbody>
									  	  @foreach($sends as $s)
										  <tr>
											<td><a href="/admin/user/{{$s['id']}}">{{$s['username']}}</a></td>
											<td>{{$s['sum']}}</td>
											<td>{{$s['date']}}</td>
										  </tr>
										  @endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default border-panel card-view">
					<div class="panel-heading">
						<div class="pull-left">
							<h6 class="panel-title txt-dark">Рефералы</h6>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="panel-wrapper collapse in">
						<div class="panel-body">
							<div class="table-wrap">
								<div class="table-responsive">
									<table class="table mb-0">
										<thead>
										  <tr>
											<th>Реферал</th>
											<th>Дата</th>
										  </tr>
										</thead>
										<tbody>
									  	  @foreach($refs as $r)
										  <tr>
											<td><a href="/admin/user/{{$r->id}}">{{$r->username}}</a></td>
											<td>{{ \Carbon\Carbon::parse($r->created_at)->diffForHumans() }}</td>
										  </tr>
										  @endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
   		@endif
    </div>
</div>
@endsection
