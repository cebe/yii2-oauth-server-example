<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace app\modules\oauth\oauth\repositories;

use app\modules\oauth\oauth\entities\AccessTokenEntity;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use \DateTime;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $arr = [
            'oauth_client_id' => $clientEntity->getIdentifier(),
            'user_id' => $userIdentifier,
        ];

        $ate = new AccessTokenEntity($arr);
        foreach ($scopes as $aScope) {
            $ate->addScope($aScope);
        }
        return $ate;
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $at = new AccessTokenEntity([
            'id' => $accessTokenEntity->getIdentifier(),
            'user_id' => $accessTokenEntity->getUserIdentifier(),
            'oauth_client_id' => $accessTokenEntity->getClient()->getIdentifier(),
            'scopes' => implode(',', static::scopesToArray($accessTokenEntity->getScopes())),
            'is_revoked' => false,
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            'expires_at' => $accessTokenEntity->getExpiryDateTime()->format('Y-m-d H:i:s'),
        ]);
        $at->save();
        // events can be triggered here
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken($tokenId)
    {
        return AccessTokenEntity::find()
            ->where(['id' => $tokenId])
            ->update(['is_revoked' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId)
    {
        $at = AccessTokenEntity::find()
            ->where(['id' => $tokenId])->one();
        if (!$at) {
            return true;
        }
        return (bool) $at->is_revoked;
    }

    /**
     * Get an array of scope identifiers for storage.
     *
     * @param  array  $scopes
     * @return array
     */
    public static function scopesToArray(array $scopes)
    {
        return array_map(function ($scope) {
            return $scope->getIdentifier();
        }, $scopes);
    }
}
