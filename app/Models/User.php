<?php
// app/Models/User.php

require_once __DIR__ . '/../../config/database.php'; // Asegúrate de que la ruta sea correcta

class User {
    private $pdo;
    private $table = 'usuarios';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Busca un usuario por su nombre de usuario.
     * Incluye la condición para usuarios no eliminados lógicamente.
     * @param string $username
     * @return array|false Retorna los datos del usuario como array asociativo o false si no existe/está eliminado.
     */
    public function findByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE nombre_usuario = :username AND deleted_at IS NULL LIMIT 1");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Marca un usuario como "eliminado" lógicamente.
     * @param int $userId
     * @return bool True en éxito, false en error.
     */
    public function softDelete($userId) {
        $stmt = $this->pdo->prepare("UPDATE " . $this->table . " SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $userId]);
    }

    /**
     * Restaura un usuario "eliminado" lógicamente.
     * @param int $userId
     * @return bool True en éxito, false en error.
     */
    public function restore($userId) {
        $stmt = $this->pdo->prepare("UPDATE " . $this->table . " SET deleted_at = NULL WHERE id = :id");
        return $stmt->execute([':id' => $userId]);
    }

    // Puedes añadir más métodos CRUD aquí (crear, actualizar, obtener todos, etc.)
}