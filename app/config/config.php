<?php

class Config {

    private $config;

    public function __construct() {

        $this->config = [

            'db' => [
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
                'host' =>  $_ENV['DB_HOST'],
                'driver' =>  $_ENV['DB_DRIVER']
            ]

        ];

    }

    public function getDbConfig() {
        return $this->config['db'];
    }
}