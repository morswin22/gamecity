<?php

require_once "user.php";

class Game {
    private $user;
    public $time;

    public function __construct() {
        $this->time = time();
    }

    public function run($userEmail, $userSaveId) {
        try {
            $this->user = new User($userEmail);
            if ($this->user->loadSave($userSaveId)) {
                
            } 
            print(json_encode($this->user->save));
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}

?>