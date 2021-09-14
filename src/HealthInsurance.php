<?php

namespace CogentHealth\Healthinsurance;

use App\Utils\Options;
use CogentHealth\Healthinsurance\repository\HiInterface;
use CogentHealth\Healthinsurance\services\HiAuthService;
use GuzzleHttp\Client;

class HealthInsurance implements HiInterface
{
    protected static $message;
    protected static $success = true;
    protected static $httpStatusCode;
    protected static $responseBody;

    private static $username;
    private static $password;
    private static $hiClient;
    private static $clientOptions;
    private static $hostName;

    /**
     *
     * @var Singleton
     */
    private static $instance;

    /**
     * Constructor
     */
    public function __construct()
    {
        self::$username = HiAuthService::getUsername();
        self::$password = HiAuthService::getPassword();
        self::$hostName = env("HI_API_URL") ? env("HI_API_URL") : Options::get('hi_settings')['hi_url'] ?? '';

        self::$clientOptions = [
//            'verify' => false,
            'auth' => [
                self::$username,
                self::$password
            ],
            'headers' => [
                'remote-user' => Options::get('hi_settings')['hi_remote_user'] ?? ''
            ],
            'base_uri' => self::$hostName
        ];
        self::$hiClient = new Client(self::$clientOptions);
    }

    /**
     * @return HealthInsurance|repository\json|Singleton|\json
     */
    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param int $patientId
     * @return repository\json|\Illuminate\Http\JsonResponse|\json
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function eligibilityRequest($patientId)
    {
        try {
            self::init();
            $request_body = [
                "resourceType" => "EligibilityRequest",
                "patient" => [
                    "reference" => "Patient/" . $patientId
                ]
            ];

            $response = self::$hiClient->post(
                config('hi_api_url.EligibilityRequest'),
                [
                    'json' => $request_body
                ]
            );

            self::$httpStatusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(), true);
        } catch (\RequestException $e) {
            if ($e->hasResponse()) {
                $responseBody = json_decode($e->getResponse()->getBody()->getContents());
            }
            self::$success = false;
            self::$httpStatusCode = 500;
        } catch (\Exception $e) {
            $responseBody = '';
            self::$success = false;
            self::$message = $e->getMessage();
            self::$httpStatusCode = 500;
        }

        self::$responseBody = $responseBody;
        return self::apiResponse();
    }

    /**
     * @param int $patientId
     * @return repository\json|\Illuminate\Http\JsonResponse|\json
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getPatientDetailById($patientId)
    {
        try {
            self::init();

            $response = self::$hiClient->get(
                config('hi_api_url.patient') . "?identifier=" . $patientId
            );

            self::$httpStatusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(), true);
        } catch (\RequestException $e) {
            if ($e->hasResponse()) {
                $responseBody = json_decode($e->getResponse()->getBody()->getContents());
            }
            self::$success = false;
            self::$httpStatusCode = 500;
        } catch (\Exception $e) {
            $responseBody = '';
            self::$success = false;
            self::$message = $e->getMessage();
            self::$httpStatusCode = 500;
        }

        self::$responseBody = $responseBody;
        return self::apiResponse();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public static function apiResponse()
    {
        return response()->json([
            'data' => self::$responseBody,
            'message' => self::$message,
            'success' => self::$success
        ], self::$httpStatusCode);
    }
}
