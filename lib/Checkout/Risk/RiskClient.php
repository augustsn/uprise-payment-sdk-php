<?php

namespace Checkout\Risk;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Risk\preauthentication\PreAuthenticationAssessmentRequest;
use Checkout\Risk\precapture\PreCaptureAssessmentRequest;

class RiskClient extends Client
{
    private const PRE_AUTHENTICATION_PATH = "risk/assessments/pre-authentication";
    private const PRE_CAPTURE_PATH = "risk/assessments/pre-capture";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param PreAuthenticationAssessmentRequest $preAuthenticationAssessmentRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function requestPreAuthenticationRiskScan(PreAuthenticationAssessmentRequest $preAuthenticationAssessmentRequest)
    {
        return $this->apiClient->post(self::PRE_AUTHENTICATION_PATH, $preAuthenticationAssessmentRequest, $this->sdkAuthorization());
    }

    /**
     * @param PreCaptureAssessmentRequest $preCaptureAssessmentRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function requestPreCaptureRiskScan(PreCaptureAssessmentRequest $preCaptureAssessmentRequest)
    {
        return $this->apiClient->post(self::PRE_CAPTURE_PATH, $preCaptureAssessmentRequest, $this->sdkAuthorization());

    }
}
