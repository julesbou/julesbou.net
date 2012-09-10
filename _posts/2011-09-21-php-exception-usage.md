---
layout: post
tags: backend
title: Different types of exceptions in php
---

<figure>
    <img src="/assets/images/2011-php-exception-usage/ghost_buster.jpeg" alt="differente types of php exceptions" />
</figure>

In this article we will see how to use some of the most widely used exception subclasses in PHP.

## RuntimeException

_=> An error occurred during the code execution._

A usual case scenario is when there's a mistake during a database connection; we throw a runtime exception because the failure only occurs when we run the code :

```php
<?php

try {
    $db = new Database($dsn, $user, $password);
} catch (RuntimeException $e) {
    echo 'Error during connection : ' . $e->getMessage();
}
```

## LogicException

_=> Your code is trying to do something illogical._

You have a LogicException when there's a logic-related error in your code. For exemple we can't accelerate a car if the engine is not started :

```php
<?php

class Car
{
    protected $speed;
    protected $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function start()
    {
        $this->engine->start();
    }

    public function accelerate()
    {
        if (!$this->engine->isStarted()) {
            throw new LogicException('cannot accelerate if the engine is not started');
        }

        $this->speed++;
    }
}
```

## OutOfBoundsException

_=> The key you're looking for is invalid._

An OutOfBoundsException occurs when a key is invalid. Let's say we have a container to store objects:

```php
<?php

class Container
{
    protected $bag = array();

    public function set($key, $val)
    {
        $this->bag[$key] = $val;
    }

    public function get($key)
    {
        if (!isset($this->bag[$key])) {
            throw new OutOfBoundsException('key '.$key.' was not found in the container');
        }

        return $this->bag[$key];
    }
}
```

## InvalidArgumentException

_=> The argument you have provided is invalid._

Let's say we have a bank account, in PHP we can't check if an argument is an integer, so we have to do some validation :

```php
<?php

class BankAccount
{
    protected $balance;

    public function __construct()
    {
        $this->balance = 0;
    }

    public function credit($number)
    {
        if (!is_int($number)) {
            throw new InvalidArgumentException('argument should be an integer');
        }

        $this->balance += $number;
    }
}
```

Now, we can provide an integer only to the <code>credit</code> method :

```php
<?php

$ba = new BankAccount();
$ba->credit(200);
$ba->credit('invalid value'); // throws InvalidArgumentException
```

## ErrorException

_=> There was an error._

This exception can be used to register an error handler that converts errors to exceptions :

```php
<?php

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

// register the error handler
set_error_handler(exception_error_handler);
```

## BadMethodCallException

_=> The method you're trying to call is invalid._

In Doctrine there is a repository to fetch objects from your database. You can call methods like `findOneBySlug('php-exceptions')`. Doctrine makes a good usage of this method in the `EntityRepository` :

```php
<?php

class EntityRepository
{
    /* ... */

    public function __call($method, $arguments)
    {
        if (substr($method, 0, 6) == 'findBy') {
            $by = substr($method, 6, strlen($method));
            $method = 'findBy';
        } else if (substr($method, 0, 9) == 'findOneBy') {
            $by = substr($method, 9, strlen($method));
            $method = 'findOneBy';
        } else {
            throw new BadMethodCallException('Undefined method '.$method);
        }
    }

    /* ... */
}
```

<hr/>

Sources: [spl exceptions](http://www.php.net/manual/en/spl.exceptions.php)
