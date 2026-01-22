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
    // Los nombres en $_POST deben coincidir con los "name" de los inputs en pedidos.php
    $id_cliente = $_POST['id_cliente'];
    $fecha = $_POST['fecha_pedido'];
    $estado = $_POST['estado'];

    // 4. PREPARAR LA SENTENCIA SQL
    // Se utiliza una sentencia preparada por seguridad contra inyecciones SQL
    $sql = "INSERT INTO pedidos (id_cliente, fecha_pedido, estado) VALUES (?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    
    // "iss" indica los tipos de datos: i = entero (id_cliente), s = string (fecha y estado)
    $stmt->bind_param("iss", $id_cliente, $fecha, $estado);

    if ($stmt->execute()) {
        // Si el registro es exitoso, muestra una alerta y redirige al listado de pedidos
        echo "<script>
                alert('¡Pedido registrado con éxito!');
                window.location.href='pedidos.php';
              </script>";
    } else {
        // En caso de error, muestra el detalle del error de la base de datos
        echo "Error al registrar el pedido: " . $conexion->error;
    }

    $stmt->close();
} else {
    // Si se intenta entrar directamente al archivo, redirige a la página de pedidos
    header("Location: pedidos.php");
}

$conexion->close();
?>