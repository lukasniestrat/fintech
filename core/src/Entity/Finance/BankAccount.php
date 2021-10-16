<?php
declare(strict_types = 1);
namespace App\Entity\Finance;

use App\Repository\Finance\BankAccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BankAccountRepository::class)
 */
class BankAccount
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
     * @ORM\Column(type="string", length=22)
     */
    private string $iban;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isSavingAccount;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, mappedBy="bankAccount")
     */
    private Collection $categories;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="bankAccount")
     */
    private Collection $transactions;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'iban' => $this->getIban(),
            'isSavingAccount' => $this->getIsSavingAccount(),
        ];
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

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getIsSavingAccount(): ?bool
    {
        return $this->isSavingAccount;
    }

    public function setIsSavingAccount(bool $isSavingAccount): self
    {
        $this->isSavingAccount = $isSavingAccount;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addBankAccount($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeBankAccount($this);
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setBankAccount($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getBankAccount() === $this) {
                $transaction->setBankAccount(null);
            }
        }

        return $this;
    }
}
