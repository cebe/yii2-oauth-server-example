<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace app\modules\oauth\oauth\repositories;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
// use OAuth2ServerExamples\Entities\ClientEntity;
use app\modules\oauth\oauth\entities\ClientEntity;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier)
    {
        return ClientEntity::findOne($clientIdentifier);
    }

    /**
     * {@inheritdoc}
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $client = ClientEntity::findOne($clientIdentifier);
        if (!$client ||
            $client->secret !== $clientSecret ||
            $grantType !== 'code') { // currently the requirement is for auth code grant only
            return false;
        }

        return true;
    }
}
