<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'rentals')]
class Rental
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Property::class, inversedBy: 'rentals')]
    #[ORM\JoinColumn(name: 'property_id', nullable: false)]
    private ?Property $property = null;

    #[ORM\Column(length: 255)]
    private string $title = '';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $monthlyRent = '0.00';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $securityDeposit = null;

    #[ORM\Column(length: 20)]
    private string $availabilityStatus = 'available';

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): static
    {
        $this->property = $property;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMonthlyRent(): string
    {
        return $this->monthlyRent;
    }

    public function getAvailabilityStatus(): string
    {
        return $this->availabilityStatus;
    }
}
