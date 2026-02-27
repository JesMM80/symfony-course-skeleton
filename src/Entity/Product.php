<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderItem::class, orphanRemoval: true)]
    private Collection $orderItems;

    /**
     * @var Collection<int, Supplier>
     */
    #[ORM\ManyToMany(targetEntity: Supplier::class, inversedBy: 'products')]
    private Collection $suppliers;
    //Es el lado propietario porque es el que tiene la anotación @ORM\ManyToMany 
    //sin mappedBy, y el lado inverso (Supplier) tiene mappedBy='suppliers' 

    /**
     * @var Collection<int, StockMovement>
     */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: StockMovement::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $stockMovements; 

    public function __construct()
    {
        $this->id = UuidV4::v4()->toRfc4122();
        $this->createdOn = new \DateTime();
        $this->orderItems = new ArrayCollection();
        $this->suppliers = new ArrayCollection();
        $this->stockMovements = new ArrayCollection(); 
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

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Supplier>
     */
    public function getSuppliers(): Collection
    {
        return $this->suppliers;
    }

    //Este método es necesario para mantener la relación bidireccional entre Product y Supplier, ya que el 
    //lado inverso (Supplier) también tiene una colección de productos. Al agregar un proveedor a un producto, 
    //también se agrega el producto a la colección de productos del proveedor. 
    public function addSupplier(Supplier $supplier): static
    {
        if (!$this->suppliers->contains($supplier)) {
            $this->suppliers->add($supplier);
        }

        return $this;
    }

    public function removeSupplier(Supplier $supplier): static
    {
        $this->suppliers->removeElement($supplier);

        return $this;
    }

    /**
     * @return Collection<int, StockMovement>
     */
    public function getStockMovements(): Collection
    {
        return $this->stockMovements;
    }

    public function addStockMovement(StockMovement $stockMovement): static
    {
        if (!$this->stockMovements->contains($stockMovement)) {
            $this->stockMovements->add($stockMovement);
            $stockMovement->setProduct($this);
        }

        return $this;
    }

    public function removeStockMovement(StockMovement $stockMovement): static
    {
        if ($this->stockMovements->removeElement($stockMovement)) {
            // set the owning side to null (unless already changed)
            if ($stockMovement->getProduct() === $this) {
                $stockMovement->setProduct(null);
            }
        }

        return $this;
    }
}
