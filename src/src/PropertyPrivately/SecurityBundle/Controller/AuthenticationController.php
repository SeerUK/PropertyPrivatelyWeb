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
use Guzzle\Http\Exception\RequestException;

/**
 * Authentication Controller
 */
class AuthenticationController extends Controller
{
    public function loginAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            return $this->redirect($this->generateUrl('pp_showroom_homepage'));
        }

        $request = $this->get('request');
        $errors  = array();

        if ('POST' === $request->getMethod()) {
            if ( ! $request->request->has('username') || ! $request->request->has('password')) {
                return $this->redirect($this->generateUrl('pp_security_login'));
            }

            try {
                $client   = $this->get('guzzle.client');
                $tokenReq = $client->post('/auth', ['Content-Type' => 'application/json'], json_encode([
                    'username' => $request->request->get('username'),
                    'password' => $request->request->get('password')
                ]));

                $tokenRes = $tokenReq->send();

                $session = $request->getSession();
                $session->set('API-User-Token', $tokenRes->json()['token']);

                return $this->redirect($this->generateUrl('pp_showroom_homepage'));
            } catch (RequestException $e) {
                $response = $e->getResponse();

                if (401 === $response->getStatusCode()) {
                    $errors[] = 'Bad credentials.';
                } else {
                    throw $e;
                }
            }
        }

        return $this->render('PropertyPrivatelySecurityBundle:Login:index.html.twig', array(
            'errors' => $errors
        ));
    }

    public function logoutAction()
    {
        $request = $this->get('request');
        $session = $request->getSession();

        $session->remove('API-User-Token');

        return $this->redirect($this->generateUrl('pp_showroom_homepage'));
    }
}
