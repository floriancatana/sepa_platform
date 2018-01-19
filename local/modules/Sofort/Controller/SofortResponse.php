<?php

/* * ********************************************************************************** */
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
/* 	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/* * ********************************************************************************** */

namespace Sofort\Controller;

use Exception;
use Sofort\Core\SofortLibTransactionData;
use Sofort\Sofort;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Log\Tlog;
use Thelia\Model\OrderQuery;
use Thelia\Model\OrderStatus;
use Thelia\Model\OrderStatusQuery;
use Thelia\Module\BasePaymentModuleController;
use const DS;
use const THELIA_LOG_DIR;

/**
 * Class SofortResponse
 * @package Sofort\Controller
 * @author Thelia <info@thelia.net>
 */
class SofortResponse extends BasePaymentModuleController {
    /* @var Tlog $log */

    protected static $logger;

    public function __construct() {
        //$this->logger = new SofortApiLogManager();
    }

    /**
     * @param $order_id
     * @return Response
     */
    public function ok($order_id) {
        //check for transaction_id in request body
        $content = $this->getRequest()->getContent();
        $transaction_id = "";
        if ($content != null) {
            $transaction_array = explode("transaction", $content);
            $transaction_id = $transaction_array[2];
            $transaction_id = substr($transaction_id, 1, strlen($transaction_id) - 3);
            $this->getLogger()->error("Sofort valid transaction-id content " . $transaction_id);
        }

        // check for transaction_id in database
        $timeout = 0;
        while ($timeout < 5 && $transaction_id == "") {
            $orderQuery = OrderQuery::create();
            $order = $orderQuery->findOneById($order_id);

            $transaction_id = $order->getTransactionRef();
            if ($transaction_id)
                $this->getLogger()->error("Sofort valid transaction_id ok() " . $transaction_id . " " . $content);
            else {
                $this->getLogger()->error("Sofort transaction_id is null for order " . $order_id . " - pausing for one second and trying again " . $timeout);
                sleep(1);
                $timeout += 1;
            }
        }

        if ($transaction_id) {
            $order = $this->checkorder($order_id, $transaction_id);

            /*
             * Set order status as paid
             */
            $event = new OrderEvent($order);
            $event->setStatus(OrderStatusQuery::getPaidStatus()->getId());
            $this->dispatch(TheliaEvents::ORDER_UPDATE_STATUS, $event);

            $this->redirectToSuccessPage($order_id);
        } else {
            $this->getLogger()->error("Sofort transaction_id is null, order contains no valid transaction_id after 5 retries");
        }

        $this->redirectToFailurePage($order_id, "failed to verify sofort transaction_id ");
    }

    /*
     * @param $order_id int
     * @return \Thelia\Core\HttpFoundation\Response
     */

    public function cancel($order_id) {
        $transaction_id = $this->extractTransactionId("Sofort-cancel", $order_id);

        try {
            $order = $this->checkorder($order_id, $transaction_id);
            $this->getLogger()->error("User canceled payment of order " . $order->getRef());

            $event = new OrderEvent($order);
            $event->setStatus(OrderStatusQuery::create()->findOneByCode(OrderStatus::CODE_CANCELED)->getId());
            $this->dispatch(TheliaEvents::ORDER_UPDATE_STATUS, $event);

            $message = $this->getTranslator()->trans("You canceled your payment", [], Sofort::DOMAIN);
        } catch (Exception $ex) {
            $this->getLogger()->error("Error occured while canceling order: " . $ex->getMessage());

            $message = $this->getTranslator()->trans(
                    "Unexpected error: %mesg", ['%mesg' => $ex->getMessage()], Sofort::DOMAIN
            );
        }

        $this->redirectToFailurePage($order_id, $message);
    }

    public function pending($order_id) {
        $transaction_id = $this->extractTransactionId("Sofort-pending", $order_id);

        if ($transaction_id) {
            //get order object
            $orderQuery = OrderQuery::create();
            $order = $orderQuery->findOneById($order_id);
            if ($order) {
                $order->setTransactionRef($transaction_id);
                $order->save();
            }
        }

        $this->getLogger()->error("Sofort-pending transaction_id " . $transaction_id . " order_id " . $order->getId());
    }

    public function loss($order_id) {
        $this->getLogger()->error("Sofort-loss " . $order_id . " request " . implode(" ", $this->getRequest()->request->all()));
    }

    public function received($order_id) {
        $this->getLogger()->error("Sofort-received " . $order_id . " request " . implode(" ", $this->getRequest()->request->all()));
    }

    public function refunded($order_id) {
        $this->getLogger()->error("Sofort-refunded " . $order_id . " request " . implode(" ", $this->getRequest()->request->all()));
    }

    public function untraceable($order_id) {
        $this->getLogger()->error("Sofort-untraceable " . $order_id . " request " . implode(" ", $this->getRequest()->request->all()));
    }

