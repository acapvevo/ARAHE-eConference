<?php

namespace App\Plugins;

class Timezone
{
    static function getTimezone($ip = null)
    {
        $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));

        return $geo['geoplugin_timezone'];
    }
}
