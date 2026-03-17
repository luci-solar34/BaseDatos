<?php

class Album {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getLatestAlbums(){

        $query = "SELECT id_album,
                         nombre_album,
                         portada_album
                  FROM Albumes
                  ORDER BY fecha_lanzamiento DESC
                  LIMIT 6";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Para la página de lanzamientos (más resultados)
    public function getRecentAlbums(){

        $query = "SELECT id_album,
                         nombre_album,
                         portada_album
                  FROM Albumes
                  ORDER BY fecha_lanzamiento DESC
                  LIMIT 20";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id){

    $query = "SELECT * FROM Albumes WHERE id_album = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id",$id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function getByArtist($artist_id){

    // La tabla Albumes no tiene columna id_artista, usamos id_usuario como referencia al creador/artista.
    $query = "SELECT * FROM Albumes WHERE id_usuario = :artist ORDER BY fecha_lanzamiento DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":artist",$artist_id);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function create($data){

    // Construir el INSERT dinámicamente en caso de que la tabla tenga columnas adicionales
$columns = ['nombre_album', 'portada_album', 'id_usuario'];
        $placeholders = [':nombre', ':portada', ':usuario'];

    if(!empty($data['descripcion']) && $this->hasColumn('Albumes', 'descripcion_album')){
        $columns[] = 'descripcion_album';
        $placeholders[] = ':descripcion';
    }

    $query = "INSERT INTO Albumes (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":nombre", $data['nombre']);
    $stmt->bindParam(":portada", $data['portada']);
    $stmt->bindParam(":usuario", $data['artista']);

    if(!empty($data['descripcion']) && $this->hasColumn('Albumes', 'descripcion_album')){
        $stmt->bindParam(":descripcion", $data['descripcion']);
    }

    if($stmt->execute()){
        return $this->conn->lastInsertId();
    }

    return false;
}

    private function hasColumn($table, $column){
        try {
            $query = "SHOW COLUMNS FROM {$table} LIKE :column";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':column', $column);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

}