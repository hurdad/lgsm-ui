<?php

// GET 
$route['get']['/db/base_images/:id'] = array('db/DBBaseImagesController', 'view', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// POST
$route['post']['/db/base_images'] = array('db/DBBaseImagesController', 'create', 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// PUT
$route['put']['/db/base_images/:id'] = array('db/DBBaseImagesController', 'update', 'match'=> array('id'=>'/^\d+$/'),'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// DELETE
$route['delete']['/db/base_images/:id'] = array('db/DBBaseImagesController', 'destroy', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');

// GET 
$route['get']['/db/events/:id'] = array('db/DBEventsController', 'view', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// POST
$route['post']['/db/events'] = array('db/DBEventsController', 'create', 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// PUT
$route['put']['/db/events/:id'] = array('db/DBEventsController', 'update', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// DELETE
$route['delete']['/db/events/:id'] = array('db/DBEventsController', 'destroy', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');

// GET 
$route['get']['/db/games/:id'] = array('db/DBGamesController', 'view', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// POST
$route['post']['/db/games'] = array('db/DBGamesController', 'create', 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// PUT
$route['put']['/db/games/:id'] = array('db/DBGamesController', 'update', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// DELETE
$route['delete']['/db/games/:id'] = array('db/DBGamesController', 'destroy', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');

// GET 
$route['get']['/db/github/:id'] = array('db/DBGithubController', 'view', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// POST
$route['post']['/db/github'] = array('db/DBGithubController', 'create', 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// PUT
$route['put']['/db/github/:id'] = array('db/DBGithubController', 'update', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// DELETE
$route['delete']['/db/github/:id'] = array('db/DBGithubController', 'destroy', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');

// GET 
$route['get']['/db/services/:id'] = array('db/DBServicesController', 'view', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// POST
$route['post']['/db/services'] = array('db/DBServicesController', 'create', 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// PUT
$route['put']['/db/services/:id'] = array('db/DBServicesController', 'update', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// DELETE
$route['delete']['/db/services/:id'] = array('db/DBServicesController', 'destroy', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');

// GET 
$route['get']['/db/vbox_soap_endpoints/:id'] = array('db/DBVboxSoapEndpointsController', 'view', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// POST
$route['post']['/db/vbox_soap_endpoints'] = array('db/DBVboxSoapEndpointsController', 'create', 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// PUT
$route['put']['/db/vbox_soap_endpoints/:id'] = array('db/DBVboxSoapEndpointsController', 'update', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');
// DELETE
$route['delete']['/db/vbox_soap_endpoints/:id'] = array('db/DBVboxSoapEndpointsController', 'destroy', 'match'=> array('id'=>'/^\d+$/'), 'authName'=>'DooPHP Admin', 'auth'=>$user, 'authFail'=>'Unauthorized!');

?>