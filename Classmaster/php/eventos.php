<?php
session_start();
require_once 'connection.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $year = isset($_GET['year']) ? intval($_GET['year']) : null;
    $month = isset($_GET['month']) ? intval($_GET['month']) : null;
    $for_estudiante = isset($_GET['estudiante_id']) ? intval($_GET['estudiante_id']) : null;
    // Get personal events
    if ($year && $month) {
        $sql = "SELECT id, titulo, descripcion, prioridad, fecha, id_usuario FROM eventos WHERE YEAR(fecha) = ? AND MONTH(fecha) = ? AND id_usuario = ? ORDER BY fecha ASC";
        $stmt = $conn->prepare($sql);
        $user_for_events = $for_estudiante ? $for_estudiante : $_SESSION['user_id'];
        $stmt->bind_param('iii', $year, $month, $user_for_events);
    } else {
        $sql = "SELECT id, titulo, descripcion, prioridad, fecha, id_usuario FROM eventos WHERE id_usuario = ? ORDER BY fecha ASC";
        $stmt = $conn->prepare($sql);
        $user_for_events = $for_estudiante ? $for_estudiante : $_SESSION['user_id'];
        $stmt->bind_param('i', $user_for_events);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $eventos = [];
    while ($row = $result->fetch_assoc()) {
        $eventos[] = $row;
    }
    $stmt->close();

    // Get curso_ids for the user (or for the requested estudiante if provided)
    $curso_ids = [];
    $target_estudiante = $for_estudiante ? $for_estudiante : $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT curso_id FROM curso_estudiante WHERE estudiante_id = ?");
    $stmt->bind_param('i', $target_estudiante);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $curso_ids[] = $row['curso_id'];
    }
    $stmt->close();

    // Get eventos_curso for those cursos
    $eventos_curso = [];
    $eventos_curso_ids = [];
    if (!empty($curso_ids)) {
        $placeholders = implode(',', array_fill(0, count($curso_ids), '?'));
        if ($year && $month) {
            $sql = "SELECT ec.id, ec.curso_id, c.nombre as curso_nombre, ec.titulo, ec.descripcion, ec.prioridad, ec.fecha, ec.creado_por_profesor_id FROM eventos_curso ec JOIN cursos c ON ec.curso_id = c.id WHERE YEAR(ec.fecha) = ? AND MONTH(ec.fecha) = ? AND ec.curso_id IN ($placeholders) ORDER BY ec.fecha ASC";
            $stmt = $conn->prepare($sql);
            $types = str_repeat('i', count($curso_ids));
            $bind_types = 'ii' . $types;
            $params = array_merge([$year, $month], $curso_ids);
        } else {
            $sql = "SELECT ec.id, ec.curso_id, c.nombre as curso_nombre, ec.titulo, ec.descripcion, ec.prioridad, ec.fecha, ec.creado_por_profesor_id FROM eventos_curso ec JOIN cursos c ON ec.curso_id = c.id WHERE ec.curso_id IN ($placeholders) ORDER BY ec.fecha ASC";
            $stmt = $conn->prepare($sql);
            $bind_types = str_repeat('i', count($curso_ids));
            $params = $curso_ids;
        }
        $stmt->bind_param($bind_types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $eventos_curso[] = $row;
            $eventos_curso_ids[$row['id']] = true;
        }
        $stmt->close();
    }

    // If user is a profesor, also get events created by them (even if not in their courses as student)
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'profesor') {
        if ($year && $month) {
            $sql = "SELECT ec.id, ec.curso_id, c.nombre as curso_nombre, ec.titulo, ec.descripcion, ec.prioridad, ec.fecha, ec.creado_por_profesor_id FROM eventos_curso ec JOIN cursos c ON ec.curso_id = c.id WHERE YEAR(ec.fecha) = ? AND MONTH(ec.fecha) = ? AND ec.creado_por_profesor_id = ? ORDER BY ec.fecha ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iis', $year, $month, $_SESSION['user_id']);
        } else {
            $sql = "SELECT ec.id, ec.curso_id, c.nombre as curso_nombre, ec.titulo, ec.descripcion, ec.prioridad, ec.fecha, ec.creado_por_profesor_id FROM eventos_curso ec JOIN cursos c ON ec.curso_id = c.id WHERE ec.creado_por_profesor_id = ? ORDER BY ec.fecha ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $_SESSION['user_id']);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            if (!isset($eventos_curso_ids[$row['id']])) {
                $eventos_curso[] = $row;
            }
        }
        $stmt->close();
    }

    echo json_encode(['success' => true, 'eventos' => $eventos, 'eventos_curso' => $eventos_curso]);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $prioridad = $_POST['prioridad'] ?? 'normal';
        $fecha = $_POST['fecha'] ?? '';
        if (!$titulo) {
            echo json_encode(['success' => false, 'error' => 'Título requerido.']);
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO eventos (titulo, descripcion, prioridad, fecha, id_usuario) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssi', $titulo, $descripcion, $prioridad, $fecha, $_SESSION['user_id']);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $conn->insert_id]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
        exit;
    } else if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            exit;
        }
        // First, try to delete personal event but only if current user is the owner
        $stmt = $conn->prepare("SELECT id_usuario FROM eventos WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($owner_user_id);
        $found = $stmt->fetch();
        $stmt->close();
        if ($found) {
            // Only the owner (id_usuario) can delete their personal event
            if ($owner_user_id == $_SESSION['user_id']) {
                $stmt = $conn->prepare("DELETE FROM eventos WHERE id = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $deleted_personal = $stmt->affected_rows > 0;
                $stmt->close();
                if ($deleted_personal) {
                    echo json_encode(['success' => true]);
                    exit;
                }
            } else {
                // Not the owner: deny (this prevents parents deleting child events)
                echo json_encode(['success' => false, 'error' => 'No tienes permiso para eliminar este evento.']);
                exit;
            }
        }

        // If not a personal event, allow profesor to delete course events they created
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'profesor' && isset($_SESSION['profesor_id'])) {
            $stmt = $conn->prepare("DELETE FROM eventos_curso WHERE id = ? AND creado_por_profesor_id = ?");
            $stmt->bind_param('is', $id, $_SESSION['profesor_id']);
            $stmt->execute();
            $deleted_curso = $stmt->affected_rows > 0;
            $stmt->close();
            if ($deleted_curso) {
                echo json_encode(['success' => true]);
                exit;
            }
        }

        echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el evento o no tienes permisos.']);
        exit;
    } else if ($action === 'create_profesor') {
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $prioridad = $_POST['prioridad'] ?? 'normal';
        $fecha = $_POST['fecha'] ?? '';
        $curso_id = intval($_POST['curso_id'] ?? 0);
        if (!$curso_id) {
            echo json_encode(['success' => false, 'error' => 'Curso ID requerido.']);
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO eventos_curso (curso_id, titulo, descripcion, prioridad, fecha, creado_por_profesor_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssss', $curso_id, $titulo, $descripcion, $prioridad, $fecha, $_SESSION['user_id']);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $conn->insert_id]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
        exit;
    } else if ($action == 'delete_profesor') {
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            exit;
        }
        // Only allow deletion if user is the profesor who created it
        if ($_SESSION['rol'] === 'profesor') {
            $stmt = $conn->prepare("DELETE FROM eventos_curso WHERE id = ? AND creado_por_profesor_id = ?");
            $stmt->bind_param('is', $id, $_SESSION['user_id']);
            $stmt->execute();
            $deleted = $stmt->affected_rows > 0;
            $stmt->close();
            if ($deleted) {
                echo json_encode(['success' => true]);
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el evento o no tienes permisos.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'No tienes permiso para eliminar este evento.']);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'error' => 'Método o acción no soportada.']);
$conn->close();
