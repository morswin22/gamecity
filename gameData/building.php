<?php

// TODO: procution-item chances are sorted from most to fewest, or idk

class Building {
    public function __construct($type,$config) {
        $this->buildingConfig = setupConfig['buildingConfig'];

        $this->type = $type;
        if ($config != false) {
            foreach($this->buildingConfig as $key => $randoms) {
                $this->$key = $config[$key];
            }
        } else {
            // randomize!
            foreach($this->buildingConfig['default'] as $key => $values) {
                $this->$key = $values;
            }
            foreach($this->buildingConfig[$this->type] as $key => $values) {
                $this->$key = $values;
            }
        }
    }

    public function getConfig() {
        $config = array('type'=>$this->type);
        foreach($this->buildingConfig['default'] as $key => $randoms) {
            $config[$key] = $this->$key;
        }
        foreach($this->buildingConfig[$this->type] as $key => $randoms) {
            $config[$key] = $this->$key;
        }
        return $config;
    }
}

function BuildingFromConfig($config) {
    return new Building($config['type'], $config);
}

?>