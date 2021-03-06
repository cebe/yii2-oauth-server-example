<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace app\modules\oauth\oauth\repositories;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use app\modules\oauth\oauth\entities\AuthCodeEntity;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getNewAuthCode()
    {
        return new AuthCodeEntity;
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $ac = new AuthCodeEntity([
            'id' => $authCodeEntity->getIdentifier(),
            'user_id' => $authCodeEntity->getUserIdentifier(),
            'oauth_client_id' => $authCodeEntity->getClient()->getIdentifier(),
            'scopes' => implode(',', AccessTokenRepository::scopesToArray($authCodeEntity->getScopes())),
            'is_revoked' => false,
            'expires_at' => $authCodeEntity->getExpiryDateTime()->format('Y-m-d H:i:s'),
        ]);
        $ac->save();
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAuthCode($codeId)
    {
        $ace = AuthCodeEntity::find()
            ->where(['id' => $codeId])->one();
        return $ace->update(['is_revoked' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthCodeRevoked($codeId)
    {
        $ac = AuthCodeEntity::find()
            ->where(['id' => $codeId])->one();
        if (!$ac) {
            return true;
        }
        return (bool) $ac->is_revoked;
    }
}
