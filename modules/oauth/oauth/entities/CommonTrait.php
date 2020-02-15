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
    protected $scopesHere = [];
    protected $scopesNewHere = [];

    // protected $userIdentifier;

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
        // return $this->userIdentifier;
    }

    /**
     * Set the identifier of the user associated with the token.
     *
     * @param string|int|null $identifier The identifier of the user
     */
    public function setUserIdentifier($identifier)
    {
        $this->user_id = $identifier;
        // $this->userIdentifier = $identifier;
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
        // in db we will store comma separated
        // $this->convertScopesStrToArr();
        // $scopes = [];
        // $sr = new ScopeRepository;
        // if ( is_string($this->scopes) && !empty(trim($this->scopes))) {
        //     foreach (explode(',', $this->scopes) as $aScope) {
        //         $scopes[] = $sr->getScopeEntityByIdentifier($aScope);
        //     }
        // } elseif (is_array($this->scopes)) {
        //     return $this->scopes;
        // }
        // // return $this->scopes;
        // return $scopes;
    }

    /**
     * Associate a scope with the token.
     *
     * @param ScopeEntityInterface $scope
     */
    public function addScope(ScopeEntityInterface $scope)
    {
        $this->scopesNewHere[$scope->getIdentifier()] = $scope;
        // if ($this->scopesNewHere) {
        //     $this->scopes = implode(',', array_keys($this->scopesNewHere));
        // }

        // $this->convertScopesStrToArr();
        // $this->scopes[$scope->getIdentifier()] = $scope;
        // $this->getScopes();
        // $scopeId = $scope->getIdentifier();
        // $allScopesHere = [];
        // // $sr = new ScopeRepository;
        // $isIncluded = false;
        // if (!empty(trim($this->scopes))) {
        //     foreach (explode(',', $this->scopes) as $aScope) {
        //         if ($aScope === $scopeId) {
        //             $isIncluded = true;
        //         }
        //         $allScopesHere[] = $aScope;
        //     }
        // }

        // // $scopes[] = $scope;
        // if ($isIncluded === false) {
        //     $allScopesHere[] = $scopeId;
        // }
        // if (count($allScopesHere) > 0) {
        //     $this->scopes = implode(',', $allScopesHere);
        // }
    }

    public function convertScopesArrToStr()
    {
        if (is_array($this->scopes)) {
            $scopes = [];
            foreach ($this->scopes as $key => $aScope) {
                $scopes[] = $aScope->getIdentifier();
            }
            $this->scopes = implode(',', $scopes);
        }
    }

    public function convertScopesStrToArr()
    {
        if (is_string($this->scopes)) {
            $sr = new ScopeRepository;
            $scopes = [];
            foreach (explode(',', $this->scopes) as $aScope) {
                $scopes[] = $sr->getScopeEntityByIdentifier($aScope);
            }
            $this->scopes = $scopes;
        }
    }

    // public function addScope(ScopeEntityInterface $scope)
    // {
    //     $this->scopesHere[$scope->getIdentifier()] = $scope;
    // }

    // public function getScopes()
    // {
    //     return array_values($this->scopesHere);
    // }
}
