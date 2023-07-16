<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\OperationLogController;
use App\Mail\MailSend;
use Illuminate\Support\Facades\Mail;

/**
 * イベントコントローラークラス
 * 
 * このクラスはイベントに関する処理を行うコントローラーです。
 */
class EventController extends Controller
{
}
