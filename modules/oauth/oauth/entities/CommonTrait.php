<?php

namespace app\modules\oauth\oauth\entities;

use League\OAuth2\Server\Entities\{ClientEntityInterface, ScopeEntityInterface};
use app\modules\oauth\oauth\entities\ClientEntity;
use app\modules\oauth\oauth\repositories\ScopeRepository;


trait CommonTrait
{
    /**
     * @var ScopeEntityInterface[]
     */
    protected $scopesNewHere = [];

    /**
     * Get the token's identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * Set the token's identifier.
     *
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->id = $identifier;
    }

    /**
     * Get the token's expiry date time.
     *
     * @return \DateTimeImmutable
     */
    public function getExpiryDateTime()
    {
        return $this->expires_at;
    }

    /**
     * Set the date time when the token expires.
     *
     * @param DateTimeImmutable $dateTime
     */
    public function setExpiryDateTime(\DateTimeImmutable $dateTime)
    {
        $this->expires_at = $dateTime;
    }

    /**
     * Get the token user's identifier.
     *
     * @return string|int|null
     */
    public function getUserIdentifier()
    {
        return $this->user_id;
    }

    /**
     * Set the identifier of the user associated with the token.
     *
     * @param string|int|null $identifier The identifier of the user
     */
    public function setUserIdentifier($identifier)
    {
        $this->user_id = $identifier;
    }

    /**
     * Get the client that the token was issued to.
     *
     * @return ClientEntityInterface
     */
    public function getClient()
    {
        return ClientEntity::findOne($this->oauth_client_id);
    }
    /**
     * Set the client that the token was issued to.
     *
     * @param ClientEntityInterface $client
     */
    public function setClient(ClientEntityInterface $client)
    {
        $this->oauth_client_id = $client;
    }

    /**
     * Return an array of scopes associated with the token.
     *
     * @return ScopeEntityInterface[]
     */
    public function getScopes()
    {
        return array_values($this->scopesNewHere);
    }

    /**
     * Associate a scope with the token.
     *
     * @param ScopeEntityInterface $scope
     */
    public function addScope(ScopeEntityInterface $scope)
    {
        $this->scopesNewHere[$scope->getIdentifier()] = $scope;
    }
}
