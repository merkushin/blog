<?php
class validate
{
	public static function username($username) {
        if (!preg_match('~^[a-z0-9_]+$~i', $username)) return false;
        if (strlen($username) < 3) return false;
        if (strlen($username) > 30) return false;
        return true;
    }

	public static function email($email) {
        if (!preg_match('~^[a-z0-9_.-]+@([a-z0-9_-]+\.)+[a-z]{2,6}$~i', $email)) return false;
        list($login, $domen) = explode('@', $email);
        return true;
    }
}
?>