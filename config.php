<?php

require_once 'lib/router/router.php';

function setError($msg = 'Unknown error', $type = 'danger') {
    $_SESSION['__errors'] = getErrors();
    array_push($_SESSION['__errors'], array($msg, $type));
}
function getErrors() {
    return (isset($_SESSION['__errors']) ? $_SESSION['__errors'] : array());
}
function getHTMLErrors() {
    $errors = getErrors();
    $html = '<ul class="error-list">';
    foreach($errors as $id => $error) {
        $html .= '<li class="'.$error[1].'"><i class="material-icons">close</i> '.$error[0].'</li>';
    }
    $html .= '</ul>';
    return $html;
}

default_data(array(
    'title' => 'Gamecity',
    'author' => 'Patryk Janiak',
    'errors' => getHTMLErrors()
));

$_SESSION['__errors'] = array();

function requireLogged() {
    $ok = false;
    if (isset($_SESSION['email'])) {
        if (file_exists('data/users/'.$_SESSION['email'].'.json')) {
            $ok = true;
        }
    }
    if (!$ok) {
        setError('Please, log in.', 'danger');
        redirect('/');
        exit;
    }
}

function repairArray(&$array) {
    $newArray = array();
    foreach($array as $value) {
        $newArray[] = $value;
    }
    $array = $newArray;
}

?>