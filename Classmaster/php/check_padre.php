<?php 
    // Endpoint: comprueba si el usuario logueado tiene un acudiente vinculado
    // Retorna JSON: { hasPadre: true|false }
    session_start();
    header('Content-Type: application/json');
    require_once 'connection.php';

    $method = $_SERVER['REQUEST_METHOD'];

    if($method === 'GET'){
        $stmt = $conn->prepare("SELECT id_padre from users where id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($id_padre);
        $stmt->store_result();
        if($stmt->fetch()){
            echo json_encode(['hasPadre' => $id_padre !== null]);
        } else {
            echo json_encode(['hasPadre' => false]);
        }
    };
?>