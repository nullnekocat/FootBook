<?php
class EmailAPIModel
{
    private string $apiUrl = 'https://disify.com/api/email';

    public function check(string $email): array
    {
        $email = trim($email);
        if ($email === '') {
            throw new \RuntimeException('Email vacío.', 422);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('Formato de email inválido.', 422);
        }

        $payload = http_build_query(['email' => $email]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_CONNECTTIMEOUT => 4,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
            ],
            CURLOPT_USERAGENT      => 'FootBook/1.0 (+login-email-check)',
        ]);

        $response = curl_exec($ch);
        $curlErr  = curl_error($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlErr) {
            throw new \RuntimeException('cURL: ' . $curlErr, 502);
        }
        if ($httpCode < 200 || $httpCode >= 300) {
            throw new \RuntimeException("API HTTP $httpCode", $httpCode);
        }

        $data = json_decode($response, true);
        if (!is_array($data)) {
            throw new \RuntimeException('Respuesta no-JSON de la API', 502);
        }

        return $data;
    }
}
