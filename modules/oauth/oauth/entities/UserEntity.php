<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace app\modules\oauth\oauth\entities;

use League\OAuth2\Server\Entities\UserEntityInterface;
use Da\User\Model\User;

class UserEntity extends User implements UserEntityInterface
{
    /**
     * Return the user's identifier.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->getId();
        // return \Yii::$app->user->identity->id;
        // return 1;
    }
}
