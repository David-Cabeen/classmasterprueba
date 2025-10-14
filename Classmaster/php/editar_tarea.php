<?php
    session_start();
    ini_set('display_errors', '0');
    error_reporting(0);
    ob_start();
    header('Content-Type: application/json');
    require_once 'connection.php';

    function send_json($payload) {
        if (ob_get_length() !== false) {
            while (ob_get_level() > 0) ob_end_clean();
        }
        echo json_encode($payload);
        exit;
    }

    if ($_SESSION['rol'] !== 'profesor') {
        echo json_encode(['success' => false, 'error' => 'No autorizado']);
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'editar') {
        $tarea_id = $_POST['id'] ?? '';
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $porcentaje = $_POST['porcentaje'] ?? '';

        if (!$tarea_id || $titulo === '' || $porcentaje === '') {
            send_json(['success' => false, 'error' => 'Faltan datos']);
        }

        // validar porcentaje
        $porcentaje_num = floatval($porcentaje);
        if ($porcentaje_num < 0 || $porcentaje_num > 100) {
            send_json(['success' => false, 'error' => 'Porcentaje inválido']);
        }

        // obtener curso_id y periodo de la tarea actual
        $curso_id = null; $periodo_actual = null;
        $s0 = $conn->prepare('SELECT curso_id, periodo FROM tareas WHERE id = ?');
        if ($s0) {
            $s0->bind_param('i', $tarea_id);
            $s0->execute();
            $s0->bind_result($curso_id, $periodo_actual);
            if (!$s0->fetch()) {
                $s0->close();
                send_json(['success' => false, 'error' => 'Tarea no encontrada']);
            }
            $s0->close();
        } else {
            send_json(['success' => false, 'error' => 'Error al consultar la tarea: ' . $conn->error]);
        }

        // verificar la suma de porcentajes existentes para este curso y periodo, excluyendo la tarea actual
        $stmt_check = $conn->prepare('SELECT IFNULL(SUM(porcentaje),0) AS total FROM tareas WHERE curso_id = ? AND periodo = ? AND id <> ?');
        if ($stmt_check) {
            $stmt_check->bind_param('isi', $curso_id, $periodo_actual, $tarea_id);
            $stmt_check->execute();
            $stmt_check->bind_result($existing_total);
            $stmt_check->fetch();
            $stmt_check->close();
            if (floatval($existing_total) + $porcentaje_num > 100.0001) {
                send_json(['success' => false, 'error' => 'La suma de porcentajes excede 100% (actual sin esta tarea: ' . $existing_total . '%)']);
            }
        } else {
            send_json(['success' => false, 'error' => 'Error verificando porcentajes: ' . $conn->error]);
        }

        $stmt = $conn->prepare("UPDATE tareas SET titulo = ?, descripcion = ?, porcentaje = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("ssdi", $titulo, $descripcion, $porcentaje_num, $tarea_id);
            if ($stmt->execute()) {
                send_json(['success' => true, 'message' => 'Tarea actualizada correctamente']);
            } else {
                $err = $stmt->error;
                $stmt->close();
                send_json(['success' => false, 'error' => $err]);
            }
            $stmt->close();
        } else {
            send_json(['success' => false, 'error' => 'Error preparando la actualización: ' . $conn->error]);
        }
    } else if($action === 'eliminar') {
        $tarea_id = $_POST['id'] ?? '';

        if ($tarea_id) {
            $stmt = $conn->prepare("DELETE FROM tareas WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $tarea_id);
                if ($stmt->execute()) {
                    send_json(['success' => true, 'message' => 'Tarea eliminada correctamente']);
                } else {
                    send_json(['success' => false, 'error' => $stmt->error]);
                }
                $stmt->close();
            } else {
                send_json(['success' => false, 'error' => 'Error preparando la eliminación: ' . $conn->error]);
            }
        } else {
            send_json(['success' => false, 'error' => 'Faltan datos']);
        };
    } else {
        send_json(['success' => false, 'error' => 'Acción no válida']);
    };

    $conn->close();

?>