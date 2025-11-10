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
    // EmailAPI.php
    public function validateEmail(string $email): array
    {
        $check = $this->model->check($email); // {format,domain,disposable,dns,whitelist}

        if (($check['dns'] ?? false) !== true) {
            throw new \RuntimeException('Dominio de email inválido (sin DNS)', 422);
        }
        if (($check['whitelist'] ?? false) !== true) {
            throw new \RuntimeException('Dominio de email no permitido', 422);
        }
        // no bloqueamos por format/alias/disposable en esta política
        return $check;
    }
}

