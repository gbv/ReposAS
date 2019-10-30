<?php

namespace ReposAS;


class Configuration
{
    public static function getConfig()
    {
        $configPath = __DIR__ . '/../config/config.ini';
        $config = parse_ini_file($configPath);
        return $config;
    }
}
