<?php
// Ajusta la ruta según tu estructura real
require_once __DIR__ . '/../models/EmailAPIModel.php';

class EmailAPI
{
    private EmailAPIModel $model;

    public function __construct()
    {
        $this->model = new EmailAPIModel();
    }

    public function validateEmail(string $email): array
    {
        $check = $this->model->check($email); 

        if (isset($check['format']) && $check['format'] === false) {
            throw new \RuntimeException('Formato de email inválido', 422);
        }
        if (isset($check['disposable']) && $check['disposable'] === true) {
            throw new \RuntimeException('No se permite email desechable', 422);
        }
        if (isset($check['dns']) && $check['dns'] === false) {
            throw new \RuntimeException('Dominio de email inválido (sin DNS)', 422);
        }
        if (empty($check['domain'])) {
            throw new \RuntimeException('No se pudo determinar el dominio del email', 422);
        }
        // alias puede ser true y NO es error
        return $check;
    }
}

