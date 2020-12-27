<?php
/**
 * Created by PhpStorm.
 * User: a_obidov
 * Date: 24.12.2020
 * Time: 11:52
 */

namespace frontend\controllers;


use yii\rest\Controller;
use \BenMajor\ExchangeRatesAPI\ExchangeRatesAPI;


class ApiController extends Controller
{

    private static $allowedDomains = [
        'http://localhost:3000',
        'http://localhost',
        'http://localhost:63342',
        '127.0.0.1'
    ];


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'corsFilter' => [
                'class' => 'yii\filters\Cors',
                'cors' => [
                    'Origin' => self::$allowedDomains,
                    'Access-Control-Request-Method' => ['GET'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,                 // Cache (seconds)
                ],
            ]
        ]);
    }

    /**
     * @param $p
     * @param $r
     * @return mixed
     * @throws \BenMajor\ExchangeRatesAPI\Exception
     */
    public function actionIndex($p, $r)
    {
        return $this->response(
            (new ExchangeRatesAPI())
                ->setBaseCurrency($p)
                ->addRates([$r])
                ->fetch()
                ->getRate($r)
        );
    }

    /**
     * Returns supported currencies
     * @return array
     */
    public function actionC()
    {
        return $this->response((new ExchangeRatesAPI())->getSupportedCurrencies());
    }

    /**
     * @param array $data
     * @param string $message
     * @param int $status
     * @return array
     */
    private function response($data, $message = 'OK', $status = 200)
    {
        return [
            'message' => $message,
            'status' => $status,
            'data' => $data
        ];
    }
}