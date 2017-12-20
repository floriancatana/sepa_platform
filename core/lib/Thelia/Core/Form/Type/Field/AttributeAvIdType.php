<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Thelia\Core\Form\Type\Field;

use Thelia\Model\AttributeAvQuery;

/**
 * Class AttributeAvIdType
 * @package Thelia\Core\Form\Type\Field
 * @author Benjamin Perche <bperche@openstudio.fr>
 */
class AttributeAvIdType extends AbstractIdType
{
    /**
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     *
     * Get the model query to check
     */
    protected function getQuery()
    {
        return new AttributeAvQuery();
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "attribute_av";
    }
}