    private function extractTransactionId($notification, $order_id) {
        $content = $this->getRequest()->getContent();
        $this->getLogger()->error($notification . " " . $order_id . " request " . $content);
        $transaction_id = "";
        if ($content == null) {
            $this->getLogger()->error($notification . " content is null");
        } else {
            //$this->getLogger()->info("")
            $transaction_array = explode("transaction", $content);
            $transaction_id = $transaction_array[1];
            $transaction_id = substr($transaction_id, 1, strlen($transaction_id) - 3);

            if (!$transaction_id) {
                $this->getLogger()->error("Sofort transaction_id is null, order contains no valid transaction_id");
            }
        }
        return $transaction_id;
    }

    /*
     * @param $order_id int
     * @param &$token string|null
     * @throws \Exception
     * @return \Thelia\Model\Order
     */

    public function checkorder($order_id, $transaction_id) {
// thelia
        if (null === $order = OrderQuery::create()->findPk($order_id)) {
            $this->getLogger()->error("Invalid order ID. This order doesn't exists.");

            throw new Exception(
            $this->getTranslator()->trans(
                    "Invalid order ID. This order doesn't exists or doesn't belong to you.", [], Sofort::DOMAIN
            )
            );
        }
// sofort
        if ($transaction_id) {
            $configkey = Sofort::getConfigValue("key");

            $SofortLibTransactionData = new SofortLibTransactionData($configkey);

            // If SofortLib_Notification returns a transaction_id:
            $SofortLibTransactionData->addTransaction($transaction_id);

            // By default without setter Api version 1.0 will be used due to backward compatibility, please
            // set ApiVersion to latest version. Please note that the response might have a different structure and values
            // For more details please see our Api documentation on https://www.sofort.com/integrationCenter-ger-DE/integration/API-SDK/
            $SofortLibTransactionData->setApiVersion('2.0');

            $SofortLibTransactionData->sendRequest();

            $output = array();
            $methods = array(
                'getAmount' => '',
                'getAmountRefunded' => '',
                'getCount' => '',
                'getPaymentMethod' => '',
                'getConsumerProtection' => '',
                'getStatus' => '',
                'getStatusReason' => '',
                'getStatusModifiedTime' => '',
                'getLanguageCode' => '',
                'getCurrency' => '',
                'getTransaction' => '',
                'getReason' => array(0, 0),
                'getUserVariable' => 0,
                'getTime' => '',
                'getProjectId' => '',
                'getRecipientHolder' => '',
                'getRecipientAccountNumber' => '',
                'getRecipientBankCode' => '',
                'getRecipientCountryCode' => '',
                'getRecipientBankName' => '',
                'getRecipientBic' => '',
                'getRecipientIban' => '',
                'getSenderHolder' => '',
                'getSenderAccountNumber' => '',
                'getSenderBankCode' => '',
                'getSenderCountryCode' => '',
                'getSenderBankName' => '',
                'getSenderBic' => '',
                'getSenderIban' => '',
            );

            foreach ($methods as $method => $params) {
                if (count($params) == 2) {
                    $output[] = $method . ': ' . $SofortLibTransactionData->$method($params[0], $params[1]);
                } else if ($params !== '') {
                    $output[] = $method . ': ' . $SofortLibTransactionData->$method($params);
                } else {
                    $output[] = $method . ': ' . $SofortLibTransactionData->$method();
                }
            }

            if ($SofortLibTransactionData->isError()) {
                $SofortLibTransactionData->getError();
                $this->getLogger()->error("Sofort response transaction_data error " . $SofortLibTransactionData->getError());
                throw new Exception(
                $this->getTranslator()->trans(
                        "Could not verify sofort transaction. Error: " . $SofortLibTransactionData->getError(), [], Sofort::DOMAIN
                )
                );
            } else
                $this->getLogger()->error("Sofort response transaction successful for id: " . $transaction_id); //." ".implode('<br />', $output));
        }
        return $order;
    }

    /**
     * Return a module identifier used to calculate the name of the log file,
     * and in the log messages.
     *
     * @return string the module code
     */
    protected function getModuleCode() {
        return "Sofort";
    }

    public function getLogger() {

        if (self::$logger == null) {
            self::$logger = Tlog::getNewInstance();

            $logFilePath = THELIA_LOG_DIR . DS . "log-sofort-payment.txt";

            self::$logger->setPrefix("#LEVEL: #DATE #HOUR: ");
            self::$logger->setDestinations("\\Thelia\\Log\\Destination\\TlogDestinationRotatingFile");
            self::$logger->setConfig("\\Thelia\\Log\\Destination\\TlogDestinationRotatingFile", 0, $logFilePath);
            self::$logger->setLevel(Tlog::ERROR);
        }
        return self::$logger;
    }

}
