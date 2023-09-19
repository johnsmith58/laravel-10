<?php

declare(strict_types=1);

namespace App\Http\Exceptions;

use Exception;

final class FailStoreArticleException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => $this->message
        ]);
    }
}
