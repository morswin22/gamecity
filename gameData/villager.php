<?php

class Villager {
    public function __construct($type,$config) {
        $this->villagerConfig = setupConfig['villagerConfig'];

        $this->type = $type;
        if ($config != false) {
            foreach($this->villagerConfig as $key => $randoms) {
                $this->$key = $config[$key];
            }
        } else {
            // randomize!
            foreach($this->villagerConfig as $key => $randoms) {
                $this->$key = $randoms[array_rand($randoms)];
            }
        }
    }

    public function getConfig() {
        $config = array('type'=>$this->type);
        foreach($this->villagerConfig as $key => $randoms) {
            $config[$key] = $this->$key;
        }
        return $config;
    }
}

function VillagerFromConfig($config) {
    return new Villager($config['type'], $config);
}

?>