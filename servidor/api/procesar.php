<?php
// procesar.php

// Verificar que la acción sea 'login'
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    // Incluir el archivo que maneja la validación del login
    include 'login.php'; 
} else {
    // Si la acción no es válida, responder con un error
    echo json_encode(['error' => 'Acción no válida']);
    http_response_code(400); // Bad Request
}
?>






