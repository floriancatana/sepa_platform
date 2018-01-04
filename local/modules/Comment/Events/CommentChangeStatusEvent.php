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
 * Class CommentChangeStatusEvent
 * @package Comment\Events
 * @author Julien Chanséaume <jchanseaume@openstudio.fr>
 */
class CommentChangeStatusEvent extends CommentEvent
{
    /** @var int */
    protected $newStatus;

    /**
     * @return int
     */
    public function getNewStatus()
    {
        return $this->newStatus;
    }

    /**
     * @param int $newStatus
     */
    public function setNewStatus($newStatus)
    {
        $this->newStatus = $newStatus;

        return $this;
    }
}
