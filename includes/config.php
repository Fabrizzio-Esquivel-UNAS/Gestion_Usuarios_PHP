<?php 
// Credenciales de la BD predeterminadas.
$DB_NAME = 'usuarios-db';
$DB_USER = 'invitado';
$DB_PASS = '@v67F4#75';

// Asignar credenciales
if(isset($_SESSION['privileges'])){
    if($_SESSION['privileges']==1){
        $DB_USER = "fabrizzio";
        $DB_PASS = "*8e#0@32V*";
    }else{
        $DB_USER = "usuario";
        $DB_PASS = "28@c3*N54";
    }
}

// Función para establecer una conexión a la BD
function connectDB($host){
    global $DB_NAME;
    global $DB_PASS;
    global $DB_USER;
    try{
        $conn = new PDO("sqlsrv:Server=$host;Database=$DB_NAME", $DB_USER, $DB_PASS);
        return $conn;
    }catch (PDOException $e){
        exit("ERROR: " . $e->getMessage());
    }
}

// Función para sincronizar las BDs
function syncDB() {
    global $connection1;
    global $connection2;
    $sourceDb = null;
    $targetDb = null;
    // No sincronizar si no hay otra BD disponible
    if (!$connection1 || !$connection2) return;
    try {
        // Obtener la ultima sincronización de cada BD
        $query = "SELECT * FROM sincronizaciones WHERE tabla='usuarios'";
        $stmt1 = $connection1->query($query);
        $stmt2 = $connection2->query($query);
        $sync1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $sync2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        // Asignar la BD de origen y de destino
        if ($sync1->tiempo >= $sync2->tiempo){
            $sourceDb = $connection1;
            $targetDb = $connection2;
        }else{
            $msg = "SINCRONIZACIÓN ÉXITOSA: Base de datos local -> Base de datos principal";
            $sourceDb = $connection2;
            $targetDb = $connection1;
        }

        // Obtener datos de la BD de origen
        $query = "SELECT * FROM usuarios";
        $stmt = $sourceDb->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Eliminar datos existentes en la BD de destino
        $deleteQuery = "DELETE FROM usuarios";
        $stmt = $targetDb->prepare($deleteQuery);
        $stmt->execute();

        // Insertar datos del origen en la base de datos de destino
        foreach ($data as $row) {
            $insertQuery = "INSERT INTO usuarios VALUES (:v1, :v2, :v3, :v4, :v5, :v6, :v7)";
            $stmt = $targetDb->prepare($insertQuery);
            $stmt->execute([
                ':v1' => $row['nombres'],
                ':v2' => $row['apellidos'],
                ':v3' => $row['dni'],
                ':v4' => $row['correo'],
                ':v5' => $row['telefono'],
                ':v6' => $row['clave'],
                ':v7' => $row['privilegios'],
            ]);
        }

        // Indicar la ultima sincronización en todas las BDs
        $updateQuery = "UPDATE sincronizaciones SET tiempo=GETDATE() WHERE tabla='usuarios'";
        $stmt = $sourceDb->prepare($updateQuery);
        $stmt->execute();
        $stmt = $targetDb->prepare($updateQuery);
        $stmt->execute();
    } catch (PDOException $e) {
        exit("ERROR DE SINCRONIZACIÓN: " . $e->getMessage());
    }
}

// Conectarse a cada BD
$connection1 = connectDB("gestion-db.database.windows.net");
$connection2 = connectDB("localhost");

// Asignar la BD con la que se va a trabajar
if ($connection1){
    $dbh = $connection1;
}else if($connection2){
    $error = "La base de datos principal no se encuentra disponible.";
    $dbh = $connection2;
}else{
    $error = "No se pudo conectar a ninguna base de datos.";
}

// Sincronizar requiere permisos de Admin
if (isset($_SESSION['privileges']) && $_SESSION['privileges']==1){
    syncDB();
}
?>