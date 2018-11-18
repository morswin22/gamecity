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

route('/saves', function() {
    requireLogged();
    render('saves.html');
});

route('/getSaves', function() {
    requireLogged();
    $user = json_decode(file_get_contents('data/users/'.$_SESSION['email'].'.json'),true);
    print(json_encode($user['saves']));
});

route('/loadSave/:id', function($args) {
    requireLogged();
    $_SESSION['saveId'] = $args['id']; // but first check if that save exists!
    redirect('/game');
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