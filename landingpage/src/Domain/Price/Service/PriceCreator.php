<?php

namespace App\Domain\Price\Service;

use App\Domain\Price\Data\PriceData;
use App\Domain\Price\Repository\PriceRepository;
// use App\Domain\Price\Repository\PriceValidator;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Service.
 */
final class PriceCreator
{
    private PriceRepository $repository;

    private PriceValidator $priceValidator;

    private LoggerInterface $logger;

    /**
     * The constructor.
     *
     * @param PriceRepository $repository The repository
     * @param PriceValidator $priceValidator The validator
     * @param LoggerFactory $loggerFactory The logger factory
     */
    public function __construct(
        PriceRepository $repository,
        PriceValidator $priceValidator,
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $repository;
        $this->priceValidator = $priceValidator;
        $this->logger = $loggerFactory
            ->addFileHandler('price_creator.log')
            ->createLogger();
    }

    /**
     * Create a new price.
     *
     * @param array<mixed> $data The form data
     *
     * @return int The new price ID
     */
    public function createPrice(array $data): int
    {
        // Input validation
        // $this->priceValidator->validatePrice($data);

        // Map form data to price DTO (model)
        $price = new PriceData($data);
        // Insert price and get new price ID
        $priceId = $this->repository->insertPrice($price);

        // Logging
        $this->logger->info(sprintf('Price created successfully: %s', $priceId));

        return $priceId;
    }
}
