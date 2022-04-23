<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default border-panel card-view">
			<div class="panel-heading">
				<div class="pull-left">
					<h6 class="panel-title txt-dark">
						Бонусы
					</h6>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
                    <div id="createBonus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createBonusLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h5 class="modal-title" id="myModalLabel">Новый бонус</h5>
                                </div>
                                <form action="/admin/bonusNew" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Сумма</label>
                                            <input type="text" class="form-control" name="sum" placeholder="Сумма">
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Фон в HEX (#000000)</label>
                                            <input type="text" class="form-control" name="color" placeholder="Фон">
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label mb-10">Будет выпадать?</label>
                                            <select class="form-control" name="status">
                                                <option value="0">Нет</option>
                                                <option value="1">Да</option>
                                            </select>
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
                    <?php $__currentLoopData = $bonuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bonus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div id="editBonus<?php echo e($bonus->id); ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createBonusLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h5 class="modal-title" id="myModalLabel">Редактировать бонус</h5>
                                </div>
                                <form action="/admin/bonusSave" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" value="<?php echo e($bonus->id); ?>" name="id">
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Сумма</label>
                                            <input type="text" class="form-control" name="sum" placeholder="Сумма" value="<?php echo e($bonus->sum); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name" class="control-label mb-10">Фон в HEX (#000000)</label>
                                            <input type="text" class="form-control" name="color" placeholder="Фон" value="<?php echo e($bonus->color); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="message-text" class="control-label mb-10">Будет выпадать?</label>
                                            <select class="form-control" name="status">
                                                <option value="0" <?php if($bonus->status == 0): ?> selected <?php endif; ?>>Нет</option>
                                                <option value="1" <?php if($bonus->status == 1): ?> selected <?php endif; ?>>Да</option>
                                            </select>
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
                        <a class="btn btn-success btn-rounded" data-toggle="modal" data-target="#createBonus">Создать бонус</a>
                    </div>
                    <table id="datable_1" class="table table-hover display  pb-30 dataTable" role="grid" aria-describedby="datable_1_info">
                        <thead>
                            <tr>
                                <th>Сумма</th>
                                <th>Выпадает</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $bonuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bonus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($bonus->sum); ?>pt</td>
                                <td><?php echo e($bonus->status == 0 ? 'Нет' : 'Да'); ?></td>
                                <td class="text-center"><a class="btn btn-primary btn-rounded btn-xs" data-toggle="modal" data-target="#editBonus<?php echo e($bonus->id); ?>">Редактировать</a> / <a href="/admin/bonusDelete/<?php echo e($bonus->id); ?>" class="btn btn-danger btn-rounded btn-xs">Удалить</a></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Сумма</th>
                                <th>Выпадает</th>
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