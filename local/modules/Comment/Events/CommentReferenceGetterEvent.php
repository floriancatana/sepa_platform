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

use Thelia\Core\Event\ActionEvent;

/**
 * Class CommentReferenceGetterEvent
 * @package Comment\Events
 * @author Julien Chanséaume <jchanseaume@openstudio.fr>
 */
class CommentReferenceGetterEvent extends ActionEvent
{
    /** @var string */
    protected $ref;
    /** @var int */
    protected $refId;
    /** @var string */
    protected $locale;
    /** @var mixed */
    protected $object;
    /** @var string */
    protected $typeTitle;
    /** @var string */
    protected $title;
    /** @var string */
    protected $viewUrl;
    /** @var string */
    protected $editUrl;

    public function __construct($ref, $refId, $locale)
    {
        $this->ref = $ref;
        $this->refId = $refId;
        $this->locale = $locale;
    }


    /**
     * @return string
     */
    public function getEditUrl()
    {
        return $this->editUrl;
    }

    /**
     * @param string $editUrl
     */
    public function setEditUrl($editUrl)
    {
        $this->editUrl = $editUrl;

        return $this;
    }

    /**
     * @return string
     */

    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * @param string $ref
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * @return int
     */
    public function getRefId()
    {
        return $this->refId;
    }

    /**
     * @param int $refId
     */
    public function setRefId($refId)
    {
        $this->refId = $refId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTypeTitle()
    {
        return $this->typeTitle;
    }

    /**
     * @param string $typeTitle
     * @return CommentReferenceGetterEvent
     */
    public function setTypeTitle($typeTitle)
    {
        $this->typeTitle = $typeTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getViewUrl()
    {
        return $this->viewUrl;
    }

    /**
     * @param string $viewUrl
     */
    public function setViewUrl($viewUrl)
    {
        $this->viewUrl = $viewUrl;

        return $this;
    }
}
