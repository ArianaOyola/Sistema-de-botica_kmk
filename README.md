# 🏥 Sistema de Gestión - Botica K&M-K

Este es un sistema web integral diseñado para la farmacia **"Inversiones K&M-K S.A.C"**. Permite gestionar el flujo completo de ventas, controlar el inventario de medicamentos, administrar la caja diaria y supervisar al personal.

## 🚀 Características principales

* **Punto de Venta (POS):** Interfaz ágil para realizar ventas, búsqueda de productos en tiempo real, cálculo automático de importes (Subtotal/IGV) y actualización inmediata del stock.
* **Control de Inventario:** Visualización del stock actual con alertas visuales (semáforo) para productos con **Stock Bajo** o próximos a vencer.
* **Gestión de Caja:** Módulo para la apertura y cierre de caja diaria, calculando automáticamente los ingresos por ventas del día.
* **Administración de Personal:** Registro de empleados con asignación de roles específicos (Administrador, Farmacéutico, Técnico, Cajero) para controlar el acceso al sistema.
* **Reportes:** Función integrada para exportar listados de inventario, proveedores y devoluciones directamente a formato **Excel** para su análisis administrativo.
* **Seguridad:** Sistema de autenticación de usuarios y recuperación de contraseñas.

## 🛠️ Tecnologías Utilizadas

* **Backend:** PHP nativo utilizando la extensión `mysqli` para conexiones eficientes a la base de datos.
* **Frontend:** HTML5, CSS3 (Diseño modular por componentes) y JavaScript (Vanilla).
* **Base de Datos:** MySQL.
* **Estilos:** CSS personalizado con variables CSS para mantener la identidad visual de la marca (colores corporativos #2A8B8B y #1B365D).

## 📂 Estructura del Proyecto

* `/css`: Contiene las hojas de estilo modulares para cada sección (ventas, caja, perfil, etc.).
* `/img`: Recursos gráficos y logotipos de la empresa.
* `/Modulos`: Lógica de negocio dividida por áreas funcionales:
    * `/caja`: Arqueo y cierre de caja.
    * `/ventas`: Proceso de venta y carrito de compras.
    * `/inventario`: Control de stock y vencimientos.
    * `/cliente`, `/empleado`, `/proveedores`: Gestión de entidades.
* `conexion.php`: Archivo central de configuración de la base de datos (excluido del repositorio público por seguridad).

---
<p align="center">
  Desarrollado con ❤️ por <b>Ariana Oyola</b> para la gestión eficiente de Inversiones K&M-K.
</p>