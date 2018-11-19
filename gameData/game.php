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
            $this->user->loadSave($userSaveId);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        // throw new Exception('msg');
    }
}

?>