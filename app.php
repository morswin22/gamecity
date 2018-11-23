<?php

require_once 'lib/router/router.php';
require_once 'config.php';

route('/', function() {
    render('index.html');
});

route('/game', function() {
    requireLogged();
    render('game.html');
});

route('/canvas', function() {
    requireLogged();
    render('canvas.html');
});

route('/process-game', function() {
    requireLogged();
    if (isset($_SESSION['saveId'])) {
        require "gameData/main.php";
        $game->run($_SESSION['email'], $_SESSION['saveId']);
    } else {
        redirect('/saves');
    }
});

route('/setup-game', function() {
    requireLogged();
    if (isset($_SESSION['saveId'])) {
        $user = json_decode(file_get_contents('data/users/'.$_SESSION['email'].'.json'), true);
        $saveName = '';
        foreach ($user['saves'] as $key => $save) {
            if ($save['id'] == $_SESSION['saveId']) {
                $saveName = $save['name'];
            }
        }
        render('setup.html', array('saveName'=>$saveName));
    } else {
        redirect('/saves');
    }
});

route('/process-setup-game', function() {
    requireLogged();
    if (isset($_SESSION['saveId'])) {
        require "gameData/setup.php";
        $setup = new GameSetup($_SESSION['email'], $_SESSION['saveId'], $_POST);
        redirect('/game');
    } else {
        redirect('/saves');
    }
});

route('/saves', function() {
    requireLogged();
    if (isset($_SESSION['saveId'])) {
        unset($_SESSION['saveId']);
    }
    render('saves.html');
});

route('/getSaves', function() {
    requireLogged();
    $user = json_decode(file_get_contents('data/users/'.$_SESSION['email'].'.json'),true);
    print(json_encode($user['saves']));
});

route('/newSave', function() {
    requireLogged();
    if (isset($_POST['name'])) {
        $name = trim($_POST['name']);
        if (!empty($name)) {
            if (is_dir('data/saves/'.$_SESSION['email'].'/')) {
                $lastIdFile = json_decode(file_get_contents('data/saves/'.$_SESSION['email'].'/lastId.json'),true);
                $lastId = $lastIdFile['lastId'];
            } else {
                $lastId = 0;
                mkdir('data/saves/'.$_SESSION['email'].'/');
                $lastIdFile = array('lastId'=>$lastId);
                file_put_contents('data/saves/'.$_SESSION['email'].'/lastId.json', json_encode($lastIdFile));
            }
            file_put_contents('data/saves/'.$_SESSION['email']."/save$lastId.json", json_encode(array('needsSetup'=>true)));

            $user = json_decode(file_get_contents('data/users/'.$_SESSION['email'].'.json'), true);
            array_push($user['saves'], array('name'=>$name, 'id'=>$lastId));
            file_put_contents('data/users/'.$_SESSION['email'].'.json', json_encode($user));
            
            $lastIdFile['lastId']++;
            file_put_contents('data/saves/'.$_SESSION['email'].'/lastId.json', json_encode($lastIdFile));
        } else {
            setError('Please, insert a save name', 'danger');
        }
    } else {
        setError();
    }
    redirect('/saves');
});

route('/loadSave/:id', function($args) {
    requireLogged();
    $_SESSION['saveId'] = $args['id']; // but first check if that save exists!
    redirect('/game');
});

route('/deleteSave/:id', function($args) {
    requireLogged();
    if (is_file('data/saves/'.$_SESSION['email'].'/save'.$args['id'].'.json')) {
        $user = json_decode(file_get_contents('data/users/'.$_SESSION['email'].'.json'), true);
        $saveName = '';
        foreach ($user['saves'] as $key => $save) {
            if ($save['id'] == $args['id']) {
                $saveName = $save['name'];
            }
        }
        render('deleteSave.html', array('saveId'=>$args['id'], 'saveName'=>$saveName));
    } else {
        redirect('/saves');
    }
});

route('/deleteSaveConfirmed/:id', function($args) {
    requireLogged();
    if (is_file('data/saves/'.$_SESSION['email'].'/save'.$args['id'].'.json')) {
        $user = json_decode(file_get_contents('data/users/'.$_SESSION['email'].'.json'), true);
        $saveKey = -1;
        foreach ($user['saves'] as $key => $save) {
            if ($save['id'] == $args['id']) {
                $saveKey = $key;
            }
        }
        if ($saveKey != -1) {
            unlink('data/saves/'.$_SESSION['email'].'/save'.$args['id'].'.json');
            unset($user['saves'][$saveKey]);
            repairArray($user['saves']);
            file_put_contents('data/users/'.$_SESSION['email'].'.json', json_encode($user));
            if (count(scandir('data/saves/'.$_SESSION['email'].'/')) == 3) {
                file_put_contents('data/saves/'.$_SESSION['email'].'/lastId.json', '{"lastId":0}');
            }
        }
    }
    redirect('/saves');
});

route('/register', function() {
    if (isset($_POST['email'],$_POST['pass'])) {
        $args = array('email'=>trim($_POST['email']),'pass'=>trim($_POST['pass']));
        if (!empty($args['email']) && !empty($args['pass'])) {
            if (!file_exists('data/users/'.$args['email'].'.json')) {
                $lastIdFile = json_decode(file_get_contents('data/lastId.json'),true);
                file_put_contents('data/users/'.$args['email'].'.json', json_encode(array(
                    'id' => $lastIdFile['lastId'],
                    'email' => $args['email'],
                    'pass' => $args['pass'],
                    'email-confirmed' => false,
                    'saves' => array()
                )));
                $lastIdFile['lastId']++;
                file_put_contents('data/lastId.json', json_encode($lastIdFile));
                setError('Please, confirm your e-mail address in order to log in.', 'warning');
            } else {
                setError('Account with this e-mail address already exists.', 'danger');
            }
        } else {
            setError('Please, enter your e-mail and password.', 'danger');
        }
    } else {
        setError();
    }
    redirect('/');
});

route('/login', function() {
    if (isset($_POST['email'],$_POST['pass'])) {
        $args = array('email'=>trim($_POST['email']),'pass'=>trim($_POST['pass']));
        if (!empty($args['email']) && !empty($args['pass'])) {
            if (file_exists('data/users/'.$args['email'].'.json')) {
                $data = json_decode(file_get_contents('data/users/'.$args['email'].'.json'), true);
                if ($data['pass'] == $args['pass']) {
                    if ($data['email-confirmed'] === true) {
                        setError('You have been successfully logged in!', 'success');
                        $_SESSION['email'] = $data['email'];
                        // if in session SaveId is stored, redirect user to the game
                        if (isset($_SESSION['saveId'])) {
                            redirect('/game');
                        } else {
                            redirect('/saves');
                        }
                    } else {
                        setError('You have to confirm your e-mail address in order to log in.', 'warning');
                        redirect('/');
                    }
                } else {
                    setError('Wrong password.', 'danger'); // TODO: link to reset
                    redirect('/');
                }
            } else {
                setError('Account with this e-mail address does not exist', 'danger');
                redirect('/');
            }
        } else {
            setError('Please, enter your e-mail and password.', 'danger');
            redirect('/');
        }
    } else {
        setError();
        redirect('/');
    }
});

route('/logout', function() {
    unset($_SESSION['email']);
    setError('Successfully logged out!', 'success');
    redirect('/');
});

?>