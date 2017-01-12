<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//$route['default_controller'] = 'welcome';
$route['default_controller'] = 'Dashboard_Controller';
$route['admin'] = 'Dashboard_Controller';

$route['login'] = 'Home/login';
$route['logout'] = 'Dashboard_Controller/logout';
$route['login/verify'] = 'Dashboard_Controller/verify';


$route['admin/portfolio'] = 'Portfolio_Controller';//load template
$route['admin/portfolio/get-all'] = 'Portfolio_Controller/get';
$route['admin/portfolio/upload'] = 'Portfolio_Controller/upload_file';
$route['admin/portfolio/add'] = 'Portfolio_Controller/store';
$route['admin/portfolio/insert-file/(:num)'] = 'Portfolio_Controller/add_file/$1';
$route['admin/portfolio/delete-file'] = 'Portfolio_Controller/delete_file';
$route['admin/portfolio/delete/(:num)'] = 'Portfolio_Controller/delete/$1';
$route['admin/portfolio/delete-image'] = 'Portfolio_Controller/delete_image';
$route['admin/portfolio/edit/(:num)'] = 'Portfolio_Controller/update/$1';

$route['admin/employees'] = 'Employees_Controller'; //load template
$route['admin/employee'] = 'Employees_Controller/get_employees';
$route['admin/employee/add'] = 'Employees_Controller/store';
$route['admin/employee/edit/(:num)'] = 'Employees_Controller/update/$1';
$route['admin/employee/delete/(:num)'] = 'Employees_Controller/delete/$1';


$route['admin/testimonial'] = 'Testimonial_Controller';
$route['admin/testimonial/add'] = 'Testimonial_Controller/store';
$route['admin/testimonial/edit/(:num)'] = 'Testimonial_Controller/update/$1';




$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
