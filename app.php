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

route('/register', function() {
    if (isset($_POST['email'],$_POST['pass'])) {
        $args = array('email'=>trim($_POST['email']),'pass'=>trim($_POST['pass']));
        if (!empty($args['email']) && !empty($args['pass'])) {
            if (!file_exists('data/users/'.$args['email'].'.json')) {
                file_put_contents('data/users/'.$args['email'].'.json', json_encode(array(
                    'email' => $args['email'],
                    'pass' => $args['pass'],
                    'email-confirmed' => false
                )));
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
                        redirect('/game');
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