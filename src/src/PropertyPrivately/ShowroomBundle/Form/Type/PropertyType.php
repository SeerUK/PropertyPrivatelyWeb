<?php

/**
 * Property Privately Web
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\ShowroomBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

/**
 * Property Type
 */
class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array(
                        'min' => 10,
                        'max' => 50
                    )),
                    new Type(array(
                        'type'    => 'string',
                        'message' => 'Your title must be a {{ type }}.'
                    ))
                )
            ))
            ->add('description', 'textarea', array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array(
                        'min' => 50,
                        'max' => 1000
                    )),
                    new Type(array(
                        'type'    => 'string',
                        'message' => 'Your description must be a {{ type }}.'
                    ))
                )
            ))
            ->add('buildingName', 'text', array(
                'constraints' => array(
                    new Length(array(
                        'min' => 1,
                        'max' => 50
                    )),
                    new Type(array(
                        'type'    => 'string',
                        'message' => 'Your building name must be a {{ type }}.'
                    ))
                )
            ))
            ->add('address1', 'text', array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array(
                        'min' => 3,
                        'max' => 100
                    )),
                    new Type(array(
                        'type'    => 'string',
                        'message' => 'Your address line 1 must be a {{ type }}.'
                    ))
                )
            ))
            ->add('address2', 'text', array(
                'constraints' => array(
                    new Length(array(
                        'min' => 3,
                        'max' => 50
                    )),
                    new Type(array(
                        'type'    => 'string',
                        'message' => 'Your address line 2 must be a {{ type }}.'
                    ))
                )
            ))
            ->add('town', 'text', array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array(
                        'min' => 1,
                        'max' => 50
                    )),
                    new Type(array(
                        'type'    => 'string',
                        'message' => 'Your town must be a {{ type }}.'
                    ))
                )
            ))
            ->add('postcode', 'text', array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array(
                        'min' => 5,
                        'max' => 7
                    )),
                    new Type(array(
                        'type'    => 'string',
                        'message' => 'Your postcode must be a {{ type }}.'
                    ))
                )
            ))
            ->add('submit', 'submit');
    }

    public function getName()
    {
        return 'pp_showroom_property';
    }
}
