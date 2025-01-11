<?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $file = '../database/reservas.json';
        $reservas = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

        $reserva = null;
        foreach ($reservas as $r) {
            if ($r['id'] == $id) {
                $reserva = $r;
                break;
            }
        }
        echo json_encode($reserva);
    }
?>
