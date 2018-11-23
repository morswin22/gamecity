<?php

require_once "game.php";

class GameSetup extends Game {
    public function __construct($email, $saveId, $config) {
        if($this->loadSave($email, $saveId)) {
            $setupFile = json_decode(file_get_contents('gameData/setup.json'),true);

            $villagersTurns = $setupFile['baseVillagers'];
            $newVillagers = array();

            $gameplaySettings = $setupFile['gameplaySettings'];
            $newGameplaySettings = array();
            
            $baseBuildings = $setupFile['baseBuildings'];
            $buildings = array();
            foreach($baseBuildings as $type) {
                $building = new Building($type, false);
                $buildings[$type] = $building->getConfig();
            }

            $difficulty = 0;
            $maxDifficulty = 0;

            foreach($config as $key => $value) {
                if (isset($villagersTurns[$key])) {
                    for($i = 0; $i < $value; $i++) {
                        $villager = new Villager($villagersTurns[$key], false);
                        $newVillagers[] = $villager->getConfig();
                        // TODO: calculate difficulty for every setup villager
                    }
                }
                if (in_array($key, $gameplaySettings)) {
                    $newGameplaySettings[$key] = $value;
                    $difficulty += $value;
                    $maxDifficulty += 2;
                }
            }
            
            $this->user->updateSave($saveId, array(
                'needsSetup' => false,
                'setupConfig' => $config,
                'villagers' => $newVillagers,
                'buildings' => $buildings,
                'gameplaySettings' => $newGameplaySettings,
                'difficulty' => $difficulty/$maxDifficulty
            ));
        }
    }

    public function loadSave($userEmail, $userSaveId) {
        try {
            $this->user = new User($userEmail);
            return !$this->user->loadSave($userSaveId);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}

?>