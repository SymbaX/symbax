<?php

namespace App\UseCases\OperationLog;

use App\Models\OperationLog;

class OperationLogUseCase
{
    public function index()
    {
        return OperationLog::all();
    }

    public function store(array $data)
    {
        $log = new OperationLog();

        if (auth()->check()) {
            $log->user_id = auth()->user()->id;
        } else {
            $log->user_id = $data['user_id'] ?? '不明';
        }
        $log->target_event_id = $data['target_event_id'];
        $log->target_user_id = $data['target_user_id'];
        $log->target_topic_id = $data['target_topic_id'];
        $log->action = $data['action'];
        $log->detail = $data['detail'];
        $log->ip_address = $data['ip'] ?? request()->ip();
        $log->save();
    }
}
