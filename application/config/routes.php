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
$route['default_controller'] = 'registerlogin';
$route['indosystemfin'] = 'indosystemMain';
$route['indosystem1'] = 'indosystem1';
$route['indosystem2'] = 'indosystem2';
$route['indosystem3'] = 'indosystem3';
$route['indosystem1/processc1'] = 'indosystem1/processc1';
$route['indosystem3/allwishes'] = 'allwishes';
$route['indosystem3/facebook_login']= 'indosystem3/facebook_login';
$route['indosystem3/facebook_logout']= 'indosystem3/facebook_logout';
$route['q1'] = 'q1controller/index';
$route['q2'] = 'q2controller/index';
$route['processq1'] = 'q1controller/process';
$route['processq2'] = 'q2controller/process';

$route['registerlogin'] = 'registerlogin';
$route['check_username'] = 'registerlogin/check_username';
$route['register'] = 'registerlogin/register';
$route['activation/(:any)/(:any)/(:any)/(:any)'] = 'registerlogin/activate/$1/$2/$3/$4';
$route['login'] = 'registerlogin/login';
$route['logout'] = 'logout/logoutNow';
$route['friendsystem/action'] = 'friendsystem/action';
$route['friendsystem/friendrequest'] = 'friendsystem/friendrequest';
$route['search_exec'] = 'search_exec/search';

$route['posttostatus'] = 'status/posttostatus';
$route['replytostatus'] = 'status/replytostatus';
$route['deletestatus'] = 'status/deletestatus';
$route['deletereply'] = 'status/deletereply';

$route['user/(:any)'] = 'user/index/$1';
$route['friends/(:any)'] = 'friends/index/$1';
$route['notification'] = 'notification';

$route['upload_profpict']='user/upload_profpict';

$route['delete_photo']= 'photos/delete_photo';
$route['show_gallery']= 'photos/show_gallery';
$route['upload_pict']= 'photos/upload_pict';
$route['photos/(:any)'] = 'photos/index/$1';

$route['message/fail_logout'] = 'message/fail_logout';
$route['message/fail_activation'] = 'message/fail_activation';
$route['message/not_loggedin']= 'message/not_loggedin';
$route['message/not_exist']= 'message/not_exist';
$route['message/upload_failed']= 'message/upload_failed';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
?>
