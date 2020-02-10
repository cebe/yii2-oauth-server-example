<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace app\modules\oauth\oauth\entities;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use app\modules\oauth\models\AuthCode;

class AuthCodeEntity extends AuthCode implements AuthCodeEntityInterface
{
    // use EntityTrait, TokenEntityTrait, AuthCodeTrait;
    use CommonTrait;

    // use EntityTrait, TokenEntityTrait, AuthCodeTrait;
    /**
     * @return string|null
     */
    // public function getRedirectUri()
    // {
    //     $this->getClient()->redirect_uri;
    // }

    /**
     * @param string $uri
     */
    // public function setRedirectUri($uri)
    // {
    //     $this->getClient()->redirect_uri = $uri;
    // }

    /**
     * @var null|string
     */
    protected $redirectUri;

    /**
     * @return string|null
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param string $uri
     */
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }
}
