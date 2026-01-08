<?php

declare(strict_types=1);

namespace App\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    //Inyección de dependencias para usar el servicio de logging.
    public function __construct(private LoggerInterface $logger){   
    }
    
    //Método que maneja el evento de solicitud HTTP si el path info es '/category/new' y el método es 'POST'.
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->getPathInfo() === '/category/new' && $request->getMethod() === 'POST') {
            $this->logger->info('Path info contiene: ' . $request->getPathInfo());
        }
        
    }
}