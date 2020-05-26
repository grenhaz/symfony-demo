<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Excepción de categoría no encontrada.
 * 
 * @author obarcia
 */
class CategoryNotFoundException extends NotFoundHttpException
{
}