<?php
namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;




class CategoryCreated 
{
        
    public function __construct(
        public readonly string $id,
        public readonly string $name,   
        public readonly string $createdOn
    )    
        
    {
    }

}