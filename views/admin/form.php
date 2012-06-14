<section class="title">
	<!-- We'll use $this->method to switch between sample.create & sample.edit -->
	<h4><?php echo lang('tasks_'.$this->method) . ' ' . lang('tasks_singular') ?></h4>
</section>

<section class="item">

	<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

	<div class="tabs">

	<ul class="tab-menu">
		<li><a href="#task-detail-tab"><span><?php echo lang('tasks_detail_label'); ?></span></a></li>
		<li><a href="#task-action-tab"><span><?php echo lang('tasks_action_label'); ?></span></a></li>
	</ul>
		
		<div class="form_inputs" id="task-detail-tab">

			<fieldset>	
		
			<ul>
				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="slug"><?php echo lang('tasks_desc'); ?> <span>*</span></label>
					<div class="input"><?php echo form_input('desc', set_value('desc', $task->desc), 'class="text width-25"'); ?></div>
				</li>
				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="name"><?php echo lang('tasks_site'); ?> <span>*</span></label>
					<div class="input"><?php echo form_dropdown('id_site', $sites, set_value('id_site', $task->id_site)); ?></div>
				</li>
				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="name"><?php echo lang('tasks_vendor'); ?> <span>*</span></label>
					<div class="input"><?php echo form_dropdown('id_profile', $vendors, set_value('id_profile', $task->id_profile)); ?></div>
				</li>

				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="slug"><?php echo lang('tasks_volume_label'); ?> </label>
					<div class="input"><?php echo form_input('volume', set_value('volume', $task->volume)); ?></div>
				</li>
				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="name"><?php echo lang('tasks_voltype_label'); ?> </label>
					<div class="input"><?php echo form_dropdown('volume_type', $vol_types, set_value('volume_type', $task->volume_type)); ?></div>
				</li>
				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="slug"><?php echo lang('tasks_cost_label'); ?> </label>
					<div class="input"><?php echo form_input('cost', set_value('cost', $task->cost)); ?></div>
				</li>
			</ul>

			</fieldset>
		
		</div>

		<div class="form_inputs" id="task-action-tab">

			<fieldset>	
		
			<ul>
			<li class="date-meta">
				<label><?php echo lang('tasks_fu_label'); ?><span>*</span></label>
				
				<div class="input datetime_input">
				<?php echo form_input('action_at', date('Y-m-d', $task->action_at), 'maxlength="10" id="datepicker" class="text width-20"'); ?> &nbsp;
				</div>
			</li>

			<li class="<?php echo alternator('', 'even'); ?>">
				<label for="slug"><?php echo lang('tasks_progress_label'); ?> <span>*</span></label>
				<div class="input"><?php echo form_dropdown('progress', $progress_options,set_value('progress', $task->progress)); ?></div>
			</li>
			</ul>

			</fieldset>
		
		</div>
	</div>
		
		<div class="buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
		</div>
		
	<?php echo form_close(); ?>

</section>