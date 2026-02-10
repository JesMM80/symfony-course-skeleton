<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(mappedBy: 'orderEntity', targetEntity: OrderItem::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $items; 
    //mappedBy es el nombre del atributo que tenemos en OrderItem que hace referencia a Order, en este caso orderEntity y 
    //sirve para establecer la relacion bidireccional entre ambas entidades, es decir, que podamos acceder a los items 
    // de una orden desde la entidad Order y viceversa, ademas de que al eliminar una orden se eliminen sus items asociados gracias a orphanRemoval=true
    //Por otro lado $items sirve para almacenar la coleccion de items que tiene una orden, y al ser una relacion OneToMany 
    //se inicializa como una ArrayCollection en el constructor de la clase Order
    
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setOrderEntity($this);

        }

        return $this;
    }

    public function removeItem(OrderItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrderEntity() === $this) {
                $item->setOrderEntity(null);
            }
        }

        return $this;
    }

    public function calculateTotal(): void
    {
        $total = 0;

        foreach ($this->items as $item) {
            $item->calculateUnitPrice();
            $total += $item->getSubtotal();
        }

        $this->total = $total;
    }

}
