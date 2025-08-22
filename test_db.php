<?php
// Configuraciones a probar
$configs = [
    [
        'host' => 'localhost',
        'user' => 'uyyoj37axeqaq',
        'pass' => 'c1J11#c[3rqc]',
        'name' => 'dbzyigrezaix7n'
    ],
    [
        'host' => '127.0.0.1',
        'user' => 'uyyoj37axeqaq',
        'pass' => 'c1J11#c[3rqc]',
        'name' => 'dbzyigrezaix7n'
    ],
    [
        'host' => 'it37.siteground.eu',
        'user' => 'uyyoj37axeqaq',
        'pass' => 'c1J11#c[3rqc]',
        'name' => 'dbzyigrezaix7n'
    ],
    // Intentar sin especificar el host (usar socket por defecto)
    [
        'host' => '',
        'user' => 'uyyoj37axeqaq',
        'pass' => 'c1J11#c[3rqc]',
        'name' => 'dbzyigrezaix7n'
    ]
];

foreach ($configs as $i => $config) {
    echo "Prueba #" . ($i + 1) . ":\n";
    echo "  Host: " . ($config['host'] ? $config['host'] : '(empty)') . "\n";
    echo "  User: " . $config['user'] . "\n";
    echo "  DB: " . $config['name'] . "\n";
    
    try {
        $dsn = 'mysql:';
        if ($config['host']) {
            $dsn .= 'host=' . $config['host'] . ';';
        }
        $dsn .= 'dbname=' . $config['name'];
        
        $pdo = new PDO($dsn, $config['user'], $config['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $result = $pdo->query("SELECT 1 as test")->fetch();
        echo "  Resultado: CONEXIÓN EXITOSA\n\n";
    } catch (PDOException $e) {
        echo "  Error: " . $e->getMessage() . "\n\n";
    }
}
