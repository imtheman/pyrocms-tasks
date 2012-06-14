<section class="title">
	<h4><?php echo lang('tasks_list'); ?></h4>
</section>

<section class="item">
	<?php echo form_open('admin/tasks/delete');?>
	
	<?php if (!empty($tasks)): ?>
	
		<table>
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th class="collapse"><?php echo lang('tasks_desc'); ?></th>
					<th class="collapse"><?php echo lang('tasks_site'); ?></th>
					<th class="collapse"><?php echo lang('tasks_vendor'); ?></th>
					<th class="collapse"><?php echo lang('tasks_voltype_label'); ?></th>
					<th class="collapse"><?php echo lang('tasks_progress_label'); ?></th>
					<th class="collapse"><?php echo lang('tasks_action_label'); ?></th>
					<th width="180"></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5">
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach( $tasks as $task ): ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $task->id); ?></td>
					<td><?php echo $task->desc; ?></td>
					<td><?php echo $task->domain; ?></td>
					<td><a href="<?php echo site_url('admin/users/edit/'.$task->user_id); ?>">
						<?php echo $task->display_name; ?></a></td>
					<td><?php echo $task->volume_type_label; ?></td>
					<td><?php echo $task->progress_label; ?></td>
					<td><?php echo date('Y-m-d', $task->action_at); ?></td>
					<td class="actions">
						<?php echo
						anchor('admin/tasks/edit/'.$task->id, lang('tasks_edit'), 'class="button"').' '.
						anchor('admin/tasks/delete/'.$task->id, 	lang('tasks_delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
		
	<?php else: ?>
		<div class="no_data"><?php echo lang('sample:no_items'); ?></div>
	<?php endif;?>
	
	<?php echo form_close(); ?>
</section>