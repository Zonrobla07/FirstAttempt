<?php
class Conexion {
    // Credenciales de acceso por defecto en XAMPP
    private $host = "localhost";
    private $db_name = "sistema_control_inventario";
    private $username = "root";
    private $password = ""; 
    public $conn;

    // Método para establecer la conexión
    public function obtenerConexion() {
        $this->conn = null;

        try {
            // Se define el string de conexión (DSN)
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            // Instancia de PDO
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Configuración de atributos para el manejo de errores y seguridad
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch(PDOException $exception) {
            // Mensaje en caso de que el motor de base de datos esté apagado o haya un error
            echo "Error de conexión con la base de datos: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>