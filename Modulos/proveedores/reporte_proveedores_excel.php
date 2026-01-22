<?php
// botica_kmk/Modulos/proveedores/reporte_proveedores_excel.php

include('../../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Reporte_Proveedores_" . date("Y-m-d") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

$sql = "SELECT * FROM proveedores";
$res = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        table { width: 100%; border-collapse: collapse; font-family: Arial; }
        th { background-color: #1B365D; color: white; border: 1px solid #000; padding: 10px; }
        td { border: 1px solid #000; padding: 8px; text-align: center; }
        .titulo { background-color: #2A8B8B; color: white; font-size: 18px; font-weight: bold; text-align: center; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr><td colspan="9" class="titulo">LISTADO DE PROVEEDORES - K&M-K S.A.C</td></tr>
            <tr>
                <th>ID</th>
                <th>RAZÓN SOCIAL</th>
                <th>RUC</th>
                <th>DNI</th>
                <th>DIRECCIÓN</th>
                <th>TELÉFONO</th>
                <th>EMAIL</th>
                <th>BANCO</th>
                <th>CUENTA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($res && $res->num_rows > 0) {
                while($f = $res->fetch_assoc()){
                    echo "<tr>
                            <td>{$f['id_proveedor']}</td>
                            <td>{$f['razon_social']}</td>
                            <td>'{$f['ruc']}</td> <td>'{$f['dni']}</td>
                            <td>{$f['direccion']}</td>
                            <td>{$f['telefono']}</td>
                            <td>{$f['email']}</td>
                            <td>{$f['nombre_banco']}</td>
                            <td>'{$f['nro_cuenta']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No hay proveedores registrados</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php $conexion->close(); ?>