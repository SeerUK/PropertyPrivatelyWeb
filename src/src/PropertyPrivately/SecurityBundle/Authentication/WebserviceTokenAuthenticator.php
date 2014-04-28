<?php

/**
 * Property Privately Web
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Authentication;

use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use PropertyPrivately\SecurityBundle\Security\User\WebserviceTokenUserProvider;

/**
 * Webservice Token Authenticator
 */
class WebserviceTokenAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @var WebserviceTokenUserProvider
     */
    protected $provider;

    /**
     * Constructor
     *
     * @param WebserviceTokenUserProvider $provider
     */
    public function __construct(WebserviceTokenUserProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Create token to authenticate
     *
     * @param  Request $request
     * @param  string  $providerKey
     * @return PreAuthenticatedToken
     *
     * @throws BadCredentialsException
     */
    public function createToken(Request $request, $providerKey)
    {
        $session = $request->getSession();

        if ( ! $session->has('API-User-Token')) {
            throw new BadCredentialsException('No API user token found.');
        }

        return new PreAuthenticatedToken(
            'anon.',
            [
                'apiUserToken' => $session->get('API-User-Token')
            ],
            $providerKey
        );
    }

    /**
     * Authenticate a token
     *
     * @param  TokenInterface        $token
     * @param  UserProviderInterface $provider
     * @param  string                $providerKey
     * @return PreAuthenticatedToken
     *
     * @throws AuthenticationException
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $provider, $providerKey)
    {
        $credentials  = $token->getCredentials();
        $apiUserToken = $credentials['apiUserToken'];

        $userinfo = $this->provider->getUserInfoForApiUserToken($apiUserToken);

        if ( ! $userinfo) {
            throw new AuthenticationException(
                sprintf('User not found, invalid token: "%s".', $apiUserToken)
            );
        }

        $roles = array();
        foreach ($userinfo['roles'] as $role) {
            $roles[] = $role['role'];
        }

        $user = $this->provider->loadUserByUsername($userinfo['username']);
        $user->setToken($apiUserToken);
        $user->setRoles($roles);

        return new PreAuthenticatedToken(
            $user,
            $apiUserToken,
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * Supports this token?
     *
     * @param  TokenInterface $token
     * @param  string         $providerKey
     * @return boolean
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
}
