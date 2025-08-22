<?php
$host = 'localhost';
$user = 'uyyoj37axeqaq';
$pass = 'c1J11#c[3rqc]';
$db = 'dbzyigrezaix7n';

// Intentar conectar sin especificar la base de datos
$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    echo "Error de conexión (sin base de datos): " . $mysqli->connect_error . "\n";
} else {
    echo "Conexión exitosa (sin base de datos)\n";
    $mysqli->close();
}

// Intentar conectar especificando la base de datos
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    echo "Error de conexión (con base de datos): " . $mysqli->connect_error . "\n";
} else {
    echo "Conexión exitosa (con base de datos)\n";
    $mysqli->close();
}
