<?php 

    session_start();
    header('Content-Type: application/json');
    require_once 'connection.php';

    $method = $_SERVER['REQUEST_METHOD'];

    if($method === 'GET'){

        $stmt = $conn->prepare("SELECT nombre, apellido, id FROM users WHERE id_padre = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $estudiantes = array();
        while ($row = $result->fetch_assoc()) {
            $estudiantes[] = array(
                'id' => $row['id'],
                'nombre' => htmlspecialchars($row['nombre']),
                'apellido' => htmlspecialchars($row['apellido'])
            );
        }
        $stmt->close();
        echo json_encode($estudiantes);

    } else if($method === 'POST'){

        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'] ?? '';

        if(empty($email)){
            echo json_encode(['success' => false, 'error' => 'Ingrese un correo']);
            exit;
        };

        $stmt = $conn->prepare("SELECT id from acudientes where email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id_padre);
        $stmt->store_result();
        if($stmt->fetch()){
            $stmt->close();
            $stmt = $conn->prepare("UPDATE users SET id_padre = ? WHERE id = ?");
            $stmt->bind_param("ii", $id_padre, $_SESSION['user_id']);
            if($stmt->execute()){
                echo json_encode(['success' => true, 'message' => 'Acudiente vinculado correctamente']);
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Acudiente no encontrado']);
        }
    }
    $conn->close();

?>