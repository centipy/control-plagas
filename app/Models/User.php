<?php
// app/Models/User.php

require_once __DIR__ . '/../../config/database.php';
/**
     * Busca un usuario por su dirección de correo electrónico.
     * Incluye la condición para usuarios no eliminados lógicamente.
     * @param string $email
     * @return array|false Retorna los datos del usuario como array asociativo o false si no existe/está eliminado.
     */
    
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
     * Busca un usuario por ID.
     * @param int $id
     * @return array|false
     */
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     * @param array $data Array asociativo con los datos del usuario (username, password, rol, etc.)
     * @return int|false ID del nuevo usuario insertado o false en caso de error.
     */
    public function create(array $data) {
        // Campos obligatorios con valores predeterminados seguros si no se proporcionan
        $nombre_usuario = $data['nombre_usuario'] ?? null;
        $contrasena_hash = $data['contrasena_hash'] ?? null; // Ya debe venir hasheada
        $rol = $data['rol'] ?? 'asesor'; // Rol por defecto 'asesor' si no se especifica
        $nombre_completo = $data['nombre_completo'] ?? null;
        $email = $data['email'] ?? null;
        $telefono = $data['telefono'] ?? null;

        // Validación básica: nombre_usuario, contrasena_hash, rol y nombre_completo son imprescindibles
        if (!$nombre_usuario || !$contrasena_hash || !$rol || !$nombre_completo) {
            error_log("Error en User::create: Datos incompletos para crear usuario.");
            return false;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO " . $this->table . " 
            (nombre_usuario, contrasena, rol, nombre_completo, email, telefono) 
            VALUES (:nombre_usuario, :contrasena, :rol, :nombre_completo, :email, :telefono)
        ");

        $success = $stmt->execute([
            ':nombre_usuario' => $nombre_usuario,
            ':contrasena' => $contrasena_hash,
            ':rol' => $rol,
            ':nombre_completo' => $nombre_completo,
            ':email' => $email,
            ':telefono' => $telefono
        ]);

        return $success ? $this->pdo->lastInsertId() : false;
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

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE email = :email AND deleted_at IS NULL LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } 
}