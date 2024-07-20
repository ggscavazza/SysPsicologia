<?php

namespace App\Controllers;

use App\Services\FichaService;

class FichaController
{
    private $service;

    public function __construct()
    {
        $this->service = new FichaService();
    }

    public function index()
    {
        $fichas = $this->service->getAll();
        header('Content-Type: application/json');
        echo json_encode($fichas);
    }

    public function show($id)
    {
        $ficha = $this->service->getById($id);
        header('Content-Type: application/json');
        echo json_encode($ficha);
    }

    public function searchByName($name)
    {
        $fichas = $this->service->getByName($name);
        header('Content-Type: application/json');
        echo json_encode($fichas);
    }

    public function searchByToken($token)
    {
        $ficha = $this->service->getByToken($token);
        header('Content-Type: application/json');
        echo json_encode($ficha);
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        try {
            $id = $this->service->create($data);
            header('Content-Type: application/json');
            echo json_encode(['id' => $id]);
        } catch (\Exception $e) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        try {
            $this->service->update($id, $data);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Ficha atualizada com sucesso']);
        } catch (\Exception $e) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            $this->service->delete($id);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Ficha deletada com sucesso']);
        } catch (\Exception $e) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
