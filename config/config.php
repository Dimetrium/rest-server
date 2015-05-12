<?php

return new \Phalcon\Config(array(

    'database' => array(
        'adapter'    => 'Mysql',
        'host'       => 'localhost',
        'username'   => 'root',
        'password'   => '',
        'dbname'     => 'rest',
        'charset'    => 'utf8',
    ),

    'application' => array(
        'modelsDir'      => APP_PATH . '/models/',
        'baseUri'        => '/rest-server/',
    )
));
