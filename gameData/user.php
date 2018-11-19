<?php

class User {
    private $data;
    private $save;

    public function __construct($email) {
        if (is_file("data/users/$email.json")) {
            $this->data = json_decode(file_get_contents("data/users/$email.json"), true);
        } else {
            throw new Exception('User does not exist');
        }
    }

    public function loadSave($saveId) {
        if (is_dir("data/saves/".$this->data['email']."/")) {
            if (is_file("data/saves/".$this->data['email']."/save$saveId.json")) {
                $this->save = json_decode(file_get_contents("data/saves/".$this->data['email']."/save$saveId.json"), true);
                // here i have finished yesterday
            } else {
                throw new Exception("Didn't find save with this id");
            }
        } else {
            throw new Exception('No saves stored');
        }
    }
}

?>