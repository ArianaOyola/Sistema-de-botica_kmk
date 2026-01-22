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
    // Recibimos los campos enviados desde el formulario en clientes.php
    $codigo = $_POST['codigo'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $sexo = $_POST['sexo'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $ruc = $_POST['ruc'];

    // 4. PREPARAR LA SENTENCIA SQL
    // Se utiliza una sentencia preparada por seguridad contra inyecciones SQL
    $sql = "INSERT INTO clientes (codigo, nombres, apellidos, sexo, dni, email, direccion, telefono, ruc) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssss", 
        $codigo, $nombres, $apellidos, $sexo, $dni, 
        $email, $direccion, $telefono, $ruc
    );

    if ($stmt->execute()) {
        // Si el registro es exitoso, muestra una alerta y redirige al listado
        echo "<script>
                alert('¡Cliente registrado con éxito!');
                window.location.href='clientes.php';
              </script>";
    } else {
        // En caso de error, muestra el detalle del error de la base de datos
        echo "Error al registrar: " . $conexion->error;
    }

    $stmt->close();
} else {
    // Si se intenta entrar directamente al archivo, redirige a la página de clientes
    header("Location: clientes.php");
}

$conexion->close();
?>