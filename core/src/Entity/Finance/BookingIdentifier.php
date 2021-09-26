<?php

namespace App\Entity\Finance;

use App\Repository\Finance\BookingIdentifierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingIdentifierRepository::class)
 */
class BookingIdentifier
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
     * @ORM\OneToMany(targetEntity=RepeatingTransaction::class, mappedBy="booking")
     */
    private Collection $repeatingTransactions;

    public function __construct()
    {
        $this->repeatingTransactions = new ArrayCollection();
    }

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

    /**
     * @return Collection|RepeatingTransaction[]
     */
    public function getRepeatingTransactions(): Collection
    {
        return $this->repeatingTransactions;
    }

    public function addRepeatingTransaction(RepeatingTransaction $repeatingTransaction): self
    {
        if (!$this->repeatingTransactions->contains($repeatingTransaction)) {
            $this->repeatingTransactions[] = $repeatingTransaction;
            $repeatingTransaction->setBooking($this);
        }

        return $this;
    }

    public function removeRepeatingTransaction(RepeatingTransaction $repeatingTransaction): self
    {
        if ($this->repeatingTransactions->removeElement($repeatingTransaction)) {
            // set the owning side to null (unless already changed)
            if ($repeatingTransaction->getBooking() === $this) {
                $repeatingTransaction->setBooking(null);
            }
        }

        return $this;
    }
}
