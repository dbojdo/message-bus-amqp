<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection;

final class ConnectionParams
{
    const DEFAULT_TIMEOUT = 3.0;
    const DEFAULT_VHOST = '/';

    /** @var string */
    private $host;

    /** @var string */
    private $port;

    /** @var string */
    private $user;

    /** @var string */
    private $password;

    /** @var string */
    private $vHost;

    /** @var float */
    private $timeout;

    /**
     * ConnectionParams constructor.
     * @param string $host
     * @param string $port
     * @param string $user
     * @param string $password
     * @param string $vHost
     * @param float $timeout
     */
    public function __construct(
        string $host,
        string $port,
        string $user,
        string $password,
        string $vHost = self::DEFAULT_VHOST,
        float $timeout = self::DEFAULT_TIMEOUT
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->vHost = $vHost ?: self::DEFAULT_VHOST;
        $this->timeout = $timeout ?: self::DEFAULT_TIMEOUT;
    }

    /**
     * @return string
     */
    public function host(): string
    {
        return (string)$this->host;
    }

    /**
     * @return string
     */
    public function port(): string
    {
        return (string)$this->port;
    }

    /**
     * @return string
     */
    public function user(): string
    {
        return (string)$this->user;
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return (string)$this->password;
    }

    /**
     * @return string
     */
    public function vHost(): string
    {
        return (string)$this->vHost;
    }

    /**
     * @return float
     */
    public function timeout(): float
    {
        return (float)$this->timeout;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return sprintf(
            '%s:%s@%s:%s%s',
            $this->user(),
            '***',
            $this->host(),
            $this->port(),
            $this->vHost()
        );
    }
}
