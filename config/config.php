<?php

return new \Phalcon\Config(array(

  'database' => array(
    'adapter'    => 'Mysql',
    'host'       => 'localhost',
//  'username'   => 'root',
//  'password'   => '',
//  'dbname'     => 'rest',
    'username'   => 'user5',
    'password'   => 'tuser5',
    'dbname'     => 'user5',
    'charset'    => 'utf8',
  ),

  'application' => array(
    'modelsDir'      => APP_PATH . '/models/',
    'baseUri'        => '/rest-server/',
  )
));
