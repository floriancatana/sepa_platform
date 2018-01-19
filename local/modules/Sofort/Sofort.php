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

namespace Sofort;

use Propel\Runtime\Connection\ConnectionInterface;
use Sofort\Payment\Sofortueberweisung;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Thelia\Core\Translation\Translator;
use Thelia\Install\Database;
use Thelia\Log\Tlog;
use Thelia\Model\MessageQuery;
use Thelia\Model\ModuleImageQuery;
use Thelia\Model\Order;
use Thelia\Model\OrderQuery;
use Thelia\Module\AbstractPaymentModule;
use Thelia\Tools\URL;
use const DS;
use const THELIA_LOG_DIR;

/**
 * Class Sofort
 * @package Sofort
 * @author Thelia <info@thelia.net>
 */
class Sofort extends AbstractPaymentModule {

    const DOMAIN = 'sofort';

    /* @var Tlog $log */

    protected static $logger;

    /**
     * The confirmation message identifier
     */
    const CONFIRMATION_MESSAGE_NAME = 'sofort_payment_confirmation';

    public function pay(Order $order) {
        $orderId = $order->getId();

        /** @var Router $router */
        $router = $this->getContainer()->get('router.sofort');

        $successUrl = URL::getInstance()->absoluteUrl(
                $router->generate('sofort.ok', ['order_id' => $order->getId()])
        );

        $cancelUrl = URL::getInstance()->absoluteUrl(
                $router->generate('sofort.cancel', ['order_id' => $order->getId()])
        );

        $pendingUrl = URL::getInstance()->absoluteUrl(
                $router->generate('sofort.pending', ['order_id' => $order->getId()])
        );

        $lossUrl = URL::getInstance()->absoluteUrl(
                $router->generate('sofort.loss', ['order_id' => $order->getId()])
        );

        $receivedUrl = URL::getInstance()->absoluteUrl(
                $router->generate('sofort.received', ['order_id' => $order->getId()])
        );

        $refundedUrl = URL::getInstance()->absoluteUrl(
                $router->generate('sofort.refunded', ['order_id' => $order->getId()])
        );
        $untraceableUrl = URL::getInstance()->absoluteUrl(
                $router->generate('sofort.untraceable', ['order_id' => $order->getId()])
        );

        $order = OrderQuery::create()->findPk($orderId);

        /*
         * Store products into 2d array $products
         */
        $products = array(array());
        $products_amount = 0;
        $itemIndex = 0;

        foreach ($order->getOrderProducts() as $product) {
            if ($product !== null) {
                $amount = floatval($product->getWasInPromo() ? $product->getPromoPrice() : $product->getPrice());
                foreach ($product->getOrderProductTaxes() as $tax) {
                    $amount += $product->getWasInPromo() ? $tax->getPromoAmount() : $tax->getAmount();
                }
                $products_amount += $amount * $product->getQuantity();
                $products[0]["NAME" . $itemIndex] = urlencode($product->getTitle());
                $products[0]["AMT" . $itemIndex] = urlencode(round($amount, 2));
                $products[0]["QTY" . $itemIndex] = urlencode($product->getQuantity());
                $itemIndex ++;
            }
        }


        /*
         * Compute difference between products total and cart amount
         * -> get Coupons.
         */
        $delta = round($products_amount - $order->getTotalAmount($useless, false), 2);

        if ($delta > 0) {
            $products[0]["NAME" . $itemIndex] = Translator::getInstance()->trans("Discount");
            $products[0]["AMT" . $itemIndex] = - $delta;
            $products[0]["QTY" . $itemIndex] = 1;
        }
        $configkey = Sofort::getConfigValue('key', 'missing');
        $Sofortueberweisung = new Sofortueberweisung($configkey);
        $this->getLogger()->error("Sofort-pay order " . $order->getId() . " amount " . round($order->getTotalAmount(), 2) . " currency " . $order->getCurrency()->getCurrentTranslation()->getCurrency()->getCode());

        $Sofortueberweisung->setAmount(round($order->getTotalAmount(), 2));
        $Sofortueberweisung->setCurrencyCode($order->getCurrency()->getCurrentTranslation()->getCurrency()->getCode());

        $Sofortueberweisung->setReason('Bestellung', $order->getId());
        $Sofortueberweisung->setSuccessUrl($successUrl, true);

        $Sofortueberweisung->setAbortUrl($cancelUrl);

        $Sofortueberweisung->setNotificationUrl($pendingUrl, 'pending');
        $Sofortueberweisung->setNotificationUrl($lossUrl, 'loss');
        $Sofortueberweisung->setNotificationUrl($receivedUrl, 'received');
        $Sofortueberweisung->setNotificationUrl($refundedUrl, 'refunded');
        $Sofortueberweisung->setNotificationUrl($untraceableUrl, 'untraceable');

        $Sofortueberweisung->sendRequest();

        if ($Sofortueberweisung->isError()) {
            //SOFORT-API didn't accept the data
            $this->getLogger()->error("Sofort " . $Sofortueberweisung->getError());
            return new RedirectResponse(
                    $this->getPaymentFailurePageUrl(
                            $order->getId(), Translator::getInstance()->trans(
                                    "Sorry, something did not worked with Sofort. %sofortError Please try again, or use another payment type.", array("%sofortError" => $Sofortueberweisung->getError()), self::DOMAIN
                            )
                    )
            );
        } else {
            //buyer must be redirected to $paymentUrl else payment cannot be successfully completed!
            $paymentUrl = $Sofortueberweisung->getPaymentUrl();
            //header('Location: '.$paymentUrl);
            return new RedirectResponse(
                    $paymentUrl
            );
        }

        // Failure !
        return new RedirectResponse(
                $this->getPaymentFailurePageUrl(
                        $order->getId(), Translator::getInstance()->trans(
                                "Sorry, something did not worked with Sofort. Please try again, or use another payment type", [], self::DOMAIN
                        )
                )
        );
    }

