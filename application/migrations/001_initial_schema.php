<?php
/**
 * Created by PhpStorm.
 * User: noushi
 * Date: 18/7/16
 * Time: 5:02 PM
 */

defined('BASEPATH') OR exit('No Direct script access allowed');

class Migration_Initial_schema extends CI_Migration
{
    public function __construct()
    {
        parent::__construct();

    }

    public function up()
    {
        /*Users*/
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('users');

        /*files*/

        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'file_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('files');

        /*gallery_files*/

        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'files_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => TRUE
            ],
            'galleries_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => TRUE
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('gallery_files');

        /*testimonial*/

        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 955,
                'null' => TRUE
            ],
            'link' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'files_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => TRUE
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('testimonials');


        /*address*/

        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 955,
                'null' => TRUE
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'telephone' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => TRUE
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 13,
                'null' => TRUE
            ],
            'website' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'linkedin' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'facebook' => [
                'type' => 'VARCHAR',
                'constraint' => 955,
                'null' => TRUE
            ],
            'twitter' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'github' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'googleplus' => [
                'type' => 'VARCHAR',
                'constraint' => 955,
                'null' => TRUE
            ],
            'gender' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE
            ],
            'place' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('address');


        /*employees*/

        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'designation' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'files_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => TRUE
            ],
            'address_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => TRUE
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('employees');

        /*portfolios*/

        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'link' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 955,
                'null' => TRUE
            ],
            'displaytext' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => TRUE
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('portfolios');

        /*portfolio_files*/

        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'portfolios_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => FALSE
            ],
            'files_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => FALSE
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('portfolio_files');

        /*galleries*/

        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('galleries');

        /*captcha*/

        $this->dbforge->add_field([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 13,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'time' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => TRUE,
            ],
            'ip_address' =>[
                'type' => 'VARCHAR',
                'constraint' => 45,
            ],
            'word' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('captcha');

    }

    public function down()
    {
        $this->dbforge->drop_table('users');
        $this->dbforge->drop_table('files');
        $this->dbforge->drop_table('gallery_files');
        $this->dbforge->drop_table('testimonials');
        $this->dbforge->drop_table('address');
        $this->dbforge->drop_table('employees');
        $this->dbforge->drop_table('portfolios');
        $this->dbforge->drop_table('portfolio_files');
        $this->dbforge->drop_table('galleries');
        $this->dbforge->drop_table('captcha');

    }
}