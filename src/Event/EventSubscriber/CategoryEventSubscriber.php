<?php

declare(strict_types=1); //Sirve para forzar el tipado estricto en PHP y esto ayuda a prevenir 
                         //errores relacionados con la conversión de tipos de datos.
namespace App\Event\EventSubscriber;

use Twig\Environment;
use App\Event\CategoryCreated;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CategoryEventSubscriber implements EventSubscriberInterface
{
    //Inyección de dependencias a través del constructor para usar el servicio de correo y el motor de plantillas Twig.
    public function __construct( private MailerInterface $mailer,private Environment $engine)
    {
    }

    //Método estático que siempre se usa y devuelve un array asociativodonde las claves son los nombres de los eventos 
    //donde las claves son los nombres de los eventos y los valores son los métodos que deben ser llamados 
    //cuando esos eventos son despachados.
    public static function getSubscribedEvents(): array 
    {
        return [
            // Cuando se ejecuta el evento CategoryCreated, se llama al método onCategoryCreated. Esto se registra
            // para que el despachador de eventos sepa qué hacer cuando se produce ese evento        
            CategoryCreated::class => 'onCategoryCreated',
            //ProductCreated::class => 'onProductCreated', Aquí se podrían agregar más eventos y sus 
                                                            //manejadores correspondientes.
        ];
    }

    public function onCategoryCreated(CategoryCreated $event): void
    {
        // Lógica para manejar el evento de creación de categoría.
        $email = (new Email())
                ->from('admin@example.com')
                ->to('hazuki00@gmail.com')
                ->subject('New Category Created')
                ->text('A new category has been created.')
                ->html(
                    $this->engine->render('emails/new-category.html.twig', [
                    'id' => $event->id,
                    'name' => $event->name,
                    'created_on' => $event->createdOn,
                ]));

            $this->mailer->send($email);
    }
}