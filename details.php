<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Module_Tasks extends Module {

    public $version = '1.0';

    public function info() {
        return array(
            'name' => array(
                'en' => 'Tasks'
            ),
            'description' => array(
                'en' => 'A module that will help track outsourcing tasks on installed sites.'
            ),
            'frontend' => FALSE,
            'backend' => TRUE,
            'menu' => 'utilities', // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
            'sections' => array(
                'tasks' => array(
                    'name' => 'tasks:tasks', // These are translated from your language file
                    'uri' => 'admin/tasks',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'tasks:create',
                            'uri' => 'admin/tasks/create',
                            'class' => 'add'
                        )
                    )
                )
            )
        );
    }

    public function install() {
        $this->dbforge->drop_table('tasks');

        $schema = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'id_profile' => array(
                'type' => 'INT',
                'constraint' => '11'
            ),
            'id_site' => array(
                'type' => 'INT',
                'constraint' => '11'
            ),
            'action_at' => array(
                'type' => 'INT',
                'constraint' => '11'
            ),
            'desc' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'progress' => array(
                'type' => 'INT',
                'constraint' => '2'
            ),
            'cost' => array(
                'type' => 'DECIMAL',
                'constraint' => array(7, 2)
            ),
            'volume_type' => array(
                'type' => 'INT',
                'constraint' => '2'
            ),
            'volume' => array(
                'type' => 'INT',
                'constraint' => '11'
            ),
            'created_at' => array(
                'type' => 'INT',
                'constraint' => '11'
            ),
            'updated_at' => array(
                'type' => 'INT',
                'constraint' => '11'
            )
        );

        $this->dbforge->add_field($schema);
        $this->dbforge->add_key('id', TRUE);
        
        $this->load->model('groups/group_m');

        if ($this->dbforge->create_table('tasks') && $this->group_m->insert(array('name' => 'vendors', 'description' => 'Vendors'))) {
            return TRUE;
        }
    }

    public function uninstall() {
        $this->dbforge->drop_table('tasks');
            return TRUE;
    }

    public function upgrade($old_version) {
        // Your Upgrade Logic
        return TRUE;
    }

    public function help() {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }

}

/* End of file details.php */
