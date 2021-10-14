<?php
declare(strict_types=1);
namespace App\Entity\Finance;

use App\Repository\Finance\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\Column(type="string", length=510, nullable=true)
     */
    private string $tags;

    /**
     * @ORM\ManyToMany(targetEntity=BankAccount::class, inversedBy="categories")
     */
    private Collection $bankAccount;

    /**
     * @ORM\OneToMany(targetEntity=RepeatingTransaction::class, mappedBy="category")
     */
    private Collection $repeatingTransactions;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="category")
     */
    private Collection $transactions;

    public function __construct()
    {
        $this->bankAccount = new ArrayCollection();
        $this->repeatingTransactions = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'tags' => $this->getTags(),
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

    public function getTags(): ?string
    {
        return $this->tags ?? null;
    }

    public function setTags(string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return Collection|BankAccount[]
     */
    public function getBankAccount(): Collection
    {
        return $this->bankAccount;
    }

    public function addBankAccount(BankAccount $bankAccount): self
    {
        if (!$this->bankAccount->contains($bankAccount)) {
            $this->bankAccount[] = $bankAccount;
        }

        return $this;
    }

    public function removeBankAccount(BankAccount $bankAccount): self
    {
        $this->bankAccount->removeElement($bankAccount);

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
            $repeatingTransaction->setCategory($this);
        }

        return $this;
    }

    public function removeRepeatingTransaction(RepeatingTransaction $repeatingTransaction): self
    {
        if ($this->repeatingTransactions->removeElement($repeatingTransaction)) {
            // set the owning side to null (unless already changed)
            if ($repeatingTransaction->getCategory() === $this) {
                $repeatingTransaction->setCategory(null);
            }
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
            $transaction->setCategory($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCategory() === $this) {
                $transaction->setCategory(null);
            }
        }

        return $this;
    }
}
