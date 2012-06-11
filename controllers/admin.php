<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a sample module for PyroCMS
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS
 * @subpackage 	Sample Module
 */
class Admin extends Admin_Controller
{
	protected $section = 'items';

	public function __construct()
	{
		parent::__construct();

		// Load all the required classes
		$this->load->model('task_m');
		$this->load->library('form_validation');
		//$this->lang->load('sample');

		// Set the validation rules
		$this->item_validation_rules = array(
			array(
				'field' => 'id_profile',
				'label' => 'Vendor',
				'rules' => 'numeric|is_natural|required'
			),
			array(
				'field' => 'id_site',
				'label' => 'Site',
				'rules' => 'numeric|is_natural|required'
			),
			array(
				'field' => 'id_site',
				'label' => 'Site',
				'rules' => 'numeric|is_natural|required'
			),
			array(
                                'field' => 'action_at',
                                'label' => 'Follow Up Date',
                                'rules' => 'trim'
                        ),
                        array(
                                'field' => 'action_at_hour',
                                'label' => 'Follow Up Hour',
                                'rules' => 'trim|numeric'
                        ),
                        array(
                                'field' => 'action_at_minute',
                                'label' => 'Follow Up Minute',
                                'rules' => 'trim|numeric'
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
			array(
				'field' => 'desc',
				'label' => 'Task',
				'rules' => 'trim|numeric|decimal[2]'
                        ),
			array(
				'field' => 'desc',
				'label' => 'Task',
				'rules' => 'trim|numeric|decimal[2]'
			)
		);

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

		// Build the view with sample/views/admin/items.php
		$this->data->items =& $items;
		$this->template->title($this->module_details['name'])
						->build('admin/tasks', $this->data);
	}

	public function create()
	{
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->sample_m->create($this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('sample.success'));
				redirect('admin/sample');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('sample.error'));
				redirect('admin/sample/create');
			}
		}
		
		foreach ($this->item_validation_rules AS $rule)
		{
			$this->data->{$rule['field']} = $this->input->post($rule['field']);
		}

		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('sample.new_item'))
						->build('admin/form', $this->data);
	}
	
	public function edit($id = 0)
	{
		$this->data = $this->sample_m->get($id);

		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// get rid of the btnAction item that tells us which button was clicked.
			// If we don't unset it MY_Model will try to insert it
			unset($_POST['btnAction']);
			
			// See if the model can create the record
			if($this->sample_m->update($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('sample.success'));
				redirect('admin/sample');
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
