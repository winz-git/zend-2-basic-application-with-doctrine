<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'db' => array(
        'adapters' => array(
            'adapter' => array(
                'driver' => 'Pdo_Mysql',
                'dsn'    => 'mysql:dbname=zf2-doctrine;host=localhost',
                'username' => 'root',
                'password' => '1234$'
            ),
            'adapter2' => array(
                'driver' => 'Pdo_Mysql',
                'dsn'    => 'mysql:dbname=zf2-doctrine;host=localhost',
                'username' => 'root',
                'password' => '1234$'
            ),
            'adapter3' => array(
                'driver' => 'Pdo_Mysql',
                'dsn'    => 'mysql:dbname=zf2-doctrine;host=localhost',
                'username' => 'root',
                'password' => '1234$'
            ),
        ),

    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
            => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);