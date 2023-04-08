<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

function handleControllerException(Throwable $th, $message)
{
    $code = Str::random(6);
    Log::error("[{$code}] - Error message: {$th}");
    return redirect()
        ->back()
        ->withError("{$message} Error code: {$code}");
}
