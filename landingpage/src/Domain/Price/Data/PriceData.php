<?php

namespace App\Domain\Price\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class PriceData
{
    public ?int $id = null;

    public ?string $title = null;

    public ?string $description = null;

    public ?int $price = null;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->id = $reader->findInt('id');
        $this->title = $reader->findString('title');
        $this->description = $reader->findString('description');
        $this->price = $reader->findInt('price');
    }
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getShortDescription() {
        return substr($this->description, 0, 20);
    }

    public function getPrice() {
        return $this->price;
    }

}
