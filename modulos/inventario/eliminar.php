<?php
require_once '../../config/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $conexion = new Conexion();
    $conn = $conexion->obtenerConexion();
    
    try {
        $query = "DELETE FROM productos WHERE id_producto = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } catch(PDOException $e) {
        // Si hay un error (ej. el producto ya está en una factura y la llave foránea impide borrarlo)
        echo "Error al eliminar: " . $e->getMessage();
        exit;
    }
}

// Redireccionamos de vuelta al inventario
header("Location: index.php");
exit;
?>