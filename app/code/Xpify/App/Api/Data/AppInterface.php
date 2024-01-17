<?php
declare(strict_types=1);

namespace Xpify\App\Api\Data;

interface AppInterface
{
    const ACCESS_MODE_ONLINE = 'online';
    const ACCESS_MODE_OFFLINE = 'offline';

    /**
     * Default access mode. Hiện tại chỉ support offline mode.
     *
     * Dùng biến này để định nghĩa những chỗ nào cần sử dụng access mode khi mà mình chưa triển khai phần app access mode này.
     * Có thể sau này triển khai access mode cho từng app thì lúc ấy mò theo biến này dễ hơn 🫡
     */
    const DEFAULT_ACCESS_MODE = self::ACCESS_MODE_OFFLINE;
    const CURRENCY_CODE = 'USD'; // Currently only supports USD
    const ID = 'entity_id';
    const REMOTE_ID = 'remote_id';
    const NAME = 'name';
    const API_KEY = 'api_key';
    const SECRET_KEY = 'secret_key';
    const SCOPES = 'scopes';
    const API_VERSION = 'api_version';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const BILLING_REQUIRED = 'billing_required';
    const BILLING_INTERVAL = 'billing_interval';

    public function getApiVersion(): ?string;

    public function setApiVersion(?string $version): self;

    /**
     * Get billing required
     *
     * @return int|null
     */
    public function getBillingRequired(): ?int;

    /**
     * Check if app is billing required
     *
     * @return bool
     */
    public function isBillingRequired(): bool;

    /**
     * @return string|null
     */
    public function getRemoteId(): ?string;

    /**
     * @param int|string $remoteId
     * @return AppInterface
     */
    public function setRemoteId(int|string $remoteId): AppInterface;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string $name
     * @return AppInterface
     */
    public function setName(string $name): AppInterface;

    /**
     * @return string|null
     */
    public function getApiKey(): ?string;

    /**
     * @param string $apiKey
     * @return AppInterface
     */
    public function setApiKey(string $apiKey): AppInterface;

    /**
     * @return string|null
     */
    public function getSecretKey(): ?string;

    /**
     * @param string $secretKey
     * @return AppInterface
     */
    public function setSecretKey(string $secretKey): AppInterface;

    /**
     * Get the scopes
     *
     * This method is used to get the scopes of the application.
     * The return type is nullable string, meaning it can return a string or null.
     *
     * @return string|null The scopes of the application or null if not set
     */
    public function getScopes(): ?string;

    /**
     * Set the scopes
     *
     * This method is used to set the scopes of the application.
     * It accepts a string as an argument.
     *
     * @param string $scopes The scopes of the application
     * @return AppInterface Returns the current instance of the class to allow method chaining
     */
    public function setScopes(string $scopes): AppInterface;

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * @param string $createdAt
     * @return AppInterface
     */
    public function setCreatedAt(string $createdAt): AppInterface;

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @param string $updatedAt
     * @return AppInterface
     */
    public function setUpdatedAt(string $updatedAt): AppInterface;
}