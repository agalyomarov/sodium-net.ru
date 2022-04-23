<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-sm-4">
		<div class="panel panel-default card-view">
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="profile-box">
						<div class="profile-info text-center mb-15">
							<div class="profile-img-wrap">
								<img alt="user" class="inline-block mb-10" src="<?php echo e($user->avatar); ?>">
							</div>
							<h5 class="block mt-10 weight-500 capitalize-font txt-dark">
								<?php echo e($user->username); ?>

							</h5>
							<h6 class="block capitalize-font">
								<?php if($user->is_admin): ?> Администратор <?php elseif($user->is_moder): ?> Модератор <?php elseif($user->is_youtuber): ?> YouTube`r <?php else: ?> Пользователь <?php endif; ?>
							</h6>
						</div>
						<div class="social-info">
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($dep); ?>р</span> <span class="counts-text block">сумма пополнений</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($with); ?>р</span> <span class="counts-text block">сумма выводов</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Бонусы
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($bonus); ?>р</span> <span class="counts-text block">сумма полученных бонусов</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($ref); ?>р</span> <span class="counts-text block">сумма за приглашенных рефералов</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки Jackpot
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($j_wb); ?>р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($j_lb); ?>р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки Double
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($do_wb); ?>р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($do_lb); ?>р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки Dice
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($di_wb); ?>р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($di_lb); ?>р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки PvP
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($p_wb); ?>р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($p_lb); ?>р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки Battle
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($b_wb); ?>р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($b_lb); ?>р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Ставки Crash
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($cr_wb); ?>р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($cr_lb); ?>р</span> <span class="counts-text block">Проиграл</span>
								</div>
							</div>
						</div>
						<div class="social-info">
							<h6 class="block capitalize-font text-center">
								Общий подсчет
							</h6>
							<div class="row">
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($j_wb + $do_wb + $di_wb + $p_wb + $b_wb + $cr_wb); ?>р</span> <span class="counts-text block">Выиграл</span>
								</div>
								<div class="col-xs-6 text-center">
									<span class="counts block head-font"><?php echo e($j_lb + $do_lb + $di_lb + $p_lb + $b_lb + $cr_lb); ?>р</span> <span class="counts-text block">Проиграл</span>
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
                            <input name="id" value="<?php echo e($user->id); ?>" type="hidden">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Фамилия Имя</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="username" value="<?php echo e($user->username); ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">IP адрес</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="username" value="<?php echo e($user->ip); ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Баланс</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="balance" value="<?php echo e($user->balance); ?>" id="balance">
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" value="<?php echo e($user->balance/10); ?> руб." id="rub" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Старница VK</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="https://vk.com/id<?php echo e($user->user_id); ?>" readonly>
                                </div>
                            </div>
														<div class="form-group">
                                <label class="col-sm-3 control-label">Реферальный код</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="affiliate_id" value="<?php echo e($user->affiliate_id); ?>" id="affiliate_id">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Привилегии</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="priv">
                                        <option value="admin" <?php if($user->is_admin): ?> selected <?php endif; ?>>Администратор</option>
                                        <option value="moder" <?php if($user->is_moder): ?> selected <?php endif; ?>>Модератор</option>
                                        <option value="youtuber" <?php if($user->is_youtuber): ?> selected <?php endif; ?>>YouTube`r</option>
                                        <option value="user" <?php if(!$user->is_admin && !$user->is_moder && !$user->is_youtuber): ?> selected <?php endif; ?>>Пользователь</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Забанен</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="ban">
                                        <option value="0" <?php if($user->ban == 0): ?> selected <?php endif; ?>>Нет</option>
                                        <option value="1" <?php if($user->ban == 1): ?> selected <?php endif; ?>>Да</option>
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
        <?php if(!$user->fake): ?>
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
									  	  <?php $__currentLoopData = $acpromo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										  <tr>
											<td><?php echo e($p->code); ?></td>
											<?php if(!empty(\App\Promocode::getUserId($p->code)->user_id)): ?>
											<td><img src="<?php echo e(\App\User::getUser(\App\Promocode::getUserId($p->code)->user_id)->avatar); ?>" style="width:50px;border-radius:50%;margin-right:10px;vertical-align:middle;"> <a href="/admin/user/<?php echo e(\App\Promocode::getUserId($p->code)->user_id); ?>"><?php echo e(\App\User::getUser(\App\Promocode::getUserId($p->code)->user_id)->username); ?></a></td>
											<?php else: ?>
											<td><img src="/assets/images/no_avatar.jpg" style="width:50px;border-radius:50%;margin-right:10px;vertical-align:middle;"> Администратор</td>
											<?php endif; ?>
											<td><?php echo e($p->price/10); ?>р</td>
											<td><?php echo e(\Carbon\Carbon::parse($p->created_at)->diffForHumans()); ?></td>
										  </tr>
										  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
									  	  <?php $__currentLoopData = $sends_from; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										  <tr>
											<td><a href="/admin/user/<?php echo e($s['id']); ?>"><?php echo e($s['username']); ?></a></td>
											<td><?php echo e($s['sum']); ?></td>
											<td><?php echo e($s['date']); ?></td>
										  </tr>
										  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
									  	  <?php $__currentLoopData = $sends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										  <tr>
											<td><a href="/admin/user/<?php echo e($s['id']); ?>"><?php echo e($s['username']); ?></a></td>
											<td><?php echo e($s['sum']); ?></td>
											<td><?php echo e($s['date']); ?></td>
										  </tr>
										  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
									  	  <?php $__currentLoopData = $refs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										  <tr>
											<td><a href="/admin/user/<?php echo e($r->id); ?>"><?php echo e($r->username); ?></a></td>
											<td><?php echo e(\Carbon\Carbon::parse($r->created_at)->diffForHumans()); ?></td>
										  </tr>
										  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
   		<?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('panel', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>