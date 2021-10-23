<?php
declare(strict_types = 1);
namespace App\Model\Common;

class RequestMetaData
{
    public const ASC = 'asc';

    public const DESC = 'desc';

    public const MAX_LIMIT = 4294967294;

    private int $total;

    private int $limit;

    private int $offset;

    private string $orderBy;

    private string $orderSequence;

    private string $search;

    public function __construct(
        int $total = 0,
        int $limit = 0,
        int $offset = 0,
        string $orderBy = 'id',
        string $orderSequence = self::DESC,
        string $search = ''
    ) {
        $this->total = $total;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->orderBy = $orderBy;
        $this->orderSequence = $orderSequence;
        $this->search = trim($search);
        if (0 === $limit) {
            $this->limit = self::MAX_LIMIT;
        }
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOrderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function setOrderSequence(string $orderSequence): self
    {
        $this->orderSequence = $orderSequence;

        return $this;
    }

    public function getOrderSequence(): string
    {
        return $this->orderSequence;
    }

    public function setSearch(string $search): self
    {
        $this->search = $search;

        return $this;
    }

    public function getSearch(): string
    {
        return $this->search;
    }

    public function toArray(): array
    {
        return [
            'total' => $this->getTotal(),
            'limit' => $this->getLimit(),
            'offset' => $this->getOffset(),
            'orderBy' => $this->getOrderBy(),
            'orderSequence' => $this->getOrderSequence(),
            'search' => $this->getSearch(),
        ];
    }

    public function generateResponseHeader(): array
    {
        return [
            'FIN-Meta-Total' => $this->getTotal(),
            'FIN-Meta-Limit' => $this->getLimit(),
            'FIN-Meta-Offset' => $this->getOffset(),
            'FIN-Meta-OrderBy' => $this->getOrderBy(),
            'FIN-Meta-OrderSequence' => $this->getOrderSequence(),
            'FIN-Meta-Search' => $this->getSearch(),
            'Access-Control-Expose-Headers' => 'FIN-Meta-Total, FIN-Meta-Limit, FIN-Meta-Offset, FIN-Meta-OrderBy, FIN-Meta-OrderSequence, FIN-Meta-Search',
        ];
    }
}
