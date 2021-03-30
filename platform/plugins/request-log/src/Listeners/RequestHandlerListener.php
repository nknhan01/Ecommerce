<?php

namespace Botble\RequestLog\Listeners;

use Botble\RequestLog\Events\RequestHandlerEvent;
use Botble\RequestLog\Models\RequestLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestHandlerListener
{
    /**
     * @var RequestLog
     */
    public $requestLog;

    /**
     * @var Request
     */
    protected $request;

    /**
     * RequestHandlerListener constructor.
     * @param RequestLog $requestLog
     * @param Request $request
     */
    public function __construct(RequestLog $requestLog, Request $request)
    {
        $this->requestLog = $requestLog;
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param RequestHandlerEvent $event
     * @return boolean
     */
    public function handle(RequestHandlerEvent $event)
    {
        $this->requestLog = RequestLog::firstOrNew([
            'url'         => Str::limit($this->request->fullUrl(), 120),
            'status_code' => $event->code,
        ]);

        if ($referrer = $this->request->header('referrer')) {
            $referrers = (array)$this->requestLog->referer ?: [];
            $referrers[] = $referrer;
            $this->requestLog->referer = $referrers;
        }

        if (Auth::check()) {
            if (!is_array($this->requestLog->user_id)) {
                $this->requestLog->user_id = [Auth::user()->getKey()];
            } else {
                $this->requestLog->user_id = array_unique(
                    array_merge($this->requestLog->user_id, [Auth::user()->getKey()])
                );
            }
        }

        if (!$this->requestLog->exists) {
            $this->requestLog->count = 1;
        } else {
            $this->requestLog->count += 1;
        }

        return $this->requestLog->save();
    }
}
