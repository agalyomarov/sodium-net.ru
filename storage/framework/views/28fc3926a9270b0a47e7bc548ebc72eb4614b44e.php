<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">
						Активные запросы
					</h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <table id="datable_1" class="table table-hover display  pb-30 dataTable" role="grid" aria-describedby="datable_1_info">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Сумма</th>
                                <th>Система</th>
                                <th>Кошелек</th>
                               <?php if($u->superadmin): ?> <th>Действия</th> <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdraw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($withdraw['id']); ?></td>
                                <td><a href="/admin/user/<?php echo e($withdraw['user_id']); ?>"><img src="<?php echo e($withdraw['avatar']); ?>" style="width:50px;border-radius:50%;margin-right:10px;vertical-align:middle;"> <?php echo e($withdraw['username']); ?></a></td>
                                <td><?php echo e($withdraw['value']); ?>р</td>
                                <td><?php echo e($withdraw['system']); ?></td>
                                <td><?php echo e($withdraw['wallet']); ?></td>
                              <?php if($u->superadmin): ?>  <td class="text-center"><a href="/admin/withdraw/<?php echo e($withdraw['id']); ?>" class="btn btn-primary btn-rounded btn-xs">Отправить</a> <a href="/admin/return/<?php echo e($withdraw['id']); ?>" class="btn btn-danger btn-rounded btn-xs">Вернуть</a></td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Сумма</th>
                                <th>Система</th>
                                <th>Кошелек</th>
                               <?php if($u->superadmin): ?> <th>Действия</th> <?php endif; ?>
                            </tr>
                        </tfoot>
                    </table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">
						Обработанные запросы
					</h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <table id="datable_2" class="table table-hover display  pb-30 dataTable" role="grid" aria-describedby="datable_2_info">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Сумма</th>
                                <th>Система</th>
                                <th>Кошелек</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $finished; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $finish): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($finish['id']); ?></td>
								<td><a href="/admin/user/<?php echo e($finish['user_id']); ?>"><img src="<?php echo e($finish['avatar']); ?>" style="width:50px;border-radius:50%;margin-right:10px;vertical-align:middle;"> <?php echo e($finish['username']); ?></a></td>
                                <td><?php echo e($finish['value']); ?>р</td>
                                <td><?php echo e($finish['system']); ?></td>
                                <td><?php echo e($finish['wallet']); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Сумма</th>
                                <th>Система</th>
                                <th>Кошелек</th>
                            </tr>
                        </tfoot>
                    </table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('panel', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>