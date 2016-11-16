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
    protected $limit = 20;

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
        $this->load->model('address_Model', 'address');
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
        $this->_seed_users($this->limit);

        // seed address
        $this->_seed_address($this->limit);

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
                'username' => $this->faker->unique()->userName, // get a unique nickname
                'password' => 'awesomepassword', // run this via your password hashing function
                /*'firstname' => $this->faker->firstName,
                'surname' => $this->faker->lastName,
                'address' => $this->faker->streetAddress,
                'city' => $this->faker->city,
                'state' => $this->faker->state,
                'country' => $this->faker->country,
                'postcode' => $this->faker->postcode,
                'email' => $this->faker->email,
                'email_verified' => mt_rand(0, 1) ? '0' : '1',
                'phone' => $this->faker->phoneNumber,
                'birthdate' => $this->faker->dateTimeThisCentury->format('Y-m-d H:i:s'),
                'registration_date' => $this->faker->dateTimeThisYear->format('Y-m-d H:i:s'),
                'ip_address' => mt_rand(0, 1) ? $this->faker->ipv4 : $this->faker->ipv6,*/
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
                'address_id' => $this->faker->randomElement([1,2,3,4,5,6,7,8,9,10])
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


    function _seed_address($limit)
    {
        echo "seeding $limit address";

        for ($i = 0; $i < $limit; $i++) {
            echo ".";
            $data = [
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

            $this->address->add($data);
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
                'link' => $this->faker->url,
                'description' => $this->faker->sentence(16),
                'displaytext' => $this->faker->sentence(6)
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
        $this->address->trunc();
        $this->gallery->trunc();
        $this->gallery_files->trunc();
        $this->portfolio_files->trunc();
        $this->portfolio->trunc();
        $this->testimonial->trunc();
    }
}