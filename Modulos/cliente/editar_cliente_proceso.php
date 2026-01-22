<?php
// 1. INCLUIR CONEXIÓN: Retrocedemos dos niveles para llegar a la raíz
include('../../conexion.php');
session_start();

// 2. SEGURIDAD: Verificar que el usuario tenga sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// 3. RECIBIR DATOS DEL FORMULARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Es fundamental recibir el ID para saber qué cliente actualizar
    $id = $_POST['id'];
    $codigo = $_POST['codigo'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $sexo = $_POST['sexo'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $ruc = $_POST['ruc'];

    // Validar que el ID no esté vacío antes de procesar
    if (!empty($id)) {
        // 4. PREPARAR LA SENTENCIA DE ACTUALIZACIÓN
        $sql = "UPDATE clientes SET 
                codigo = ?, 
                nombres = ?, 
                apellidos = ?, 
                sexo = ?, 
                dni = ?, 
                email = ?, 
                direccion = ?, 
                telefono = ?, 
                ruc = ? 
                WHERE id = ?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssssssssi", 
            $codigo, $nombres, $apellidos, $sexo, $dni, 
            $email, $direccion, $telefono, $ruc, $id
        );

        if ($stmt->execute()) {
            // Si tiene éxito, muestra alerta y regresa al listado
            echo "<script>
                    alert('¡Cliente actualizado con éxito!'); 
                    window.location.href='clientes.php';
                  </script>";
        } else {
            echo "Error al actualizar: " . $conexion->error;
        }
        $stmt->close();
    } else {
        echo "<script>
                alert('Error: No se encontró el ID del cliente.'); 
                window.location.href='clientes.php';
              </script>";
    }
} else {
    // Si se intenta entrar directamente, redirige a clientes.php
    header("Location: clientes.php");
}

$conexion->close();
?>