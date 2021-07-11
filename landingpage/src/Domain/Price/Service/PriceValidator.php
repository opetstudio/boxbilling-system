<?php

namespace App\Domain\Price\Service;

use App\Domain\Price\Repository\PriceRepository;
// use App\Domain\Price\Type\PriceRoleType;
use App\Factory\ValidationFactory;
use Cake\Validation\Validator;
use Selective\Validation\Exception\ValidationException;

/**
 * Service.
 */
final class PriceValidator
{
    private PriceRepository $repository;

    private ValidationFactory $validationFactory;

    /**
     * The constructor.
     *
     * @param PriceRepository $repository The repository
     * @param ValidationFactory $validationFactory The validation
     */
    public function __construct(PriceRepository $repository, ValidationFactory $validationFactory)
    {
        $this->repository = $repository;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Validate update.
     *
     * @param int $userId The user id
     * @param array<mixed> $data The data
     *
     * @return void
     */
    public function validatePriceUpdate(int $userId, array $data): void
    {
        if (!$this->repository->existsPriceId($userId)) {
            throw new ValidationException(sprintf('Price not found: %s', $userId));
        }

        $this->validatePrice($data);
    }

    /**
     * Validate new user.
     *
     * @param array<mixed> $data The data
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function validatePrice(array $data): void
    {
        $validator = $this->createValidator();

        $validationResult = $this->validationFactory->createValidationResult(
            $validator->validate($data)
        );

        if ($validationResult->fails()) {
            throw new ValidationException('Please check your input', $validationResult);
        }
    }

    /**
     * Create validator.
     *
     * @return Validator The validator
     */
    private function createValidator(): Validator
    {
        $validator = $this->validationFactory->createValidator();

        return $validator
            ->notEmptyString('username', 'Input required')
            ->notEmptyString('password', 'Input required')
            ->minLength('password', 8, 'Too short')
            ->maxLength('password', 40, 'Too long')
            ->email('email', false, 'Input required')
            // ->inList('user_role_id', [PriceRoleType::ROLE_USER, PriceRoleType::ROLE_ADMIN], 'Invalid')
            ->notEmptyString('locale', 'Input required')
            ->regex('locale', '/^[a-z]{2}\_[A-Z]{2}$/', 'Invalid')
            ->boolean('enabled', 'Invalid');
    }
}
