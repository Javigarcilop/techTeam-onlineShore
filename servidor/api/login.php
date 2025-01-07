<?php
// login.php

// Rutas de los archivos JSON
$usersFile = '../JSON/usuarios.json';
$storeFile = '../JSON/tienda.json';

// Obtener la entrada JSON enviada por el cliente
$input = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos enviados estén completos
if (!isset($input['username']) || !isset($input['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Usuario y contraseña son requeridos.']);
    exit;
}

// Leer los datos de los usuarios y la tienda desde los archivos JSON
$users = json_decode(file_get_contents($usersFile), true);
$store = json_decode(file_get_contents($storeFile), true);

// Verificar si se encontró el usuario
$foundUser = null;
foreach ($users['usuarios'] as $user) {
    if ($user['user'] === $input['username'] && $user['password'] === $input['password']) {
        $foundUser = $user;
        break;
    }
}

// Si las credenciales son correctas, generar un token y devolver los datos de la tienda
if ($foundUser) {
    $response = [
        'token' => bin2hex(random_bytes(16)), // Generar un token aleatorio
        'storeData' => $store, // Datos de la tienda
    ];
    echo json_encode($response); // Responder al cliente con el token y los datos
    exit;
}

// Si las credenciales son incorrectas
http_response_code(401); // Unauthorized
echo json_encode(['error' => 'Credenciales inválidas']);
?>






