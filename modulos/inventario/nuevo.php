<?php
require_once '../../config/conexion.php';

$mensaje = ''; // Variable para mostrar alertas de éxito o error

// Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexion = new Conexion();
    $conn = $conexion->obtenerConexion();

    // Capturamos los datos del formulario
    $codigo = $_POST['codigo_barras'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_compra = $_POST['precio_compra'];
    $precio_venta = $_POST['precio_venta'];
    $cantidad = $_POST['cantidad_actual'];
    $stock_min = $_POST['stock_minimo'];
    $stock_max = $_POST['stock_maximo'];

    try {
        // Preparamos la consulta SQL
        $query = "INSERT INTO productos (codigo_barras, nombre, descripcion, precio_compra, precio_venta, cantidad_actual, stock_minimo, stock_maximo) 
                  VALUES (:codigo, :nombre, :descripcion, :precio_c, :precio_v, :cantidad, :stock_min, :stock_max)";
        
        $stmt = $conn->prepare($query);
        
        // Asignamos los valores de forma segura
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio_c', $precio_compra);
        $stmt->bindParam(':precio_v', $precio_venta);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':stock_min', $stock_min);
        $stmt->bindParam(':stock_max', $stock_max);

        if ($stmt->execute()) {
            $mensaje = "<div class='alerta-exito'>Producto registrado correctamente.</div>";
        }
    } catch(PDOException $e) {
        $mensaje = "<div class='alerta-error'>Error al registrar: " . $e->getMessage() . "</div>";
    }
}

include '../../includes/header.php';
?>

<div class="header-modulo">
    <h2>Registrar Nuevo Producto</h2>
    <a href="index.php" class="btn btn-secundario">Volver al Catálogo</a>
</div>

<?php echo $mensaje; ?>

<div class="formulario-contenedor">
    <form action="nuevo.php" method="POST">
        
        <div class="form-grid">
            <div class="form-group">
                <label for="codigo_barras">Código de Barras</label>
                <input type="text" id="codigo_barras" name="codigo_barras" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="nombre">Nombre del Producto</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="precio_compra">Precio de Compra ($)</label>
                <input type="number" step="0.01" id="precio_compra" name="precio_compra" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="precio_venta">Precio de Venta ($)</label>
                <input type="number" step="0.01" id="precio_venta" name="precio_venta" class="form-control" required>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="cantidad_actual">Stock Inicial (Cantidad)</label>
                <input type="number" id="cantidad_actual" name="cantidad_actual" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="stock_minimo">Stock Mínimo (Alerta)</label>
                <input type="number" id="stock_minimo" name="stock_minimo" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="stock_maximo">Stock Máximo</label>
                <input type="number" id="stock_maximo" name="stock_maximo" class="form-control" required>
            </div>
        </div>

        <div class="form-acciones">
            <button type="submit" class="btn btn-primary">Guardar Producto</button>
        </div>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>