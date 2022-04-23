<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">
						Промокоды
					</h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <div id="createPromo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createPromoLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h5 class="modal-title" id="myModalLabel">Новый промокод</h5>
                                </div>
                                <form action="/admin/promoNew" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Код (только английские символы):</label>
                                            <input type="text" class="form-control" name="code" placeholder="Код">
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label mb-10">Лимит:</label>
                                            <select class="form-control" name="limit">
                                                <option value="0">Без лимита</option>
                                                <option value="1">По кол-ву</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Сумма (в монетах):</label>
                                            <input type="text" class="form-control" name="amount" placeholder="Сумма">
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Кол-во:</label>
                                            <input type="text" class="form-control" name="count_use" placeholder="Кол-во">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                        <button type="submit" class="btn btn-success">Создать</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php $__currentLoopData = $codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div id="editPromo<?php echo e($code->id); ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createPromoLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h5 class="modal-title" id="myModalLabel">Редактировать промокод</h5>
                                </div>
                                <form action="/admin/promoSave" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" value="<?php echo e($code->id); ?>" name="id">
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Код (только английские символы):</label>
                                            <input type="text" class="form-control" name="code" placeholder="Код" value="<?php echo e($code->code); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label mb-10">Лимит:</label>
                                            <select class="form-control" name="limit">
                                                <option value="0" <?php if($code->limit == 0): ?> selected <?php endif; ?>>Без лимита</option>
                                                <option value="1" <?php if($code->limit == 1): ?> selected <?php endif; ?>>По кол-ву</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Сумма (в монетах):</label>
                                            <input type="text" class="form-control" name="amount" placeholder="Сумма" value="<?php echo e($code->amount); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Кол-во:</label>
                                            <input type="text" class="form-control" name="count_use" placeholder="Кол-во" value="<?php echo e($code->count_use); ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                        <button type="submit" class="btn btn-success">Сохранить</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-center">
                        <a class="btn btn-success btn-rounded" data-toggle="modal" data-target="#createPromo">Создать промокод</a>
                    </div>
                    <table id="datable_1" class="table table-hover display  pb-30 dataTable" role="grid" aria-describedby="datable_1_info">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Код</th>
                                <th>Лимит</th>
                                <th>Сумма</th>
                                <th>Кол-во</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $codes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($code->id); ?></td>
                                <?php if($code->user_id): ?>
                                <td><img src="<?php echo e(\App\User::getUser($code->user_id)->avatar); ?>" style="width:50px;border-radius:50%;margin-right:10px;vertical-align:middle;"> <a href="/admin/user/<?php echo e($code->user_id); ?>"><?php echo e(\App\User::getUser($code->user_id)->username); ?></a></td>
                                <?php else: ?>
                                <td><img src="/assets/images/no_avatar.jpg" style="width:50px;border-radius:50%;margin-right:10px;vertical-align:middle;"> Администратор</td>
                                <?php endif; ?>
                                <td><?php echo e($code->code); ?></td>
                                <td><?php if($code->limit): ?> По кол-ву <?php else: ?> Без лимита <?php endif; ?></td>
                                <td><?php echo e($code->amount); ?>pt</td>
                                <td><?php echo e($code->count_use); ?></td>
                                <td class="text-center"><a class="btn btn-primary btn-rounded btn-xs" data-toggle="modal" data-target="#editPromo<?php echo e($code->id); ?>">Редактировать</a> / <a href="/admin/promoDelete/<?php echo e($code->id); ?>" class="btn btn-danger btn-rounded btn-xs">Удалить</a></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Код</th>
                                <th>Лимит</th>
                                <th>Сумма</th>
                                <th>Кол-во</th>
                                <th>Действия</th>
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