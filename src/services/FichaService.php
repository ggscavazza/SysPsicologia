<?php

namespace App\Services;

use App\Database\Connection;
use App\Models\Ficha;
use App\Utils\CPFValidator;
use PDO;

class FichaService
{
    private $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM ficha_pacientes");
        return $stmt->fetchAll(PDO::FETCH_CLASS, Ficha::class);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ficha_pacientes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(Ficha::class);
    }

    public function getByName($name)
    {
        $stmt = $this->db->prepare("SELECT * FROM ficha_pacientes WHERE nome LIKE :name");
        $name = "%$name%";
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Ficha::class);
    }

    public function getByToken($token)
    {
        $stmt = $this->db->prepare("SELECT * FROM ficha_pacientes WHERE token = :token");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject(Ficha::class);
    }

    public function create($data)
    {
        if (!CPFValidator::validate($data['cpf'])) {
            throw new \Exception("CPF inválido");
        }

        $stmt = $this->db->prepare("
            INSERT INTO ficha_pacientes (nome, cpf, nascimento, idade, sexo, profissao, cep, logradouro, bairro, cidade, estado, num_residencia, complemento_residencia)
            VALUES (:nome, :cpf, :nascimento, :idade, :sexo, :profissao, :cep, :logradouro, :bairro, :cidade, :estado, :num_residencia, :complemento_residencia)
        ");
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        if (!CPFValidator::validate($data['cpf'])) {
            throw new \Exception("CPF inválido");
        }

        $data['id'] = $id;
        $stmt = $this->db->prepare("
            UPDATE ficha_pacientes
            SET nome = :nome, cpf = :cpf, nascimento = :nascimento, idade = :idade, sexo = :sexo, profissao = :profissao, cep = :cep, logradouro = :logradouro, bairro = :bairro, cidade = :cidade, estado = :estado, num_residencia = :num_residencia, complemento_residencia = :complemento_residencia
            WHERE id = :id
        ");
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ficha_pacientes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
