<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace app\modules\oauth\oauth\repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
// use OAuth2ServerExamples\Entities\ScopeEntity;
use app\modules\oauth\oauth\entities\ScopeEntity;

class ScopeRepository implements ScopeRepositoryInterface
{
    public $allScopes = [
        'basic' => [
            'description' => 'basic  for simple login'
        ],
        'email' => [
            'description' => 'Your email address and other basic info will be shared',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function getScopeEntityByIdentifier($scopeIdentifier)
    {
        if (\array_key_exists($scopeIdentifier, $this->allScopes) === false) {
            return;
        }

        $scope = new ScopeEntity();
        $scope->setIdentifier($scopeIdentifier);
        return $scope;
    }

    /**
     * {@inheritdoc}
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        // Example of programatically modifying the final scope of the access token
        // if ((int) $userIdentifier === 1) {
        //     $scope = new ScopeEntity();
        //     $scope->setIdentifier('email');
        //     $scopes[] = $scope;
        // }

        if (!$scopes) { // case when scope is present in request query string but empty
            $scopes[] = $this->getScopeEntityByIdentifier('email');
        }

        return $scopes; // currently there is no need to add/remove any scopes

        // unset($scopes);
        // $scopes = [$this->getScopeEntityByIdentifier('basic'), $this->getScopeEntityByIdentifier('email')]; // currently only basic is required
        // return $scopes;
    }
}
