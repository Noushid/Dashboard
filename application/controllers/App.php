<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * App Class
 *
 * Stop talking and start faking!
 */
class App extends CI_Controller
{
    protected $limit = 15;

    function __construct()
    {
        parent::__construct();
        require_once '../vendor/fzaninotto/faker/src/autoload.php';

//         can only be called from the command line
        if (!$this->input->is_cli_request()) {
            exit('Direct access is not allowed');
        }

        // can only be run in the development environment
        if (ENVIRONMENT !== 'development') {
            exit('Wowsers! You don\'t want to do that!');
        }

        // initiate faker
        $this->faker = Faker\Factory::create();

        // load any required models
        $this->load->model('User_Model', 'user_model');
        $this->load->model('Employee_Model', 'employee');
        $this->load->model('File_Model', 'file');
        $this->load->model('Gallery_Files_Model', 'gallery_files');
        $this->load->model('Gallery_Model', 'gallery');
        $this->load->model('Portfolio_Files_model', 'portfolio_files');
        $this->load->model('Portfolio_Model', 'portfolio');
        $this->load->model('Testimonial_Model', 'testimonial');

    }

    /**
     * seed local database
     */
    function seed()
    {
        // purge existing data
        $this->_truncate_db();

        // seed users
        $this->_seed_users(1);

        // seed employee
        $this->_seed_employee($this->limit);

        //seed file
        $this->_seed_file($this->limit);

        //seed galleries
        $this->_seed_gallery($this->limit);

        //seed gallery files
        $this->_seed_gallery_file($this->limit);

        //seed portfolio
        $this->_seed_portfolio($this->limit);

        //seed portfolio files
        $this->_seed_portfolio_file($this->limit);

        //seed testimonial
        $this->_seed_testimonial($this->limit);

    }

    public function seedUser()
    {
        $this->_seed_users(1);
    }

    /**
     * seed users
     *
     * @param int $limit
     */
    function _seed_users($limit)
    {
        echo "seeding $limit users";

        // create a bunch of base buyer accounts
        for ($i = 0; $i < $limit; $i++) {
            echo ".";

            $data = array(
                'username' => 'admin', // get a unique nickname
                'password' => hash('sha256','admin') // run this via your password hashing function
            );

            $this->user_model->add($data);
        }

        echo PHP_EOL;
    }

    function _seed_employee($limit)
    {
        echo "seeding $limit employees";
        //create branch of base buyer accounts
        for ($i= 0; $i < $limit; $i++) {
            echo ".";

            $data = [
                'designation' => $this->faker->jobTitle,
                'files_id' => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10]),
                'name' => $this->faker->name,
                'address' => $this->faker->address,
                'email' => $this->faker->email,
                'telephone' => $this->faker->phoneNumber,
                'mobile' => $this->faker->phoneNumber,
                'website' => $this->faker->url,
                'linkedin' => $this->faker->url,
                'facebook' => $this->faker->url,
                'twitter' => $this->faker->url,
                'github' => $this->faker->url,
                'googleplus' => $this->faker->url,
                'gender' => $this->faker->url,
                'place' => $this->faker->url
            ];

            $this->employee->add($data);
        }
        echo PHP_EOL;
    }

    function _seed_file($limit)
    {
        echo "seeding $limit files";

        for ($i = 0; $i < $limit; $i++) {
            echo ".";
            $data = [
                'file_name' => $this->faker->file(FCPATH.'/temp', FCPATH.'/uploads'),
                'file_type' => $this->faker->fileExtension,
            ];

            $this->file->add($data);
        }
        echo PHP_EOL;
    }

    function _seed_gallery($limit)
    {
        echo "seeding $limit gallery";

        for ($i = 0; $i < $limit; $i++) {
            echo ".";
            $data = [
                'name' => $this->faker->name,
                'description' => $this->faker->sentence(6)
            ];

            $this->gallery->add($data);
        }
        echo PHP_EOL;
    }

    function _seed_gallery_file($limit)
    {
        echo "seeding $limit gallery files";

        for ($i = 0; $i < $limit; $i++) {
            echo ".";
            $data = [
                'files_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
                'galleries_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
            ];

            $this->gallery_files->add($data);
        }
        echo PHP_EOL;
    }

    function _seed_portfolio_file($limit)
    {
        echo "seeding $limit portfolio files";

        for ($i = 0; $i < $limit; $i++) {
            echo ".";
            $data = [
                'portfolios_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
                'files_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])

            ];

            $this->portfolio_files->add($data);
        }
        echo PHP_EOL;
    }


    function _seed_portfolio($limit)
    {
        echo "seeding $limit portfolios";

        for ($i = 0; $i < $limit; $i++) {
            echo ".";
            $data = [
                'name' => $this->faker->name,
                'type' => $this->faker->randomElement(['portfolio site', 'web application', 'ERP', 'standalone']),
                'date' => $this->faker->date(),
                'link' => $this->faker->url,
                'clientname' => $this->faker->name(),
                'description' => $this->faker->sentence(16),
                'feedback' => $this->faker->sentence(6),
                'files_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])
            ];

            $this->portfolio->add($data);
        }
        echo PHP_EOL;
    }


    function _seed_testimonial($limit)
    {
        {
            echo "seeding $limit testimonials";

            for ($i = 0; $i < $limit; $i++) {
                echo ".";
                $data = [
                    'name' => $this->faker->name,
                    'description' => $this->faker->sentence(16),
                    'link' => $this->faker->url,
                    'files_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])
                ];

                $this->testimonial->add($data);
            }
            echo PHP_EOL;
        }
    }


    private function _truncate_db()
    {
        $this->user_model->trunc();
        $this->employee->trunc();
        $this->file->trunc();
        $this->gallery->trunc();
        $this->gallery_files->trunc();
        $this->portfolio_files->trunc();
        $this->portfolio->trunc();
        $this->testimonial->trunc();
    }
}
