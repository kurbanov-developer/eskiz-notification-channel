<?php

namespace KurbanovDeveloper\EskizNotificationChannel;

use GuzzleHttp\Client;

class EskizClient
{
    protected $client;
    protected $email;
    protected $secret;

    public function __construct($email, $secret)
    {
        $this->email = $email;
        $this->secret = $secret;

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
                'email' => $this->email,
                'password' => $this->secret,
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['data']['token'])) {
            return $data['data']['token'];
        }

        throw new \Exception('Unable to retrieve token. Please check your credentials.');
    }

    // Обновить токен
    public function updateToken()
    {
        $response = $this->client->patch('/api/auth/refresh');
        return json_decode($response->getBody(), true);
    }

    // Данные пользователя
    public function getUserData()
    {
        $response = $this->client->get('/api/auth/user');
        return json_decode($response->getBody(), true);
    }

    // Отправить шаблон
    public function sendTemplate($templateId)
    {
        $response = $this->client->post('/api/user/template', [
            'json' => [
                'template_id' => $templateId,
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
    
    /**
     * "messages": [{"user_sms_id":"sms1","to": 998991234567, "text": "eto test"}, {"user_sms_id":"sms2","to": 998991234567, "text": "eto test 2"}],
    */
    public function sendSmsBulk(array $messages, $dispatch_id)
    {
        $response = $this->client->post('/api/message/sms/send-batch', [
            'json' => [
                'messages' => $messages,
                'dispatch_id' => $dispatch_id,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Отправить международный СМС
    public function sendInternationalSms($phone, $message, $country_code, $unicode = '1')
    {
        $response = $this->client->post('/api/message/sms/send-global', [
            'json' => [
                'mobile_phone' => $phone,
                'message' => $message,
                'country_code' => $country_code,
                'unicode' => $unicode,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Детализация
    /***
     * $start_date = '2023-11-01 00:00' 
     * С %Y-%m-%d %H:%M
     */
    public function getDetails($start_date, $to_date, $page_size = 20, $count = 1, $status = '', $is_ad = '')
    {
        $response = $this->client->post('/api/message/sms/get-user-messages?status=' . $status, [
            'json' => [
                'start_date' => $start_date,
                'to_date' => $to_date,
                'page_size' => $page_size,
                'count' => $count,
                'is_ad' => $is_ad,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Получить СМС по рассылке
    public function getSmsByBulk($dispatch_id, $count = 1, $is_ad = '',  $status = '')
    {
        $response = $this->client->post('/api/message/sms/get-user-messages-by-dispatch?status=' . $status, [
            'json' => [
                'dispatch_id' => $dispatch_id,
                'count' => $count,
                'is_ad' => $is_ad,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Статус рассылки
    public function getBulkStatus($dispatch_id, $is_global = 0)
    {
        $response = $this->client->post('/api/message/sms/get-dispatch-status', [
            'json' => [
                'dispatch_id' => $dispatch_id,
                'is_global' => $is_global,
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
    public function getSmsSummary($year, $month)
    {
        $response = $this->client->post('/api/user/totals', [
            'json' => [
                'year' => $year,
                'month' => $month,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Получить баланс
    public function getBalance()
    {
        $response = $this->client->get('/api/user/get-limit');
        return json_decode($response->getBody(), true);
    }

    // Экспортировать в CSV
    public function exportCsv($year, $month)
    {
        $response = $this->client->post('/api/message/export?status=all', [
            'json' => [
                'year' => $year,
                'month' => $month,
            ]
        ]);
        return $response->getBody();
    }

    // Итого по месяцам
    public function getMonthlyTotal($year)
    {
        $response = $this->client->get('/api/report/total-by-month?year=' . $year);
        return json_decode($response->getBody(), true);
    }

    // Итого по компаниям
    public function getCompanyTotal($year, $month)
    {
        $response = $this->client->post('/api/report/total-by-smsc', [
            'json' => [
                'year' => $year,
                'month' => $month,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Системные логи
    public function getSystemLogs($sms_id)
    {
        $response = $this->client->get('/api/logs/sms/' . $sms_id);
        return json_decode($response->getBody(), true);
    }

    // Расходы по датам
    /***
     * $start_date = '2023-11-01 00:00' 
     * С %Y-%m-%d %H:%M
     */
    public function getExpensesByDates($start_date, $to_date, $is_ad = '', $status = '')
    {
        $response = $this->client->post('/api/report/total-by-range?status=' . $status, [
            'json' => [
                'start_date' => $start_date,
                'to_date' => $to_date,
                'is_ad' => $is_ad,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    // Расходы по рассылке
    public function getExpensesByBulk($dispatch_id, $is_ad = '', $status = '')
    {
        $response = $this->client->post('/api/report/total-by-dispatch?status=' . $status, [
            'json' => [
                'dispatch_id' => $dispatch_id,
                'is_ad' => $is_ad,
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
