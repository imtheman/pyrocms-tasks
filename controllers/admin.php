<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a sample module for PyroCMS
 *
 * @author 		Hugh Fletcher
 * @website		http://hughfletcher.com
 * @package 	PyroCMS
 * @subpackage 	Tasks Module
 */
class Admin extends Admin_Controller
{
	protected $section = 'tasks';

	protected $validation_rules = array(
		array(
			'field' => 'id_site',
			'label' => 'Site',
			'rules' => 'numeric|is_natural|required'
		),
		array(
			'field' => 'id_profile',
			'label' => 'Vendor',
			'rules' => 'numeric|is_natural|required'
		),
		array(
	            'field' => 'action_at',
	            'label' => 'Follow Up Date',
	            'rules' => 'trim|required'
	    ),
		array(
			'field' => 'desc',
			'label' => 'Task',
			'rules' => 'trim|required'
	                ),
		array(
			'field' => 'progress',
			'label' => 'Progress',
			'rules' => 'trim|numeric|required'
	                ),
		array(
			'field' => 'cost',
			'label' => 'Cost',
			'rules' => 'trim|numeric|decimal[2]'
	                ),
		array(
			'field' => 'volume_type',
			'label' => 'Task Type',
			'rules' => 'trim|numeric'
	                ),
		array(
			'field' => 'volume',
			'label' => 'Task Qty',
			'rules' => 'trim|numeric'
	                ),
	);

	public function __construct()
	{
		parent::__construct();

		$this->output->enable_profiler(TRUE);

		// Load all the required classes
		$this->load->model('task_m');
		$this->load->model('groups/group_m');
		$this->load->model('users/user_m');
		$this->load->model('settings/settings_m');
		$this->load->model('sites/sites_m');
		$this->load->library('form_validation');
		$this->lang->load('tasks');

		// Date ranges for select boxes
		$this->template
			->set('hours', array_combine($hours = range(0, 23), $hours))
			->set('minutes', array_combine($minutes = range(0, 59), $minutes))
		;

		$this->template->set('progress_options', $this->task_m->get_progress_options());

		$this->template->set('vol_types', $this->task_m->get_volume_types());

		$_vendors = array(0 => lang('tasks_non_vendor').' '. lang('tasks_singular'));
		if ($users = $this->user_m->get_many_by(array('group_id' => $this->settings_m->get('vendor_group')->value)))
		{
			foreach ($users as $user)
			{
				$_vendors[$user->id] = $user->display_name;
			}
		}
		$this->template->set('vendors', $_vendors);

		$prefix = $this->db->dbprefix;
        $this->db->set_dbprefix('core_');
        $_sites = $this->sites_m->dropdown('id', 'domain');
        array_unshift($_sites, lang('tasks_no_assoc_site'));
        $this->db->set_dbprefix($prefix);
		$this->template->set('sites', $_sites);

		// We'll set the partials and metadata here since they're used everywhere
//		$this->template->append_js('module::admin.js')
//                    ->append_css('module::admin.css');
	}

	/**
	 * List all items
	 */
	public function index()
	{
		// here we use MY_Model's get_all() method to fetch everything
		$items = $this->task_m->get_all();
		// print_r($items);

		// Build the view with sample/views/admin/items.php
		$this->data->tasks =& $items;
		$this->template->title($this->module_details['name'])
						->build('admin/tasks', $this->data);
	}

	public function create()
	{
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->task_m->insert(array(
					'id_profile'	=> $this->input->post('id_profile'),
					'id_site'		=> $this->input->post('id_site'),
					'action_at'		=> strtotime($this->input->post('action_at')),
					'desc'			=> $this->input->post('desc'),
					'progress'		=> $this->input->post('progress'),
					'volume'		=> $this->input->post('volume'),
					'volume_type'	=> $this->input->post('volume_type'),
					'cost'			=> $this->input->post('cost'),
					'updated_at'	=> now(),
					'created_at'	=> now(),
				))
			)
			{
				// All good...
				$this->session->set_flashdata('success', lang('tasks_success'));
				redirect('admin/tasks');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('tasks_error'));
				redirect('admin/tasks/create');
			}
		} else {
			// Go through all the known fields and get the post values
			foreach ($this->validation_rules as $key => $field)
			{
				$task->$field['field'] = set_value($field['field']);
			}
			$task->action_at = strtotime("+3 day");
		}
		
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('tasks_create'))
			->set('task', $task)
			->build('admin/form', $this->data);
	}
	
	public function edit($id = 0)
	{
		$this->data['task'] = $this->task_m->get($id);

		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			
			// See if the model can create the record
			if($this->task_m->update($id, array(
					'id_profile'	=> $this->input->post('id_profile'),
					'id_site'		=> $this->input->post('id_site'),
					'action_at'		=> strtotime($this->input->post('action_at')),
					'desc'			=> $this->input->post('desc'),
					'progress'		=> $this->input->post('progress'),
					'volume'		=> $this->input->post('volume'),
					'volume_type'	=> $this->input->post('volume_type'),
					'cost'			=> $this->input->post('cost'),
					'updated_at'	=> now(),
				))
			)
			{
				// All good...
				$this->session->set_flashdata('success', lang('tasks_success'));
				redirect('admin/tasks');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('sample.error'));
				redirect('admin/sample/create');
			}
		}

		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('sample.edit'))
						->build('admin/form', $this->data);
	}
	
	public function delete($id = 0)
	{
		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			// pass the ids and let MY_Model delete the items
			$this->sample_m->delete_many($this->input->post('action_to'));
		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
			$this->sample_m->delete($id);
		}
		redirect('admin/sample');
	}
}
