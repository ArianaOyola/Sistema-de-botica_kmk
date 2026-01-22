<?php
// 1. CONEXIÓN: Retrocedemos dos niveles para llegar a la raíz (../../)
include('../../conexion.php'); 
session_start();

// 2. SEGURIDAD: Si no hay sesión iniciada, redirige al login en la raíz
if (!isset($_SESSION['usuario'])) { 
    header("Location: ../../index.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Inversiones K&M-K S.A.C</title>
    <link rel="stylesheet" href="../../css/pedidos.css">
</head>
<body>

    <header class="header-seccion">
        <div class="header-icon">👨‍⚕️</div>
        <h1 class="titulo-pagina">GESTIÓN DE PEDIDOS</h1>
        <div class="header-icon">⚕️</div>
    </header>

    <div class="contenedor-principal">
        <section class="formulario-datos">
            <form action="guardar_pedido.php" method="POST">
                <fieldset>
                    <legend>NUEVA ORDEN DE PEDIDO</legend>
                    <div class="grid-formulario">
                        <div class="grupo-input">
                            <label>👤 Cliente:</label>
                            <select name="id_cliente" required>
                                <option value="">Seleccione un cliente...</option>
                                <?php
                                // Consulta para listar clientes de la base de datos
                                $clientes = $conexion->query("SELECT id, nombres FROM clientes");
                                while($c = $clientes->fetch_assoc()){
                                    echo "<option value='{$c['id']}'>{$c['nombres']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="grupo-input">
                            <label>📅 Fecha de Pedido:</label>
                            <input type="date" name="fecha_pedido" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="grupo-input">
                            <label>🚦 Estado Inicial:</label>
                            <select name="estado">
                                <option value="Pendiente">Pendiente</option>
                                <option value="En Proceso">En Proceso</option>
                                <option value="Completado">Completado</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <div class="botones-ficha">
                    <button type="submit" class="btn-ficha">💾 GUARDAR PEDIDO</button>
                    <a href="../../principal.php" class="btn-ficha btn-volver">↩️ REGRESAR</a>
                </div>
            </form>
        </section>

        <section class="tabla-container">
            <table>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta uniendo las tablas para ver el nombre del cliente
                    $sql = "SELECT p.id_pedido, c.nombres, p.fecha_pedido, p.estado 
                            FROM pedidos p 
                            INNER JOIN clientes c ON p.id_cliente = c.id";
                    $res = $conexion->query($sql);
                    if($res && $res->num_rows > 0){
                        while($f = $res->fetch_assoc()){
                            echo "<tr>
                                    <td>{$f['id_pedido']}</td>
                                    <td>{$f['nombres']}</td>
                                    <td>{$f['fecha_pedido']}</td>
                                    <td><b>{$f['estado']}</b></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No hay pedidos registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>