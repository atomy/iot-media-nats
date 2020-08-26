<?php

declare(strict_types=1);

namespace IotMediaApi;

/**
 * Class EventValidator.
 *
 * @package IotMediaApp
 */
class EventValidator
{
    /**
     * Input request-body.
     *
     * @var string
     */
    protected string $requestBody;

    /**
     * EventValidator constructor.
     *
     * @param string $requestBody input request body
     */
    public function __construct(string $requestBody)
    {
        $this->requestBody = $requestBody;
    }

    /**
     * Check if payload is valid.
     *
     * @return bool
     * @throws \JsonException
     */
    public function isValid(): bool
    {
        if (empty($this->requestBody)) {
            return false;
        }

        $jsonObject = json_decode($this->requestBody, false, 512, JSON_THROW_ON_ERROR);

        if (null === $jsonObject || false === $jsonObject || !is_object($jsonObject)) {
            return false;
        }

        if (empty($jsonObject->clientVersion) || strlen($jsonObject->clientVersion) < 5) {
            return false;
        }

        if (empty($jsonObject->action) || strlen($jsonObject->action) < 4) {
            return false;
        }

        if (empty($jsonObject->timestamp) || !is_int($jsonObject->timestamp)) {
            return false;
        }


        return true;
    }
}
