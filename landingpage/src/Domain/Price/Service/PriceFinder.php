<?php

namespace App\Domain\Price\Service;

use App\Domain\Price\Data\PriceData;
use App\Domain\Price\Repository\PriceFinderRepository;

/**
 * Service.
 */
final class PriceFinder
{
    private PriceFinderRepository $repository;

    /**
     * The constructor.
     *
     * @param PriceFinderRepository $repository The repository
     */
    public function __construct(PriceFinderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Find prices.
     *
     * @return PriceData[] A list of prices
     */
    public function findPrices(): array
    {
        // Input validation
        // ...

        return $this->repository->findPrices();
    }
}
