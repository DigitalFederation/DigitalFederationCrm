<?php

namespace Domain\Payments\Services;

use Domain\Payments\Contracts\PaymentGatewayInterface;
use Domain\Payments\Gateways\EasyPayGateway;
use Domain\Payments\Gateways\OfflineGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    private array $gateways = [];
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->registerDefaultGateways();
    }

    /**
     * Register a payment gateway
     */
    public function register(string $name, string $gatewayClass): void
    {
        if (! class_exists($gatewayClass)) {
            throw new InvalidArgumentException("Gateway class {$gatewayClass} does not exist");
        }

        if (! in_array(PaymentGatewayInterface::class, class_implements($gatewayClass))) {
            throw new InvalidArgumentException("Gateway class {$gatewayClass} must implement PaymentGatewayInterface");
        }

        $this->gateways[$name] = $gatewayClass;
    }

    /**
     * Get a configured gateway instance
     */
    public function gateway(string $name): PaymentGatewayInterface
    {
        if (! isset($this->gateways[$name])) {
            throw new InvalidArgumentException("Gateway {$name} is not registered");
        }

        $gatewayClass = $this->gateways[$name];
        $gateway = new $gatewayClass;

        // Configure the gateway with its config
        $gatewayConfig = $this->config['gateways'][$name] ?? [];
        $gateway->configure($gatewayConfig);

        return $gateway;
    }

    /**
     * Get all registered gateway names
     */
    public function getAvailableGateways(): array
    {
        return array_keys($this->gateways);
    }

    /**
     * Check if a gateway is registered
     */
    public function hasGateway(string $name): bool
    {
        return isset($this->gateways[$name]);
    }

    /**
     * Register default gateways
     */
    private function registerDefaultGateways(): void
    {
        $this->register('offline', OfflineGateway::class);
        $this->register('easypay', EasyPayGateway::class);
    }

    /**
     * Create manager instance from Laravel config
     */
    public static function createFromConfig(): self
    {
        return new self(config('payment', []));
    }
}
