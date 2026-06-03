<?php
// Subimos dos niveles (../../) para llegar a la raíz e incluir la conexión y el header
require_once '../../config/conexion.php';
include '../../includes/header.php';

// Instanciamos la conexión
$conexion = new Conexion();
$conn = $conexion->obtenerConexion();

// Consultamos los productos a la base de datos
$query = "SELECT id_producto, codigo_barras, nombre, precio_venta, cantidad_actual, stock_minimo, stock_maximo FROM productos";
$stmt = $conn->prepare($query);
$stmt->execute();
$productos = $stmt->fetchAll();
?>

<div class="header-modulo">
    <h2>Catálogo de Inventario</h2>
    <a href="nuevo.php" class="btn btn-primary">Nuevo Producto</a>
</div>

<table class="tabla-datos">
    <thead>
        <tr>
            <th>Código</th>
            <th>Producto</th>
            <th>Precio</th>
            <th>Stock Actual</th>
            <th>Mínimo</th>
            <th>Máximo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($productos as $producto): ?>
            <?php 
            // Aquí está la lógica de la alerta
            $claseAlerta = '';
            if ($producto['cantidad_actual'] <= $producto['stock_minimo']) {
                $claseAlerta = 'alerta-stock'; // Si hay poco stock, asignamos esta clase CSS
            }
            ?>
            <tr class="<?php echo $claseAlerta; ?>">
                <td><?php echo $producto['codigo_barras']; ?></td>
                <td><?php echo $producto['nombre']; ?></td>
                <td>$<?php echo number_format($producto['precio_venta'], 2); ?></td>
                
                <td><strong><?php echo $producto['cantidad_actual']; ?></strong></td>
                
                <td><?php echo $producto['stock_minimo']; ?></td>
                <td><?php echo $producto['stock_maximo']; ?></td>
                <td>
    <a href="editar.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-sm btn-secundario">Editar</a>
    <a href="eliminar.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-sm" style="background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5;" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto del inventario?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        
        <?php if(empty($productos)): ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">No hay productos registrados en el inventario.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php 
// Incluimos el footer
include '../../includes/footer.php'; 
?>