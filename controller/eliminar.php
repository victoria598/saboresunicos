<?php
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $file = '../database/reservas.json';
        $reservas = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

        $reservas = array_filter($reservas, function($reserva) use ($id) {
            return $reserva['id'] != $id;
        });

        $reservas = array_values($reservas);

        if (file_put_contents($file, json_encode($reservas, JSON_PRETTY_PRINT))) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
?>
