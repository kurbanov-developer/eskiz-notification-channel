<?php

namespace KurbanovDeveloper\EskizNotificationChannel;

use GuzzleHttp\Client;

class EskizClient
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('eskiz.api_url'),
            'headers' => [
                'Authorization' => 'Bearer ' . config('eskiz.api_token'),
            ],
        ]);
    }

    // Получить токен
    public function getToken($email, $password)
    {
        $response = $this->client->post('/auth/login', [
            'json' => [
                'email' => $email,
                'password' => $password,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Обновить токен
    public function updateToken($token)
    {
        $response = $this->client->patch('/auth/token', [
            'json' => [
                'token' => $token,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Данные пользователя
    public function getUserData()
    {
        $response = $this->client->get('/auth/me');
        return json_decode($response->getBody(), true);
    }

    // Отправить шаблон
    public function sendTemplate($templateId, $phone, $params)
    {
        $response = $this->client->post('/template/send', [
            'json' => [
                'template_id' => $templateId,
                'mobile_phone' => $phone,
                'params' => $params,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Получить список шаблонов
    public function getTemplates()
    {
        $response = $this->client->get('/template/list');
        return json_decode($response->getBody(), true);
    }

    // Отправить СМС
    public function sendSms($phone, $message)
    {
        $response = $this->client->post('/message/sms/send', [
            'json' => [
                'mobile_phone' => $phone,
                'message' => $message,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Отправить СМС рассылка
    public function sendSmsBulk($phones, $message)
    {
        $response = $this->client->post('/message/sms/send-bulk', [
            'json' => [
                'mobile_phones' => $phones,
                'message' => $message,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Отправить международный СМС
    public function sendInternationalSms($phone, $message)
    {
        $response = $this->client->post('/message/sms/international', [
            'json' => [
                'mobile_phone' => $phone,
                'message' => $message,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Детализация
    public function getDetails($bulkId)
    {
        $response = $this->client->post('/message/details', [
            'json' => [
                'bulk_id' => $bulkId,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Получить СМС по рассылке
    public function getSmsByBulk($bulkId)
    {
        $response = $this->client->post('/message/sms/by-bulk', [
            'json' => [
                'bulk_id' => $bulkId,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Статус рассылки
    public function getBulkStatus($bulkId)
    {
        $response = $this->client->post('/message/status', [
            'json' => [
                'bulk_id' => $bulkId,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Получить список никнеймов
    public function getSenderList()
    {
        $response = $this->client->get('/message/senders');
        return json_decode($response->getBody(), true);
    }

    // Итог отправленных СМС
    public function getSmsSummary()
    {
        $response = $this->client->post('/message/summary');
        return json_decode($response->getBody(), true);
    }

    // Получить баланс
    public function getBalance()
    {
        $response = $this->client->get('/balance');
        return json_decode($response->getBody(), true);
    }

    // Экспортировать в CSV
    public function exportCsv()
    {
        $response = $this->client->post('/message/export');
        return $response->getBody();
    }

    // Итого по месяцам
    public function getMonthlyTotal()
    {
        $response = $this->client->get('/message/monthly-total');
        return json_decode($response->getBody(), true);
    }

    // Итого по компаниям
    public function getCompanyTotal()
    {
        $response = $this->client->post('/message/company-total');
        return json_decode($response->getBody(), true);
    }

    // Системные логи
    public function getSystemLogs()
    {
        $response = $this->client->get('/logs');
        return json_decode($response->getBody(), true);
    }

    // Расходы по датам
    public function getExpensesByDates($startDate, $endDate)
    {
        $response = $this->client->post('/expenses/dates', [
            'json' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Расходы по рассылке
    public function getExpensesByBulk($bulkId)
    {
        $response = $this->client->post('/expenses/by-bulk', [
            'json' => [
                'bulk_id' => $bulkId,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Получить статус по ID
    public function getStatusById($id)
    {
        $response = $this->client->get("/message/status/{$id}");
        return json_decode($response->getBody(), true);
    }
}
