<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['category-read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['category-read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Candle>
     */
    #[ORM\ManyToMany(targetEntity: Candle::class, mappedBy: 'categories')]
    private Collection $candles;

    public function __construct()
    {
        $this->candles = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Candle>
     */
    public function getCandles(): Collection
    {
        return $this->candles;
    }

    public function addCandle(Candle $candle): static
    {
        if (!$this->candles->contains($candle)) {
            $this->candles->add($candle);
            $candle->addCategory($this);
        }

        return $this;
    }

    public function removeCandle(Candle $candle): static
    {
        if ($this->candles->removeElement($candle)) {
            $candle->removeCategory($this);
        }

        return $this;
    }
}
