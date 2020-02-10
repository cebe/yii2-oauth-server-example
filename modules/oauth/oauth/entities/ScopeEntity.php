<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace app\modules\oauth\oauth\entities;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\ScopeTrait;

class ScopeEntity implements ScopeEntityInterface
{
    // use EntityTrait, ScopeTrait;

    // public $id;

    /**
     * Get the scope's identifier.
     *
     * @return string
     */
    // public function getIdentifier()
    // {
    //     return $this->id;
    //     // return 'basic'; // currently only one scope(basic for auth) is in requirements
    // }

    /**
     * @var string
     */
    protected $id;

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->id = $identifier;
    }

    /**
     * Serialize the object to the scopes string identifier when using json_encode().
     *
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}
