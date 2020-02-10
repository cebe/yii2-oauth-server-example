<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace app\modules\oauth\oauth\entities;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use app\modules\oauth\models\Client;

class ClientEntity extends Client implements ClientEntityInterface
{
    // use EntityTrait, ClientTrait;

    // public function setName($name)
    // {
    //     $this->name = $name;
    // }

    // public function setRedirectUri($uri)
    // {
    //     $this->redirect_uri = $uri;
    // }

    // public function setConfidential()
    // {
    //     // TODO check
    //     $this->isConfidential = false;
    // }

    /**
     * Get the client's identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the client's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the registered redirect URI (as a string).
     *
     * Alternatively return an indexed array of redirect URIs.
     *
     * @return string|string[]
     */
    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }

    /**
     * Returns true if the client is confidential.
     *
     * @return bool
     */
    public function isConfidential()
    {
        return false; // Currently we do not have any such requirments
    }
}
