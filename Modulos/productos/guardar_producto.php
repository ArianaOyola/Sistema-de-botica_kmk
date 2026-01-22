<?php
// 1. INCLUIR CONEXIÓN: Retrocedemos dos niveles para llegar a la raíz (../../)
include('../../conexion.php');
session_start();

// 2. SEGURIDAD: Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// 3. RECIBIR DATOS DEL FORMULARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Los nombres en $_POST deben coincidir con los "name" de los inputs en productos.php
    $categoria = $_POST['categoria'];
    $laboratorio = $_POST['laboratorio'];
    $descripcion = $_POST['descripcion'];
    $stock = $_POST['stock'];
    $registro_sanitario = $_POST['registro_sanitario'];
    $presentacion = $_POST['presentacion'];
    $precio_compra = $_POST['precio_compra'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $concentracion = $_POST['concentracion'];
    $precio_venta = $_POST['precio_venta'];
    
    // El estado se maneja según si el checkbox fue marcado o no
    $estado = isset($_POST['estado']) ? 'Activo' : 'Inactivo';

    // 4. PREPARAR LA SENTENCIA SQL
    // Se utiliza una sentencia preparada por seguridad contra inyecciones SQL
    $sql = "INSERT INTO productos (descripcion, presentacion, concentracion, categoria, stock, precio_compra, precio_venta, registro_sanitario, laboratorio, fecha_vencimiento, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    
    // "ssssiddssss" indica los tipos de datos: s = string, i = entero, d = doble/decimal
    $stmt->bind_param("ssssiddssss", 
        $descripcion, $presentacion, $concentracion, $categoria, 
        $stock, $precio_compra, $precio_venta, $registro_sanitario, 
        $laboratorio, $fecha_vencimiento, $estado
    );

    if ($stmt->execute()) {
        // Si el registro es exitoso, muestra una alerta y redirige al listado de productos
        echo "<script>
                alert('¡Producto registrado correctamente!');
                window.location.href='productos.php';
              </script>";
    } else {
        // En caso de error, muestra el detalle del error de la base de datos
        echo "Error al registrar el producto: " . $conexion->error;
    }

    $stmt->close();
} else {
    // Si se intenta entrar directamente al archivo, redirige a la página de productos
    header("Location: productos.php");
}

$conexion->close();
?>