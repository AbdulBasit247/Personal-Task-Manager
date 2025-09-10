<?php
function flash() {
    if (!session_id()) session_start();
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}
function set_flash($type, $msg) {
    if (!session_id()) session_start();
    $_SESSION['flash'] = ['type'=>$type,'msg'=>$msg];
}
