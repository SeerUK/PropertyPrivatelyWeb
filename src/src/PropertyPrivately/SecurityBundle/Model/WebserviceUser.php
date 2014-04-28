<?php

/**
 * Property Privately Web
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Webservice User Model
 */
class WebserviceUser implements UserInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $token;

    /**
     * @var array
     */
    private $roles;

    /**
     * Cosntructor
     */
    public function __construct()
    {
        $this->roles = array();
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface::getUsername()
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @see UserInterface::getPassword()
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface::getRoles()
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @see UserInterface::getSalt()
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * @see UserInterface::eraseCredentials()
     */
    public function eraseCredentials()
    {
    }
}
