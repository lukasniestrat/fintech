<?php
declare(strict_types = 1);
namespace App\Entity\Finance;

use App\Repository\Finance\BankAccountAmountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BankAccountAmountRepository::class)
 */
class BankAccountAmount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private float $amount;

    /**
     * @ORM\OneToOne(targetEntity=BankAccount::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private BankAccount $bankAccount;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(BankAccount $bankAccount): self
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }
}
