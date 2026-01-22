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
    <title>Gestión de Medicamentos - K&M-K S.A.C</title>
    <link rel="stylesheet" href="../../css/medicamentos.css">
</head>
<body>

    <header class="header-seccion">
        <div class="header-icon">👨‍⚕️</div>
        <h1 class="titulo-pagina">GESTIÓN DE MEDICAMENTOS</h1>
        <div class="header-icon">⚕️</div>
    </header>

    <div class="contenedor-principal">
        <section class="formulario-datos">
            <form action="guardar_medicamento.php" method="POST">
                <fieldset>
                    <legend>FICHA TÉCNICA DEL MEDICAMENTO</legend>
                    <div class="grid-formulario">
                        <div class="campo">
                            <label>Nombre Comercial:</label>
                            <input type="text" name="nombre_comercial" required placeholder="Ej: Panadol">
                        </div>
                        <div class="campo">
                            <label>Principio Activo:</label>
                            <input type="text" name="principio_act" placeholder="Ej: Paracetamol">
                        </div>
                        <div class="campo">
                            <label>Vía de Administración:</label>
                            <select name="via_admin">
                                <option>Oral</option>
                                <option>Intravenosa</option>
                                <option>Tópica</option>
                                <option>Oftálmica</option>
                            </select>
                        </div>

                        <div class="campo">
                            <label>Acción Farmacológica:</label>
                            <input type="text" name="accion" placeholder="Ej: Analgésico">
                        </div>
                        <div class="campo">
                            <label>Dosis Sugerida:</label>
                            <input type="text" name="dosis">
                        </div>
                        <div class="campo">
                            <label>Requiere Receta:</label>
                            <select name="receta">
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                        <div class="campo span-3">
                            <label>Contraindicaciones / Advertencias:</label>
                            <textarea name="contraindicaciones" rows="2"></textarea>
                        </div>
                    </div>
                </fieldset>

                <div class="acciones-inferiores">
                    <button type="reset" class="btn-accion">+ NUEVO</button>
                    <button type="submit" class="btn-accion">💾 GUARDAR FICHA</button>
                    <a href="../../principal.php" class="btn-accion" style="text-decoration:none;">↩️ REGRESAR</a>
                </div>
            </form>
        </section>

        <section class="tabla-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Principio Activo</th>
                        <th>Acción</th>
                        <th>Vía</th>
                        <th>Receta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta a la tabla medicamentos
                    $sql = "SELECT id, nombre_comercial, principio_activo, accion, via_admin, receta FROM medicamentos";
                    $res = $conexion->query($sql);
                    if($res && $res->num_rows > 0){
                        while($f = $res->fetch_assoc()){
                            echo "<tr>
                                    <td>{$f['nombre_comercial']}</td>
                                    <td>{$f['principio_activo']}</td>
                                    <td>{$f['accion']}</td>
                                    <td>{$f['via_admin']}</td>
                                    <td>{$f['receta']}</td>
                                    <td><button class='btn-tabla'>👁️ Ver</button></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay medicamentos registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>

</body>
</html>