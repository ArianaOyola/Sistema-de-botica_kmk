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
    // Los nombres en $_POST deben coincidir con los "name" de los inputs en proveedores.php
    $razon = $_POST['razon_social'];
    $ruc = $_POST['ruc'];
    $dni = $_POST['dni'];
    $dir = $_POST['direccion'];
    $tel = $_POST['telefono'];
    $email = $_POST['email'];
    $banco = $_POST['nombre_banco'];
    $cuenta = $_POST['nro_cuenta'];
    $estado = "Activo"; // Valor por defecto según el diseño del módulo

    // 4. PREPARAR LA SENTENCIA SQL
    $sql = "INSERT INTO proveedores (razon_social, ruc, dni, direccion, telefono, email, nombre_banco, nro_cuenta, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    
    // "sssssssss" indica que todos los parámetros son de tipo string (cadena)
    $stmt->bind_param("sssssssss", $razon, $ruc, $dni, $dir, $tel, $email, $banco, $cuenta, $estado);

    if ($stmt->execute()) {
        // Si el registro es exitoso, muestra una alerta y redirige al listado
        echo "<script>
                alert('¡Proveedor registrado con éxito!');
                window.location.href='proveedores.php';
              </script>";
    } else {
        // En caso de error, muestra el detalle del error de la base de datos
        echo "Error al guardar el proveedor: " . $conexion->error;
    }

    $stmt->close();
} else {
    // Si se intenta entrar directamente al archivo, redirige a la página de proveedores
    header("Location: proveedores.php");
}

$conexion->close();
?>