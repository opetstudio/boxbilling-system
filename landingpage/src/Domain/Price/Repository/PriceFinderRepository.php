<?php

namespace App\Domain\Price\Repository;

use App\Domain\Price\Data\PriceData;
use App\Factory\QueryFactory;
use App\Support\Hydrator;

/**
 * Repository.
 */
final class PriceFinderRepository
{
    private QueryFactory $queryFactory;

    private Hydrator $hydrator;

    /**
     * The constructor.
     *
     * @param QueryFactory $queryFactory The query factory
     * @param Hydrator $hydrator The hydrator
     */
    public function __construct(QueryFactory $queryFactory, Hydrator $hydrator)
    {
        $this->queryFactory = $queryFactory;
        $this->hydrator = $hydrator;
    }

    /**
     * Find prices.
     *
     * @return PriceData[] A list of prices
     */
    public function findPrices(): array
    {
        $query = $this->queryFactory->newSelect('prices');

        $query->select(
            [
                'id',
                'title',
                'description',
                'price'
            ]
        );

        // Add more "use case specific" conditions to the query
        // ...

        $rows = $query->execute()->fetchAll('assoc') ?: [];

        // Convert to list of objects
        return $this->hydrator->hydrate($rows, PriceData::class);
    }
}