    //TODO - future 
    public function isValidPayment() {
        $valid = true; //false;
        // Check if total order amount is within the module's limits
        /* $order_total = $this->getCurrentOrderTotalAmount();

          $min_amount = Sofort::getConfigValue('minimum_amount', 0);
          $max_amount = Sofort::getConfigValue('maximum_amount', 0);

          if (
          ($order_total > 0)
          &&
          ($min_amount <= 0 || $order_total >= $min_amount)
          &&
          ($max_amount <= 0 || $order_total <= $max_amount)
          ) {
          // Check cart item count
          $cartItemCount = $this->getRequest()->getSession()->getSessionCart($this->getDispatcher())->countCartItems();

          if ($cartItemCount <= Sofort::getConfigValue('cart_item_count', 9)) {
          $valid = true;

          if (Sofort::isSandboxMode()) {
          // In sandbox mode, check the current IP
          $raw_ips = explode("\n", Sofort::getConfigValue('allowed_ip_list', ''));

          $allowed_client_ips = array();

          foreach ($raw_ips as $ip) {
          $allowed_client_ips[] = trim($ip);
          }

          $client_ip = $this->getRequest()->getClientIp();

          $valid = in_array($client_ip, $allowed_client_ips);
          }
          }
          } */
        return $valid;
    }

    public function postActivation(ConnectionInterface $con = null) {
        // Setup some default values at first install
        /*  if (null === self::getConfigValue('minimum_amount', null)) {
          self::setConfigValue('minimum_amount', 0);
          self::setConfigValue('maximum_amount', 0);
          self::setConfigValue('send_payment_confirmation_message', 1);
          }

          if (null === MessageQuery::create()->findOneByName(self::CONFIRMATION_MESSAGE_NAME)) {
          $message = new Message();

          $message
          ->setName(self::CONFIRMATION_MESSAGE_NAME)
          ->setHtmlTemplateFileName('sofort-payment-confirmation.html')
          ->setTextTemplateFileName('sofort-payment-confirmation.txt')
          ->setLocale('en_US')
          ->setTitle('Sofort payment confirmation')
          ->setSubject('Payment of order {$order_ref}')
          ->setLocale('fr_FR')
          ->setTitle('Confirmation de paiement par Sofort')
          ->setSubject('Confirmation du paiement de votre commande {$order_ref}')
          ->save()
          ;
          }
         */
        /* Deploy the module's image */
        $module = $this->getModuleModel();

        if (ModuleImageQuery::create()->filterByModule($module)->count() == 0) {
            $this->deployImageFolder($module, sprintf('%s/images', __DIR__), $con);
        }
    }

    //TODO 
    public function update($currentVersion, $newVersion, ConnectionInterface $con = null) {
        if (null === self::getConfigValue('login', null)) {
            $database = new Database($con);

            $statement = $database->execute('select * from sofort_config');

            while ($statement && $config = $statement->fetchObject()) {
                switch ($config->name) {
                    case 'login_sandbox':
                        Sofort::setConfigValue('sandbox_login', $config->value);
                        break;

                    case 'password_sandbox':
                        Sofort::setConfigValue('sandbox_password', $config->value);
                        break;

                    case 'signature_sandbox':
                        Sofort::setConfigValue('sandbox_signature', $config->value);
                        break;

                    default:
                        Sofort::setConfigValue($config->name, $config->value);
                        break;
                }
            }
        }

        parent::update($currentVersion, $newVersion, $con);
    }

    public static function isSandboxMode() {
        return 1 == intval(self::getConfigValue('sandbox'));
    }

    public function destroy(ConnectionInterface $con = null, $deleteModuleData = false) {
        if ($deleteModuleData) {
            MessageQuery::create()->findOneByName(self::CONFIRMATION_MESSAGE_NAME)->delete();
        }
    }

    /**
     * if you want, you can manage stock in your module instead of order process.
     * Return false to decrease the stock when order status switch to pay
     *
     * @return bool
     */
    public function manageStockOnCreation() {
        return false;
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
