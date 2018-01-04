<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia                                                                       */
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
/*      along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace Comment\Controller\Front;

use Comment\Comment;
use Comment\Events\CommentAbuseEvent;
use Comment\Events\CommentCreateEvent;
use Comment\Events\CommentDefinitionEvent;
use Comment\Events\CommentDeleteEvent;
use Comment\Events\CommentEvents;
use Comment\Exception\InvalidDefinitionException;
use Comment\Model\CommentQuery;
use Exception;
use Thelia\Controller\Front\BaseFrontController;

/**
 * Class CommentController
 * @package Comment\Controller\Admin
 * @author Michaël Espeche <michael.espeche@gmail.com>
 * @author Julien Chanséaume <jchanseaume@openstudio.fr>
 */
class CommentController extends BaseFrontController
{
    const DEFAULT_VISIBLE = 0;

    protected $useFallbackTemplate = true;

    public function getAction()
    {
        // only ajax
        $this->checkXmlHttpRequest();

        $definition = null;

        try {
            $definition = $this->getDefinition(
                $this->getRequest()->get('ref', null),
                $this->getRequest()->get('ref_id', null)
            );
        } catch (InvalidDefinitionException $ex) {
            if ($ex->isSilent()) {
                // Comment not authorized on this resource
                $this->accessDenied();
            }
        }

        return $this->render(
            "ajax-comments",
            [
                'ref' => $this->getRequest()->get('ref'),
                'ref_id' => $this->getRequest()->get('ref_id'),
                'start' => $this->getRequest()->get('start', 0),
                'count' => $this->getRequest()->get('count', 10),
            ]
        );
    }

    public function abuseAction()
    {
        // only ajax
        $this->checkXmlHttpRequest();

        $abuseForm = $this->createForm('comment.abuse.form');

        $messageData = [
            "success" => false
        ];

        try {
            $form = $this->validateForm($abuseForm);

            $comment_id = $form->get("id")->getData();

            $event = new CommentAbuseEvent();
            $event->setId($comment_id);

            $this->dispatch(CommentEvents::COMMENT_ABUSE, $event);

            $messageData["success"] = true;
            $messageData["message"] = $this->getTranslator()->trans(
                "Your request has been registered. Thank you.",
                [],
                Comment::MESSAGE_DOMAIN
            );
        } catch (\Exception $ex) {
            // all errors
            $messageData["message"] = $this->getTranslator()->trans(
                "Your request could not be validated. Try it later",
                [],
                Comment::MESSAGE_DOMAIN
            );
        }

        return $this->jsonResponse(json_encode($messageData));
    }


    public function createAction()
    {
        // only ajax
        $this->checkXmlHttpRequest();

        $responseData = [];
        /** @var CommentDefinitionEvent $definition */
        $definition = null;

        try {
            $params = $this->getRequest()->get('admin_add_comment');
            $definition = $this->getDefinition(
                $params['ref'],
                $params['ref_id']
            );
        } catch (InvalidDefinitionException $ex) {
            if ($ex->isSilent()) {
                // Comment not authorized on this resource
                $this->accessDenied();
            } else {
                // The customer does not have minimum requirement to post comment
                $responseData = [
                    "success" => false,
                    "messages" => [$ex->getMessage()]
                ];
                return $this->jsonResponse(json_encode($responseData));
            }
        }

        $customer = $definition->getCustomer();

        $validationGroups = [
            'Default'
        ];

        if (null === $customer) {
            $validationGroups[] = 'anonymous';
        }
        if (!$definition->hasRating()) {
            $validationGroups[] = 'rating';
        }

        $commentForm = $this->createForm(
            'comment.add.form',
            'form',
            [],
            ['validation_groups' => $validationGroups]
        );

        try {
            $form = $this->validateForm($commentForm);

            $event = new CommentCreateEvent();
            $event->bindForm($form);

            $event->setVerified($definition->isVerified());

            if (null !== $customer) {
                $event->setCustomerId($customer->getId());
            }

            if (!$definition->getConfig()['moderate']) {
                $event->setStatus(\Comment\Model\Comment::ACCEPTED);
            } else {
                $event->setStatus(\Comment\Model\Comment::PENDING);
            }

            $event->setLocale($this->getRequest()->getLocale());

            $this->dispatch(CommentEvents::COMMENT_CREATE, $event);

            if (null !== $event->getComment()) {
                $responseData = [
                    "success" => true,
                    "messages" => [
                        $this->getTranslator()->trans(
                            "Thank you for submitting your comment.",
                            [],
                            Comment::MESSAGE_DOMAIN
                        ),
                    ]
                ];
                if ($definition->getConfig()['moderate']) {
                    $responseData['messages'][] = $this->getTranslator()->trans(
                        "Your comment will be put online once verified.",
                        [],
                        Comment::MESSAGE_DOMAIN
                    );
                }
            } else {
                $responseData = [
                    "success" => false,
                    "messages" => [
                        $this->getTranslator()->trans(
                            "Sorry, an unknown error occurred. Please try again.",
                            [],
                            Comment::MESSAGE_DOMAIN
                        )
                    ]
                ];
            }
        } catch (Exception $ex) {
            $responseData = [
                "success" => false,
                "messages" => [$ex->getMessage()]
            ];
        }

        return $this->jsonResponse(json_encode($responseData));
    }

    protected function getDefinition($ref, $refId)
    {
        $eventDefinition = new CommentDefinitionEvent();
        $eventDefinition
            ->setRef($ref)
            ->setRefId($refId)
            ->setCustomer($this->getSecurityContext()->getCustomerUser())
            ->setConfig(Comment::getConfig());

        $this->dispatch(
            CommentEvents::COMMENT_GET_DEFINITION,
            $eventDefinition
        );

        return $eventDefinition;
    }

    public function deleteAction($commentId)
    {
        // only ajax
        $this->checkXmlHttpRequest();

        $messageData = [
            "success" => false
        ];

        try {
            $customer = $this->getSecurityContext()->getCustomerUser();

            // find the comment
            $comment = CommentQuery::create()->findPk($commentId);

            if (null !== $comment) {
                if ($comment->getCustomerId() === $customer->getId()) {
                    $event = new CommentDeleteEvent();
                    $event->setId($commentId);

                    $this->dispatch(CommentEvents::COMMENT_DELETE, $event);

                    if (null !== $event->getComment()) {
                        $messageData["success"] = true;
                        $messageData["message"] = $this->getTranslator()->trans(
                            "Your comment has been deleted.",
                            [],
                            Comment::MESSAGE_DOMAIN
                        );
                    }
                }
            }
        } catch (\Exception $ex) {
            ;
        }

        if (false === $messageData["success"]) {
            $messageData["message"] = $this->getTranslator()->trans(
                "Comment could not be removed. Please try later.",
                [],
                Comment::MESSAGE_DOMAIN
            );
        }

        return $this->jsonResponse(json_encode($messageData));
    }
}
