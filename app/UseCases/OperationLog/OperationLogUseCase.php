<?php

namespace App\UseCases\OperationLog;

use App\Models\OperationLog;

/**
 * 操作ログに関するユースケースクラス
 */
class OperationLogUseCase
{
    /* =================== 以下メインの処理 =================== */

    /**
     * 操作ログの詳細を保存する
     * 
     * @param array $data 操作ログのデータ
     */
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
