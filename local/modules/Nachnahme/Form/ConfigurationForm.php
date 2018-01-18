<?php

/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace Nachnahme\Form;

use Nachnahme\Nachnahme;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

/**
 * Class ConfigurationForm
 * @package Nachnahme\Form
 * @author Thelia <info@thelia.net>
 */
class ConfigurationForm extends BaseForm
{
    protected function trans($str, $params = [])
    {
        return Translator::getInstance()->trans($str, $params, Nachnahme::MESSAGE_DOMAIN);
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'payable_to',
                'text',
                [
                    'constraints' => [ new NotBlank() ],
                    'label'       => $this->trans('Nachnahme is payable to: '),
                    'label_attr' => [
                        'for' => 'payable_to',
                        'help' => $this->trans('The name to which the nachnahme shoud be payable to.')
                    ],
                    'attr' => [
                        'rows'        => 10,
                        'placeholder' => $this->trans('Pay nachnahme to')
                    ]
                ]
            )
            ->add(
                'instructions',
                'textarea',
                [
                    'constraints' => [],
                    'required'    => false,
                    'label'       => $this->trans('Nachnahme instructions'),
                    'label_attr' => [
                        'for' => 'namefield',
                        'help' => $this->trans('Please enter here the payment by nachnahme instructions')
                    ],
                    'attr' => [
                        'rows'        => 10,
                        'placeholder' => $this->trans('Payment instruction')
                    ]
                ]
            )
         ;
    }

    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return 'nachnahme_configuration_instructions';
    }
}