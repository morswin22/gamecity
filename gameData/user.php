<?php

require_once 'save.php';

class User {
    public $data;
    public $save;

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
                if ($this->save['needsSetup']) {
                    return false;
                } else {
                    return true;
                }
            } else {
                throw new Exception("Didn't find save with this id");
            }
        } else {
            throw new Exception('No saves stored');
        }
    }

    public function updateSave($saveId, $data) {
        if (isset($this->save)) {
            foreach($data as $key => $value) {
                $this->save[$key] = $value;
            }
            file_put_contents("data/saves/".$this->data['email']."/save$saveId.json", json_encode($this->save));
        }
    }
}

?>