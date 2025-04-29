<?php
class SessionManager {
    // Démarre une session si elle n'est pas déjà active et si nous ne sommes pas en ligne de commande
    public static function startSession() {
        if (php_sapi_name() !== 'cli' && session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}