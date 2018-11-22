<?php

define('villagerConfig', array(
    'name' => array("Addy","Aldith","Aldreda","Aldus","Amice","Amis","Bate","Col","Daw","Dicun","Diot","Dye","Eda","Elis","Elric","Etheldred","Etheldreda","Firmin","Hamo","Hamon","Hankin","Hann","Herry","Hob","Hopkin","Hudde","Ibb","Iseut","Jackin","Jan","Jankin","Jocosa","Judd","Kinborough","Larkin","Law","Mack","Malle","Matty","Meggy","Molle","Morris","Nichol","Nicol","Noll","Ode","Pate","Randel","Rohese","Rohesia","Roul","Royse","Stace","Tenney","Wilkin","Wilky","Wilmot","Wybert","Wymond","Wyot"),

));

// foreach(villagerConfig['name'] as $value) print('"'.ucfirst(strtolower($value)).'",');

class Villager {
    public function __construct($type,$config) {
        $this->type = $type;
        if ($config != false) {
            foreach(villagerConfig as $key => $randoms) {
                $this->$key = $config[$key];
            }
        } else {
            // randomize!
            foreach(villagerConfig as $key => $randoms) {
                $this->$key = $randoms[array_rand($randoms)];
            }
        }
    }

    public function getConfig() {
        $config = array('type'=>$this->type);
        foreach(villagerConfig as $key => $randoms) {
            $config[$key] = $this->$key;
        }
        return $config;
    }
}

function VillagerFromConfig($config) {
    return new Villager($config['type'], $config);
}

?>