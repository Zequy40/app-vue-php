<?php 


$db_name = 'mysql:host=localhost:8889;dbname=crudClientes;';
$user_name = 'root';
$user_password = 'root';
$db_port = 8889;

try {
    $conn = new PDO($db_name, $user_name, $user_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

?>