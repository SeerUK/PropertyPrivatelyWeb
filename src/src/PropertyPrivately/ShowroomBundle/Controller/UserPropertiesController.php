<?php

/**
 * Property Privately Web
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\ShowroomBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Guzzle\Http\Exception\RequestException;
use PropertyPrivately\ShowroomBundle\Form\Type\PropertyType;

/**
 * User Properties Controller
 */
class UserPropertiesController extends Controller
{
    public function viewAllAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('pp_showroom_homepage'));
        }

        $client      = $this->get('guzzle.client');
        $apiRequest  = $client->get('/user/properties');
        $apiResponse = $apiRequest->send();
        $body        = $apiResponse->json();

        $properties = array();
        if (isset($body['_embedded']['properties'])) {
            $properties = $body['_embedded']['properties'];
        }

        return $this->render('PropertyPrivatelyShowroomBundle:UserProperties:view-all.html.twig', array(
            'properties' => $properties
        ));
    }

    public function createAction()
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('pp_showroom_homepage'));
        }

        $request = $this->get('request');
        $form    = $this->createForm(new PropertyType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $client      = $this->get('guzzle.client');
                $apiRequest  = $client->post('/properties', ['Content-Type' => 'application/json'], json_encode($form->getData()));
                $apiResponse = $apiRequest->send();

                return $this->redirect($this->generateUrl('pp_showroom_user_properties_view_all'));
            } catch (RequestException $e) {
                // Do nothing with this, in this example...
            }
        }

        return $this->render('PropertyPrivatelyShowroomBundle:UserProperties:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('pp_showroom_homepage'));
        }

        $client     = $this->get('guzzle.client');
        $apiRequest = $client->get('/properties/' . $id);
        $apiResponse = $apiRequest->send();

        $request = $this->get('request');
        $form    = $this->createForm(new PropertyType(), $apiResponse->json());
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $apiRequest  = $client->patch('/properties/' . $id, ['Content-Type' => 'application/json'], json_encode($form->getData()));
                $apiResponse = $apiRequest->send();

                return $this->redirect($this->generateUrl('pp_showroom_user_properties_view_all'));
            } catch (RequestException $e) {
                // Do nothing with this, in this example...
            }
        }

        return $this->render('PropertyPrivatelyShowroomBundle:UserProperties:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id)
    {
        if ( ! $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('pp_showroom_homepage'));
        }

        try {
            $client      = $this->get('guzzle.client');
            $apiRequest  = $client->delete('/properties/' . $id);
            $apiResponse = $apiRequest->send();
        } catch (RequestException $e) {
                // Do nothing with this, in this example...
        }

        return $this->redirect($this->generateUrl('pp_showroom_user_properties_view_all'));
    }
}
