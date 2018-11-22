<?php

require_once "game.php";

class GameSetup extends Game {
    public function __construct($email, $saveId, $config) {
        $this->loadSave($email, $saveId);

        header('Content-type: text/plain');

        $villagersTurns = array('LumberjackCount'=>'lumberjack', 'FarmerCount'=>'farmer', 'BlacksmithCount'=>'blacksmith', 'MinerCount'=>'miner');
        $newVillagers = array();

        $gameplaySettings = array('VillagersHunger', 'VillagersHealthRegeneration', 'AnimalSpawns', 'ZombieSieges', 'MerchantArrivals');
        $newGameplaySettings = array();

        $difficulty = 0;
        $maxDifficulty = 0;

        $baseBuildings = array('community storage', 'mine', 'farm', 'lumber mill');
        $buildings = array(); // TODO: create basic buildings

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

        $this->user->save = array(
            'needsSetup' => false,
            'setupConfig' => $config,
            'villagers' => $newVillagers,
            'buildings' => $buildings,
            'gameplaySettings' => $newGameplaySettings,
            'difficulty' => $difficulty/$maxDifficulty
        );
        print_r($this->user);
    }

    public function loadSave($userEmail, $userSaveId) {
        try {
            $this->user = new User($userEmail);
            return $this->user->loadSave($userSaveId);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}

?>