<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a sample module for PyroCMS
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS
 * @subpackage 	Sample Module
 */
class Task_m extends MY_Model {

    private $progress_options;
    private $volume_types;

	public function __construct()
	{	
            
            
            
        parent::__construct();

        $this->lang->load('tasks');

        $this->progress_options = array(
                0 => lang('tasks_open_label'),
                1 => lang('tasks_waiting_label'),
                2 => lang('tasks_received_label'),
                3 => lang('tasks_completed_label'),
            );
        $this->volume_types = array(
                0 => lang('tasks_novol_label'),
                1 => lang('tasks_artlt500_label'),
                2 => lang('tasks_artgt500_label'),
                3 => lang('tasks_wiki_label'),
                4 => lang('tasks_comment_label'),
                5 => lang('tasks_profile_label'),
                6 => lang('tasks_blog_label'),
                7 => lang('tasks_edugov_label'),
            ); 

        /**
         * I'm pulling from multiple tables with diff prefixes.
         * So we're just gonna turn off active record's prefixing
         * and do it ourselves.  
         */

        $default = $this->_clear_prefix();
		$this->profiles_table = $default.'profiles';
        $this->sites_table = 'core_sites';
        $this->_table = $default.'tasks';
        $this->db->set_dbprefix($default);   

	}

    private function _clear_prefix() {
        $prefix = $this->db->dbprefix;
        
        $this->db->set_dbprefix();
        return $prefix;
    }
	
    public function get_sites() {
        $prefix = $this->_clear_prefix();
        $this->db->set_dbprefix('core_');
        $ret = $this->sites_m->get_all();
        $this->db->set_dbprefix($prefix);
        return $ret;
    }

    public function get_progress_options() {
        return $this->progress_options;
    }

    public function get_volume_types() {
        return $this->volume_types;
    }

	//make sure the slug is valid
	public function _check_slug($slug)
	{
		$slug = strtolower($slug);
		$slug = preg_replace('/\s+/', '-', $slug);

		return $slug;
	}
        
    public function get_all()
    {
        
        $prefix = $this->_clear_prefix();
        
            
        $this->db
            ->select($this->_table.'.*, '.$this->profiles_table.'.*, s.*')
            ->select('ELT('.$this->_table.'.progress + 1,' . "'" . implode("','", $this->progress_options) . "'" . ') AS progress_label', FALSE)
            ->select('ELT('.$this->_table.'.volume_type + 1,' . "'" . implode("','", $this->volume_types) . "'" . ') AS volume_type_label', FALSE)
            ->join($this->profiles_table, $this->profiles_table.'.user_id = '.$this->_table.'.id_profile', 'left')
        
            ->join($this->sites_table.' s', 's.id = '.$this->_table.'.id_site', 'left')
            ->group_by($this->profiles_table.'.id');

        $return = parent::get_all();
        $this->db->set_dbprefix($prefix);
        return $return;
    }
}
