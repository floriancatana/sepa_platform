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


namespace Comment\Events;


/**
 * Class CommentEvents
 * @package Comment\Events
 * @author Julien Chanséaume <jchanseaume@openstudio.fr>
 */
class CommentEvents
{
    const COMMENT_CREATE = "action.comment.create";
    const COMMENT_UPDATE = "action.comment.update";
    const COMMENT_DELETE = "action.comment.delete";
    const COMMENT_STATUS_UPDATE = "action.comment.status.update";
    const COMMENT_ABUSE = "action.comment.abuse";
    const COMMENT_RATING_COMPUTE = "action.comment.rating.compute";
    const COMMENT_REFERENCE_GETTER = "action.comment.reference.getter";
    const COMMENT_CUSTOMER_DEMAND = "action.comment.customer.demand";
    //
    const COMMENT_GET_DEFINITION = "action.comment.definition";
    const COMMENT_GET_DEFINITION_PRODUCT = "action.comment.definition.product";
    const COMMENT_GET_DEFINITION_CONTENT = "action.comment.definition.content";
}
