<?php

namespace App\UseCases\OperationLog;

use App\Models\OperationLog;

class OperationLogUseCase
{
    public function index()
    {
        return OperationLog::all();
    }

    public function store(string $message, ?string $user_id = null, ?string $ip_address = null)
    {
        $log = new OperationLog();

        if (auth()->check()) {
            $log->user_id = auth()->user()->id;
        } else {
            $log->user_id = $user_id ?? '不明';
        }

        $log->action = $message;
        $log->ip_address = $ip_address ?? request()->ip();
        $log->save();
    }
}
