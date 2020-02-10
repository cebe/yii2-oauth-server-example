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
    // const CLIENT_NAME = 'My Awesome App';
    // const REDIRECT_URI = 'http://foo/bar';

    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier)
    {
        return ClientEntity::findOne($clientIdentifier);
        // if (!$client) {
        //     return;
        // }
        // return $client;
        // $client = new ClientEntity();

        // $client->setIdentifier($clientIdentifier);
        // $client->setName(self::CLIENT_NAME);
        // $client->setRedirectUri(self::REDIRECT_URI);
        // $client->setConfidential();

        // return $client;

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

        // $clients = [
        //     'myawesomeapp' => [
        //         'secret'          => \password_hash('abc123', PASSWORD_BCRYPT),
        //         'name'            => self::CLIENT_NAME,
        //         'redirect_uri'    => self::REDIRECT_URI,
        //         'is_confidential' => true,
        //     ],
        // ];

        // // Check if client is registered
        // if (\array_key_exists($clientIdentifier, $clients) === false) {
        //     return;
        // }

        // if (
        //     $clients[$clientIdentifier]['is_confidential'] === true
        //     && \password_verify($clientSecret, $clients[$clientIdentifier]['secret']) === false
        // ) {
        //     return;
        // }
    }
}
