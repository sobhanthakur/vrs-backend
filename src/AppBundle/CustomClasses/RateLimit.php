<?php
/**
 * Created by PhpStorm.
 * User: prabhat
 * Date: 30/1/20
 * Time: 1:14 PM
 */

namespace AppBundle\CustomClasses;


use Noxlogic\RateLimitBundle\EventListener\RateLimitAnnotationListener;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Noxlogic\RateLimitBundle\Events\CheckedRateLimitEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

use Noxlogic\RateLimitBundle\Events\RateLimitEvents;
use Noxlogic\RateLimitBundle\Exception\RateLimitExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;


class RateLimit extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}