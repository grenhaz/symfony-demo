<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Excepción de película no encontrada.
 * 
 * @author obarcia
 */
class MovieNotFoundException extends NotFoundHttpException
{
}