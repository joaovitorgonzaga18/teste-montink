<?php

require_once FCPATH . 'vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Doctrine
{
    public $em;

    public function __construct() {
        $paths = [APPPATH . 'Entities'];
        $isDevMode = true;

        require APPPATH . 'config/database.php';
        $db = $db['default'];

        $dbParams = [
            'driver'   => 'pdo_mysql',
            'user'     => $db['username'],
            'password' => $db['password'],
            'dbname'   => $db['database'],
            'host'     => $db['hostname'],
            'charset'  => 'utf8'
        ];

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $this->em = EntityManager::create($dbParams, $config);
    }
}
