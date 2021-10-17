<?php
declare(strict_types = 1);
namespace App\Entity\Finance;

use App\Model\Common\FinConstants;
use App\Repository\Finance\TransactionRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
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
    private DateTimeInterface $bookingDate;

    /**
     * @ORM\Column(type="string", length=22)
     */
    private string $iban;

    /**
     * @ORM\ManyToOne(targetEntity=BankAccount::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private BankAccount $bankAccount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $subject;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="transactions")
     */
    private Category $category;

    public function __construct()
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'subject' => $this->getSubject(),
            'amount' => $this->getSubject(),
            'bookingDate' => $this->getBookingDate()->format(FinConstants::DATE_FORMAT_DATE_ONLY),
            'iban' => $this->getIban(),
            'category' => $this->getCategory()->toArray(),
            'bankAccount' => $this->getBankAccount()->toArray(),
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBookingDate(): ?DateTimeInterface
    {
        return $this->bookingDate;
    }

    public function setBookingDate(DateTimeInterface $bookingDate): self
    {
        $this->bookingDate = $bookingDate;

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

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?BankAccount $bankAccount): self
    {
        $this->bankAccount = $bankAccount;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
