<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ($content = $request->getContent()) {
            $data = json_decode($content, true);

            $request->request = new ParameterBag(is_array($data) ? $data : []);
        }
    }
}
