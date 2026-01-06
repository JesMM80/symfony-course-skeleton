<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert; //This is an alias

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Index(columns: ['sku'], name: 'IDX_product_sku')]
#[ORM\Index(columns: ['price'], name: 'IDX_product_price')]
class Product
{
    #[ORM\Id]
    // #[ORM\Column(type: 'uuid')]  // Mejor que string+CHAR(36)
    // private ?Uuid $id = null;
    #[ORM\Column(type:'string', columnDefinition: 'CHAR(36) NOT NULL')] 
    private ?string $id = null; //Será un string para el UUID

    
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'name must be at least {{ limit }} characters long',
        maxMessage: 'name cannot be longer than {{ limit }} characters',
    )]
    #[Assert\NotBlank()]
    #[ORM\Column(length: 100)]
    private ?string $name = null;
    
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'name must be at least {{ limit }} characters long',
        maxMessage: 'name cannot be longer than {{ limit }} characters',
    )]
    #[Assert\NotBlank()]
    #[ORM\Column(length: 50)]
    private ?string $sku = null;

    #[Assert\PositiveOrZero()]
    #[Assert\NotBlank()]
    #[ORM\Column(type: 'integer')]
    private ?int $price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdOn = null;

    #[Assert\NotBlank()]
    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    public function __construct()
    {
        $this->id = UuidV4::v4()->toRfc4122();
        $this->createdOn = new \DateTime(); 
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
