<?php

namespace App\Domain\Price\Service;

use App\Domain\Price\Data\PriceData;
use App\Domain\Price\Repository\PriceRepository;

/**
 * Service.
 */
final class PriceReader
{
    private PriceRepository $repository;

    /**
     * The constructor.
     *
     * @param PriceRepository $repository The repository
     */
    public function __construct(PriceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a price.
     *
     * @param int $priceId The price id
     *
     * @return PriceData The price data
     */
    public function getPriceData(int $priceId): PriceData
    {
        // Input validation
        // ...

        // Fetch data from the database
        $price = $this->repository->getPriceById($priceId);

        // Optional: Add or invoke your complex business logic here
        // ...

        // Optional: Map result
        // ...

        return $price;
    }
}
