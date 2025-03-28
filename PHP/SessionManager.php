<?php
class SessionManager {
    public static function startSession() {
        if (php_sapi_name() !== 'cli' && session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}