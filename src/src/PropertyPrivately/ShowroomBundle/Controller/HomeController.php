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

/**
 * Home Controller
 */
class HomeController extends Controller
{
    public function homeAction()
    {
        return $this->render('PropertyPrivatelyShowroomBundle:Home:index.html.twig');
    }
}
