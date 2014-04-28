<?php

/**
 * Property Privately Web
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Security\User;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;
use PropertyPrivately\SecurityBundle\Model\WebserviceUser;

/**
 * Webservice Token User Provider
 */
class WebserviceTokenUserProvider implements UserProviderInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Request
     */
    private $request;

    /**
     * Constructor
     *
     * @param Client $client
     */
    public function __construct(Client $client, RequestStack $requestStack)
    {
        $this->client  = $client;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * Get a user's username by a user token
     *
     * @param  string $apiUserToken
     * @return string
     */
    public function getUserInfoForApiUserToken($apiUserToken)
    {
        $request  = $this->client->get('/user', ['X-API-User-Token' => $apiUserToken]);

        try {
            $response = $request->send();
        } catch (RequestException $e) {
            return false;
        }

        return $response->json();
    }


    public function loadUserByUsername($username)
    {
        $user = new WebserviceUser();
        $user->setUsername($username);

        return $user;
    }

    /**
     * Must not be implemented
     *
     * @param  UserInterface $user
     *
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * Returns if the given class is supported by this provider
     *
     * @param  string $class
     * @return boolean
     */
    public function supportsClass($class)
    {
        return 'PropertyPrivately\SecurityBundle\Model\User' === $class;
    }
}
