<?php
declare(strict_types=1);

namespace Xpify\Merchant\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Xpify\Merchant\Api\Data\MerchantInterface as IMerchant;

interface MerchantRepositoryInterface
{
    /**
     * @param int $id
     * @return IMerchant
     * @throws NoSuchEntityException
     */
    public function getById(int $id): IMerchant;

    /**
     * Save merchant
     *
     * @param IMerchant $merchant
     * @return IMerchant
     * @throws CouldNotSaveException
     */
    public function save(IMerchant $merchant): IMerchant;

    /**
     * Destroy merchant
     *
     * @param IMerchant $merchant
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(IMerchant $merchant): bool;

    /**
     * Destroy merchant by id
     *
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $id): bool;

    /**
     * Get merchant list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Xpify\Merchant\Api\Data\MerchantSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria): \Xpify\Merchant\Api\Data\MerchantSearchResultsInterface;
}
