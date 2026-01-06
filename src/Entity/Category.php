<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\String\s;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', columnDefinition:'char(36) NOT NULL')]
    private ?string $id = null;

    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'name must be at least {{ limit }} characters long',
        maxMessage: 'name cannot be longer than {{ limit }} characters',
    )]
    #[Assert\NotBlank()]
    #[ORM\Column(type: 'string', length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdOn = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class)] // Relación inversa
    private Collection $products; // Una categoría tiene muchos productos

    public function __construct()
    {
        $this->id = UuidV4::v4()->toRfc4122();
        $this->createdOn = new \DateTime();
        $this->products = new ArrayCollection(); // Inicializamos la colección
    }

    public function getId(): string
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

    public function getCreatedOn(): \DateTimeInterface
    {
        return $this->createdOn;
    }


    /**
     * @return Collection<int, Product> //Devolverá una colección o array de productos
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) { // Si no lo contiene ya
            $this->products->add($product);// Añadimos el producto a la colección y este método está en la en colección
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}
