<?php
declare(strict_types = 1);
namespace App\Model\Common;

class ModelList
{
    private array $list;

    private RequestMetaData $metaData;

    private array $additionalData;

    public function __construct(array $list, RequestMetaData $metaData)
    {
        $this->list = $list;
        $this->metaData = $metaData;
        $this->additionalData = [];
    }

    public function setList(array $list): self
    {
        $this->list = $list;

        return $this;
    }

    public function getList(): array
    {
        return $this->list;
    }

    public function setMetaData(RequestMetaData $metaData): void
    {
        $this->metaData = $metaData;
    }

    public function getMetaData(): RequestMetaData
    {
        return $this->metaData;
    }

    public function addToList(object $item): void
    {
        $this->list[] = $item;
    }

    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }

    public function setAdditionalData(array $additionalData): void
    {
        $this->additionalData = $additionalData;
    }
}
