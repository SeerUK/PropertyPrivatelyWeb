<?php

/**
 * Property Privately Web
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Authentication Controller
 */
class AuthenticationController extends Controller
{
    public function loginAction()
    {
        return $this->render('PropertyPrivatelySecurityBundle:Default:index.html.twig');
    }
}
