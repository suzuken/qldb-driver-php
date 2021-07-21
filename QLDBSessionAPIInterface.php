<?php

namespace Suzuken\QLDBDriver;

/**
 * Interface QLDBSessionAPIInterface
 * @package Suzuken\QLDBDriver
 *
 * This interface emulates QLDBSEssionClient class methods.
 */
interface QLDBSessionAPIInterface
{
    public function sendCommand(array $args = []): \Aws\Result;
    public function sendCommandAsync(array $args = []): \GuzzleHttp\Promise\Promise;
}