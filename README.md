# Message Bus - AMQP Infrastructure

AMQP protocol infrastructure for Message Bus

## Installation

```bash
composer require webit/message-bus-amqp=^1.0.0
```

## Usage

### Connection Pool

Use ConnectionPoolBuilder to create one

```php
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\ConnectionPoolBuilder;
use Webit\MessageBus\Infrastructure\Amqp\Connection\ConnectionParams;


$builder = ConnectionPoolBuilder::create();

// optionally set connection factory (LazyConnectionFactory used by default)
$builder->setConnectionFactory(
    new \Webit\MessageBus\Infrastructure\Amqp\Connection\InstantConnectionFactory()
);

// optionally add logger (use a smarter one in real life)
$logger = new \Psr\Log\NullLogger();
$builder->setLogger($logger);

// register at least one connection
$builder->registerConnection(
    new ConnectionParams(
        'rabbitmq.host',
        '5672', // port,
        'my-username',
        'my-password'
    ),
    'connection-1'
);

$connectionPool = $builder->build();
 
```

ConnectionPool gives you a current connection.
If you find something is wrong with the current connection, 
you can dispose it and ask pool to give you a next one (if has any more).

```php

try {
    $connection = $connectionPool->current();
    $channel = $connection->getChannel();
} catch (\Exception $e) {
    $connectionPool->disposeCurrent();
    $connection = $connectionPool->current();
}

```

### Publisher integration

To publish ***Message*** via AMQP use ***AmqpPublisher***

```php
use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\NewChannelConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\ExchangePublicationTarget;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\QueuePublicationTarget;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\AmqpPublisher;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\Routing\FromMessageTypeRoutingKeyResolver;

$channelFactory = new NewChannelConnectionAwareChannelFactory($connectionPool);

$publicationTarget = new ExchangePublicationTarget(
    $channelFactory,
    new FromMessageTypeRoutingKeyResolver(), // you can provide your implementation
    'exchange-name'
);

// or

$publicationTarget = new QueuePublicationTarget(
    $channelFactory,
    'queueName'
);

$publisher = new AmqpPublisher($publicationTarget);

$message = new Message('my-type', 'message_content');
$publisher->publish($message);

```

### Message consumption

To listen for messages from AMQP and consume them:
 1. Implement your ***Consumer***
 
 ```php
 use Webit\MessageBus\Consumer;
 use Webit\MessageBus\Message;
 
 class \MyConsumer implements Consumer
 {
     public function consume(Message $message)
     {
         // do your stuff here
     }
 }
 ```
 
 2. Build AmqpConsumer
 
 ```php
 use Webit\MessageBus\Infrastructure\Amqp\Listener\Message\MessageFactory;
 use Webit\MessageBus\Infrastructure\Amqp\Listener\AmqpConsumerBuilder;
 
 $builder = AmqpConsumerBuilder::create();
 $builder->setConsumer(new \MyConsumer());
 $builder->setLogger(new NullLogger()); // optional
 $builder->shouldSendFeedback(false); // if you don't want to acknowledge messages, set this to false (true by default)
 $builder->setMessageFactory(new SimpleMessageFactory()); // optionally set your MessageFactory
 
 $amqpConsumer = $builder->build();
 ```
 
 3. Start listening for AMQPMessages
 
  ```php
  
  $listener = new SimpleAmqpListener(
    $channelFactory,
    $amqpConsumer,
    'queue-name'
  );
  
  // start listening (continuous process)
  $listener->listen();

  ```

## Running tests

Unit tests

```bash
docker-compose run --rm php ./vendor/bin/phpunit
```

Integration tests

```bash
docker-compose run --rm php ./vendor/bin/phpunit -c tests/integration/phpunit.xml.dist
```