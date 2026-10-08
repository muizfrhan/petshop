<?php

namespace App\Exceptions;

use Exception;

class StockUnavailableException extends Exception
{
    public function __construct(string $message = 'Stok produk tidak mencukupi.')
    {
        parent::__construct($message);
    }
}