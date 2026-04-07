<?php

class SessionCheck
{
    public static function validateToken(string $token): bool
    {
        return false;
    }

    public static function hasActiveSession(): bool
    {
        session_start();
        return isset($_SESSION["user_id"]);
    }
}
?>
