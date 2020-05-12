<?php

namespace epusta;

class Configuration
{
    private $config;
    private $configPhpUnit;

    public function __construct()
    {
        $configPath = __DIR__ . '/../../config/config.ini';
        $this->config = parse_ini_file($configPath);
        $configPath = __DIR__ . '/../../config/config.phpunit.ini';
        $this->configPhpUnit = parse_ini_file($configPath);
    }

    public function getConfig()
    {
        return $this->config;
    }
    
    public function getPhpUnitConfig()
    {
        return $this->configPhpUnit;
    }
}
