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

	public function __construct()
	{	
            /**
             * I'm pulling from multiple tables with diff prefixes.
             * So we're just gonna turn off active record's prefixing
             * and do it ourselves.  
             */
            $default = $this->db->dbprefix;
            $this->profiles_table = $default.'profiles';
            $this->sites_table = 'core_sites';
            $this->_table = $default.'tasks';
            
            parent::__construct();
		
            $this->db->set_dbprefix();
		
	}
	
	//create a new item
	public function create($input)
	{
		$to_insert = array(
			'name' => $input['name'],
			'slug' => $this->_check_slug($input['slug'])
		);

		return $this->db->insert('sample', $to_insert);
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
            
            //$this->profiles_table = $this->db->dbprefix('profiles');
            //$users = $this->db->dbprefix('profiles');
            $prefix = $this->db->dbprefix;
            //$this->db->set_dbprefix('core_');
            //$this->sites_table = $this->db->dbprefix('sites');
            
                
            $this->db
                ->select($this->_table.'.*')
                ->join($this->profiles_table, $this->profiles_table.'.user_id = '.$this->_table.'.id_profile', 'left')
            
                ->join($this->sites_table.' s', 's.id = '.$this->_table.'.id_site')
                ->group_by($this->profiles_table.'.id');

            return parent::get_all();
        }
}
