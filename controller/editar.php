<?php
    if (isset($_POST['reserva'])) {
        $reservaData = json_decode($_POST['reserva'], true);
        $id = $reservaData['id'];

        $file = '../database/reservas.json';
        $reservas = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

        foreach ($reservas as &$reserva) {
            if ($reserva['id'] == $id) {
                $reserva = $reservaData;
                break;
            }
        }

        if (file_put_contents($file, json_encode($reservas, JSON_PRETTY_PRINT))) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
?>
