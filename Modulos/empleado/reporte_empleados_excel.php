<?php
include('../../conexion.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Reporte_Personal.xls");
header("Pragma: no-cache");
header("Expires: 0");

$sql = "SELECT codigo, dni, nombres, apellidos, sexo, telefono, especialidad FROM empleados";
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
        .titulo { background-color: #2A8B8B; color: white; font-weight: bold; text-align: center; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr><td colspan="7" class="titulo">REPORTE DE PERSONAL - K&M-K S.A.C</td></tr>
            <tr>
                <th>CÓDIGO</th>
                <th>DNI</th>
                <th>NOMBRES</th>
                <th>APELLIDOS</th>
                <th>SEXO</th>
                <th>TELÉFONO</th>
                <th>ESPECIALIDAD</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($res && $res->num_rows > 0) {
                while($f = $res->fetch_assoc()){
                    echo "<tr>
                            <td>{$f['codigo']}</td>
                            <td>'{$f['dni']}</td>
                            <td>{$f['nombres']}</td>
                            <td>{$f['apellidos']}</td>
                            <td>{$f['sexo']}</td>
                            <td>{$f['telefono']}</td>
                            <td>{$f['especialidad']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hay empleados registrados</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php $conexion->close(); ?>