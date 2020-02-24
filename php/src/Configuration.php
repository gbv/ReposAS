<?php

namespace epusta;

class Configuration
{
    private $config;

    public function __construct()
    {
        $configPath = __DIR__ . '/../../config/config.ini';
        $this->config = parse_ini_file($configPath);
    }

    public function getConfig()
    {
        return $this->config;
    }
}
