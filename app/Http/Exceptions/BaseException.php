<?php

declare(strict_types=1);

namespace App\Http\Exceptions;

use Exception;

class BaseException extends Exception
{
    public function render()
    {
        return response([
            'error' => [
                'message' => $this->getMessage()
            ]
        ], $this->code);
    }
}
