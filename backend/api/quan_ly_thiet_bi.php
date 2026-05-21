<?php
// backend/api/equipment_crud.php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$db = getDBConnection();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'list') {
    require_once '../models/Equipment.php';
    $equipmentModel = new Equipment();
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $status = $_GET['status'] ?? 'all';
    $room_id = $_GET['room_id'] ?? 'all';
    $search = $_GET['q'] ?? '';
    
    // For now, let's just implement basic pagination. 
    // In a real app, I'd pass filters to the model.
    // I'll update the model to handle these filters.
    $result = $equipmentModel->getEquipmentPaginated($page, $limit, $status, $room_id, $search);
    echo json_encode(['status' => 'success', 'data' => $result['data'], 'pagination' => $result['pagination']]);
    exit;
}


if ($action === 'create') {

    $data = [
        'room_id' => $_POST['room_id'],
        'name' => $_POST['equipment_name'],
        'equipment_code' => $_POST['equipment_code'],
        'category' => $_POST['category'],
        'status' => $_POST['status'] ?? 'good',
        'purchase_date' => date('Y-m-d')
    ];

    $stmt = $db->prepare("INSERT INTO equipment (room_id, name, equipment_code, category, condition_status, purchase_date) 
                          VALUES (:room_id, :name, :equipment_code, :category, :status, :purchase_date)");
    
    if ($stmt->execute($data)) {
        echo json_encode(['status' => 'success', 'message' => 'Created successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }

} elseif ($action === 'update') {
    $id = $_POST['id'];
    $data = [
        'id' => $id,
        'room_id' => $_POST['room_id'],
        'name' => $_POST['equipment_name'],
        'equipment_code' => $_POST['equipment_code'],
        'category' => $_POST['category'],
        'status' => $_POST['status']
    ];

    $stmt = $db->prepare("UPDATE equipment SET 
                          room_id = :room_id, 
                          name = :name, 
                          equipment_code = :equipment_code, 
                          category = :category, 
                          condition_status = :status 
                          WHERE id = :id");
    
    if ($stmt->execute($data)) {
        echo json_encode(['status' => 'success', 'message' => 'Updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }

} elseif ($action === 'delete') {
    $id = $_POST['id'];
    $stmt = $db->prepare("DELETE FROM equipment WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(['status' => 'success', 'message' => 'Deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
    }
}
