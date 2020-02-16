<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace app\modules\oauth\oauth\entities;

use app\modules\oauth\oauth\repositories\AccessTokenRepository;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use League\OAuth2\Server\CryptKey;
use app\modules\oauth\oauth\entities\ClientEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use app\modules\oauth\models\AccessToken;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

class AccessTokenEntity extends AccessToken implements AccessTokenEntityInterface
{
    use CommonTrait;

    /**
     * @var \League\OAuth2\Server\CryptKey
     */
    protected $privateKey;

    /**
     * Set the private key
     *
     * @param \League\OAuth2\Server\CryptKey $key
     */
    public function setPrivateKey(CryptKey $key)
    {
        $this->privateKey = $key;
    }

    /**
     * Generate a string representation from the access token
     */
    public function __toString()
    {
        return (string) $this->convertToJWT($this->privateKey);
    }

    /**
     * Generate a JWT from the access token
     *
     * @param CryptKey $privateKey
     *
     * @return Token
     */
    private function convertToJWT(CryptKey $privateKey)
    {
        return (new Builder())
            ->setAudience($this->getClient()->getIdentifier())
            ->setId($this->getIdentifier())
            ->setIssuedAt(time())
            ->setNotBefore(time())
            ->setExpiration($this->getExpiryDateTime()->getTimestamp())
            ->setSubject((string) $this->getUserIdentifier())
            ->set('scopes', $this->getScopes())
            ->sign(new Sha256(), new Key($privateKey->getKeyPath(), $privateKey->getPassPhrase()))
            ->withHeader('my-custom-header', json_encode(['my-custom-header-value'=>'dablu', 'foo' => 'bar'])) // add custom data here
            ->getToken();
    }

    /**
     * Check Token
     * @param  int|string $user_id
     * @param  app\modules\oauth\oauth\entities\ClientEntity $client
     * @param  app\modules\oauth\oauth\entities\ScopeEntity[] $scopes
     * @return $this|null
     */
    public static function checkToken($user_id, $client, $scopes)
    {
        return static::find()->where([
            'user_id' => $user_id,
            'oauth_client_id' => $client->getIdentifier(),
            'scopes' => implode(',', AccessTokenRepository::scopesToArray($scopes)),
            'is_revoked' => 0,
        ])->andWhere(
            ['>=', 'expires_at', date('Y-m-d H:i:s')]
        )->one();
    }
}
