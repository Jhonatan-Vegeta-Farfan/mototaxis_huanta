<?php
require_once __DIR__ . '/../config/database.php';

class Alert {
    public static function send($type, $message) {
        $_SESSION['alert'] = json_encode([
            'type' => $type,
            'message' => $message,
            'timestamp' => time()
        ]);
    }

    public static function show() {
        if (isset($_SESSION['alert'])) {
            $alert = json_decode($_SESSION['alert'], true);
            unset($_SESSION['alert']);
            return $alert;
        }
        return null;
    }
}
?>