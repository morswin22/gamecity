<?php

define('setupConfig', json_decode(file_get_contents('gameData/setup.json'),true));

require_once 'villager.php';
require_once 'building.php';

?>