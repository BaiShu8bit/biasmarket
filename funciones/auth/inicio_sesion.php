<?php
// Session configuration
session_set_cookie_params([
    'lifetime' => 86400, // 24 hours
    'path' => '/',
    'domain' => '',
    'secure' => false, // Set to true if using HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require('../../BBDD/connection.php');

// Comprobar datos POST
if (!isset($_POST['usuario']) || !isset($_POST['password'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Datos incompletos"
    ]);
    exit;
}

$nombre_usuario = $_POST['usuario'];
$password = $_POST['password'];

try {

    $instance = ConnectionDB::getInstance();

    // 🔥 1. BUSCAR USUARIO SOLO POR NOMBRE
    $sql = 'SELECT clienteId, nombre_usuario, password, rol FROM clientes WHERE nombre_usuario = :nombre_usuario';
    $stmt = $instance->prepare($sql);
    $stmt->bindValue(':nombre_usuario', $nombre_usuario);
    $stmt->execute();

    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // DEBUG
    error_log("Query results: " . print_r($resultado, true));
    error_log("Number of results: " . count($resultado));

    // 🔥 2. COMPROBAR SI EXISTE
    if (!empty($resultado)) {

        // 🔥 3. VERIFICAR PASSWORD ENCRIPTADA
        if (password_verify($password, $resultado[0]['password'])) {

            // LOGIN OK
            $_SESSION["clienteId"] = $resultado[0]["clienteId"];
            $_SESSION["nombre_usuario"] = $resultado[0]["nombre_usuario"];
            $_SESSION["rol"] = $resultado[0]["rol"];

            $clienteData = [
                "status" => "success",
                "clienteId" => $resultado[0]["clienteId"],
                "rol" => $resultado[0]["rol"]
            ];

            error_log("Login OK: " . json_encode($clienteData));

            echo json_encode($clienteData);
            exit;

        } else {

            error_log("Password incorrecta para: " . $nombre_usuario);

            echo json_encode([
                "status" => "error",
                "message" => "Contraseña incorrecta"
            ]);
            exit;
        }

    } else {

        error_log("Usuario no encontrado: " . $nombre_usuario);

        echo json_encode([
            "status" => "error",
            "message" => "Usuario no encontrado"
        ]);
        exit;
    }

} catch (Exception $e) {

    error_log("Error servidor: " . $e->getMessage());

    echo json_encode([
        "status" => "error",
        "message" => "Error del servidor"
    ]);
    exit;
}
?>