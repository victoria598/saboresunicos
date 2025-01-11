<?php

if (isset($_POST['reserva'])) {
    $reservaData = json_decode($_POST['reserva'], true);

    $file = '../database/reservas.json';

    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
    } else {
        $data = [];
    }
    $id = count($data) + 1;
    $reservaData['id'] = $id;
    $data[] = $reservaData;

    if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
