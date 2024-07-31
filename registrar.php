<?php
// Inicializar mensaje de estado
$mensaje = "";

// Verificar si la solicitud es un POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el campo 'nombre' está presente en el POST
    if (isset($_POST['nombre'])) {
        // Obtener y sanitizar el nombre
        $nombre = trim($_POST['nombre']);

        // Validar que el nombre no esté vacío
        if (!empty($nombre)) {
            // Definir la ruta del archivo
            $archivo = 'estudiantes.txt';

            // Sanitizar el contenido para evitar problemas de XSS
            $contenido = htmlspecialchars($nombre) . PHP_EOL;

            try {
                // Verificar si el archivo es escribible o no existe
                if (is_writable($archivo) || !file_exists($archivo)) {
                    // Intentar guardar el nombre en el archivo
                    $resultado = file_put_contents($archivo, $contenido, FILE_APPEND | LOCK_EX);

                    if ($resultado === false) {
                        $mensaje = "Error al guardar el estudiante. Por favor, intenta de nuevo.";
                    } else {
                        $mensaje = "Estudiante registrado: " . htmlspecialchars($nombre);
                    }
                } else {
                    $mensaje = "El archivo no es escribible o no se puede crear.";
                }
            } catch (Exception $e) {
                // Manejo de excepciones en caso de errores de archivo
                $mensaje = "Error al procesar la solicitud: " . $e->getMessage();
            }
        } else {
            $mensaje = "Por favor, ingresa un nombre.";
        }
    } else {
        $mensaje = "Nombre no recibido en la solicitud.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Estudiantes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .mensaje {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Registro de Estudiantes</h1>

    <?php if ($mensaje): ?>
        <p class="<?php echo strpos($mensaje, 'Error') === false ? 'mensaje' : 'error'; ?>"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <label for="nombre">Nombre del Estudiante:</label>
        <input type="text" id="nombre" name="nombre" required>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>