<?php
declare(strict_types=1);
namespace App\Entity\Finance;

use App\Repository\Finance\RepeatingTransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RepeatingTransactionRepository::class)
 */
class RepeatingTransaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private float $amount;

    /**
     * @ORM\Column(type="date")
     */
    private \Date $lastBookingDate;

    /**
     * @ORM\Column(type="date")
     */
    private \Date $nextBookingDate;

    /**
     * @ORM\ManyToOne(targetEntity=BookingIdentifier::class, inversedBy="repeatingTransactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private BookingIdentifier $booking;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="repeatingTransactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Category $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $subject;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getLastBookingDate(): ?\DateTimeInterface
    {
        return $this->lastBookingDate;
    }

    public function setLastBookingDate(\DateTimeInterface $lastBookingDate): self
    {
        $this->lastBookingDate = $lastBookingDate;

        return $this;
    }

    public function getNextBookingDate(): ?\DateTimeInterface
    {
        return $this->nextBookingDate;
    }

    public function setNextBookingDate(\DateTimeInterface $nextBookingDate): self
    {
        $this->nextBookingDate = $nextBookingDate;

        return $this;
    }

    public function getBooking(): ?BookingIdentifier
    {
        return $this->booking;
    }

    public function setBooking(?BookingIdentifier $booking): self
    {
        $this->booking = $booking;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }
}
