<?php

namespace ReposAS;


class Configuration
{
    public static function getConfig()
    {
        $configPath = __DIR__ . '/../config/config.json';
        $config = json_decode(file_get_contents($configPath), true);
        return $config;
    }
}
