<?php
session_start();

// Cargar los productos desde el archivo JSON
$productosTienda = json_decode(file_get_contents('JSON/tienda.json'), true);

// Verificar si el archivo JSON se cargó correctamente
if ($productosTienda === null) {
    die('Error al cargar los productos de la tienda.');
}

// Obtener el carrito de la sesión (si no existe, crearlo)
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Procesar el carrito y realizar la validación de precios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $carrito = $_SESSION['carrito'];
    $total = 0;
    $errores = [];

    foreach ($carrito as $index => $producto) {
        // Validamos si el precio del producto en el carrito coincide con el precio original
        if (isset($productosTienda['productos'][$producto['id'] - 1])) {
            $productoOriginal = $productosTienda['productos'][$producto['id'] - 1];
            if ($productoOriginal['precio'] != $producto['precio']) {
                $errores[] = "El precio del producto {$producto['nombre']} ha cambiado. El carrito será actualizado.";
                $carrito[$index]['precio'] = $productoOriginal['precio']; // Actualizamos el precio en el carrito
            }
            // Sumar al total
            $total += $carrito[$index]['precio'] * $carrito[$index]['cantidad'];
        } else {
            $errores[] = "El producto con ID {$producto['id']} no se encuentra en la tienda.";
        }
    }

    // Si no hay errores, procesamos el pedido
    if (empty($errores)) {
        // Aquí iría la lógica para realizar el pedido (almacenar en base de datos, procesar el pago, etc.)
        $_SESSION['carrito'] = []; // Limpiar el carrito después del pedido
        header('Location: dashboard.html'); 
        exit;
    } else {
        $_SESSION['errores'] = $errores;
        $_SESSION['carrito'] = $carrito; // Actualizamos el carrito con los precios corregidos
        header('Location: carrito.php'); // Volver a la página del carrito
        exit;
    }
}
?>

