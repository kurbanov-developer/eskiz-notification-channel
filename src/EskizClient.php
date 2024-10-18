<?php

namespace KurbanovDeveloper\EskizNotificationChannel;

use GuzzleHttp\Client;

class EskizClient
{
    protected $client;

    public function __construct()
    {
        $this->token = $this->getToken();

        $this->client = new Client([
            'base_uri' => config('eskiz.api_url'),
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ],
        ]);
    }

    // Получить токен
    public function getToken()
    {
        $tempClient = new Client([
            'base_uri' => config('eskiz.api_url'),
        ]);

        $response = $tempClient->post('/api/auth/login', [
            'json' => [
                'email' => config('eskiz.email'),
                'password' => config('eskiz.password'),
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['data']['token'])) {
            return $data['data']['token'];
        }

        throw new \Exception('Unable to retrieve token. Please check your credentials.');
    }

    // Обновить токен
    public function updateToken($token)
    {
        $response = $this->client->patch('/api/auth/refresh', [
            'json' => [
                'token' => $token,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Данные пользователя
    public function getUserData()
    {
        $response = $this->client->get('/api/auth/user');
        return json_decode($response->getBody(), true);
    }

    // Отправить шаблон
    public function sendTemplate($templateId, $phone, $params)
    {
        $response = $this->client->post('/api/user/template', [
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
        $response = $this->client->get('/api/user/templates');
        return json_decode($response->getBody(), true);
    }

    // Отправить СМС
    public function sendSms($phone, $message)
    {
        $response = $this->client->post('/api/message/sms/send', [
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
        $response = $this->client->post('/api/message/sms/send-batch', [
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
        $response = $this->client->post('/api/message/sms/send-global', [
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
        $response = $this->client->post('/api/message/sms/get-user-messages?status', [
            'json' => [
                'bulk_id' => $bulkId,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Получить СМС по рассылке
    public function getSmsByBulk($bulkId)
    {
        $response = $this->client->post('/api/message/sms/get-user-messages-by-dispatch?status', [
            'json' => [
                'bulk_id' => $bulkId,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Статус рассылки
    public function getBulkStatus($bulkId)
    {
        $response = $this->client->post('/api/message/sms/get-dispatch-status', [
            'json' => [
                'bulk_id' => $bulkId,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Получить список никнеймов
    public function getSenderList()
    {
        $response = $this->client->get('/api/nick/me');
        return json_decode($response->getBody(), true);
    }

    // Итог отправленных СМС
    public function getSmsSummary()
    {
        $response = $this->client->post('/api/user/totals');
        return json_decode($response->getBody(), true);
    }

    // Получить баланс
    public function getBalance()
    {
        $response = $this->client->get('/api/user/get-limit');
        return json_decode($response->getBody(), true);
    }

    // Экспортировать в CSV
    public function exportCsv()
    {
        $response = $this->client->post('/api/message/export?status=all');
        return $response->getBody();
    }

    // Итого по месяцам
    public function getMonthlyTotal()
    {
        $response = $this->client->get('/api/report/total-by-month?year=2024');
        return json_decode($response->getBody(), true);
    }

    // Итого по компаниям
    public function getCompanyTotal()
    {
        $response = $this->client->post('/api/report/total-by-smsc');
        return json_decode($response->getBody(), true);
    }

    // Системные логи
    public function getSystemLogs()
    {
        $response = $this->client->get('/api/logs/sms/:id');
        return json_decode($response->getBody(), true);
    }

    // Расходы по датам
    public function getExpensesByDates($startDate, $endDate)
    {
        $response = $this->client->post('/api/report/total-by-range?status', [
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
        $response = $this->client->post('/api/report/total-by-dispatch?status', [
            'json' => [
                'bulk_id' => $bulkId,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Получить статус по ID
    public function getStatusById($id)
    {
        $response = $this->client->get("api/message/sms/status_by_id/{$id}");
        return json_decode($response->getBody(), true);
    }
}
