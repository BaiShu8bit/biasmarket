<?php

class ConnectionDB {

    public static $instance;

    private function __construct() {}

    public static function getInstance() {

        if (!isset(self::$instance)) {

            try {

                // 🔥 CONFIG DIRECTA Y SEGURA

                $host = 'localhost';
                $db   = 'tfg';

                // 👉 LOCAL (XAMPP)
                if (gethostname() === 'DESKTOP-LOCAL' || $_SERVER['HTTP_HOST'] === 'localhost') {
                    $user = 'root';
                    $pass = '';
                }

                // 👉 PRODUCCIÓN (EC2)
                else {
                    $user = 'biasuser';
                    $pass = 'biaspass';
                }

                self::$instance = new PDO(
                    "mysql:host=$host;dbname=$db;charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );

                self::$instance->exec("SET NAMES utf8mb4");

            } catch (PDOException $e) {

                error_log("DB Connection Error: " . $e->getMessage());
                die("Error de conexión a la base de datos");
            }
        }

        return self::$instance;
    }
}

?>