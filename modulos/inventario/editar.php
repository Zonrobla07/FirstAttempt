<?php
require_once '../../config/conexion.php';

$mensaje = '';
$conexion = new Conexion();
$conn = $conexion->obtenerConexion();

// 1. Procesar la actualización si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];
    $codigo = $_POST['codigo_barras'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $cantidad = $_POST['cantidad_actual'];
    $stock_min = $_POST['stock_minimo'];
    $stock_max = $_POST['stock_maximo'];

    try {
        $query = "UPDATE productos SET 
                  codigo_barras = :codigo, nombre = :nombre, descripcion = :descripcion, 
                  precio_compra = :precio_c, precio_venta = :precio_v, 
                  cantidad_actual = :cantidad, stock_minimo = :stock_min, stock_maximo = :stock_max 
                  WHERE id_producto = :id";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio_c', $precio_compra);
        $stmt->bindParam(':precio_v', $precio_venta);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':stock_min', $stock_min);
        $stmt->bindParam(':stock_max', $stock_max);
        $stmt->bindParam(':id', $id_producto);

        if ($stmt->execute()) {
            $mensaje = "<div class='alerta-exito'>Producto actualizado correctamente.</div>";
        }
    } catch(PDOException $e) {
        $mensaje = "<div class='alerta-error'>Error al actualizar: " . $e->getMessage() . "</div>";
    }
}

// 2. Obtener los datos actuales del producto para llenar el formulario
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM productos WHERE id_producto = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $producto = $stmt->fetch();

    if (!$producto) {
        die("Producto no encontrado.");
    }
} else {
    header("Location: index.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="header-modulo">
    <h2>Editar Producto</h2>
    <a href="index.php" class="btn btn-secundario">Volver al Catálogo</a>
</div>

<?php echo $mensaje; ?>

<div class="formulario-contenedor">
    <form action="editar.php?id=<?php echo $producto['id_producto']; ?>" method="POST">
        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
        
        <div class="form-grid">
            <div class="form-group">
                <label for="codigo_barras">Código de Barras</label>
                <input type="text" id="codigo_barras" name="codigo_barras" class="form-control" value="<?php echo $producto['codigo_barras']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre del Producto</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $producto['nombre']; ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="3"><?php echo $producto['descripcion']; ?></textarea>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="precio_compra">Precio de Compra ($)</label>
                <input type="number" step="0.01" id="precio_compra" name="precio_compra" class="form-control" value="<?php echo $producto['precio_compra']; ?>" required>
            </div>
            <div class="form-group">
                <label for="precio_venta">Precio de Venta ($)</label>
                <input type="number" step="0.01" id="precio_venta" name="precio_venta" class="form-control" value="<?php echo $producto['precio_venta']; ?>" required>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="cantidad_actual">Stock Actual (Cantidad)</label>
                <input type="number" id="cantidad_actual" name="cantidad_actual" class="form-control" value="<?php echo $producto['cantidad_actual']; ?>" required>
            </div>
            <div class="form-group">
                <label for="stock_minimo">Stock Mínimo (Alerta)</label>
                <input type="number" id="stock_minimo" name="stock_minimo" class="form-control" value="<?php echo $producto['stock_minimo']; ?>" required>
            </div>
            <div class="form-group">
                <label for="stock_maximo">Stock Máximo</label>
                <input type="number" id="stock_maximo" name="stock_maximo" class="form-control" value="<?php echo $producto['stock_maximo']; ?>" required>
            </div>
        </div>

        <div class="form-acciones">
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        </div>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>