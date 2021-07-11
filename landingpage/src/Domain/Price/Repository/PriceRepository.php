<?php

namespace App\Domain\Price\Repository;

use App\Domain\Price\Data\PriceData;
use App\Factory\QueryFactory;
use Cake\Chronos\Chronos;
use DomainException;

/**
 * Repository.
 */
final class PriceRepository
{
    private QueryFactory $queryFactory;

    /**
     * The constructor.
     *
     * @param QueryFactory $queryFactory The query factory
     */
    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    /**
     * Insert price row.
     *
     * @param PriceData $price The price data
     *
     * @return int The new ID
     */
    public function insertPrice(PriceData $price): int
    {
        $row = $this->toRow($price);
        // $row['created_at'] = Chronos::now()->toDateTimeString();

        return (int)$this->queryFactory->newInsert('prices', $row)
            ->execute()
            ->lastInsertId();
    }

    /**
     * Get price by id.
     *
     * @param int $priceId The price id
     *
     * @throws DomainException
     *
     * @return PriceData The price
     */
    public function getPriceById(int $priceId): PriceData
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

        $query->andWhere(['id' => $priceId]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(sprintf('Price not found: %s', $priceId));
        }

        return new PriceData($row);
    }

    /**
     * Update price row.
     *
     * @param PriceData $price The price
     *
     * @return void
     */
    public function updatePrice(PriceData $price): void
    {
        $row = $this->toRow($price);

        // Updating the password is another use case
        // unset($row['password']);

        // $row['updated_at'] = Chronos::now()->toDateTimeString();

        $this->queryFactory->newUpdate('prices', $row)
            ->andWhere(['id' => $price->id])
            ->execute();
    }

    /**
     * Check price id.
     *
     * @param int $priceId The price id
     *
     * @return bool True if exists
     */
    public function existsPriceId(int $priceId): bool
    {
        $query = $this->queryFactory->newSelect('prices');
        $query->select('id')->andWhere(['id' => $priceId]);

        return (bool)$query->execute()->fetch('assoc');
    }

    /**
     * Delete price row.
     *
     * @param int $priceId The price id
     *
     * @return void
     */
    public function deletePriceById(int $priceId): void
    {
        $this->queryFactory->newDelete('prices')
            ->andWhere(['id' => $priceId])
            ->execute();
    }

    /**
     * Convert to array.
     *
     * @param PriceData $price The price data
     *
     * @return array The array
     */
    private function toRow(PriceData $price): array
    {
        return [
            'id' => $price->id,
            'title' => $price->title,
            'description' => $price->description,
            'price' => $price->price
        ];
    }
}
