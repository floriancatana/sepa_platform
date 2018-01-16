<?php

namespace SepaImporter\Import;

use AmazonIntegration\Controller\Admin\AmazonAWSController;
use Symfony\Component\Serializer\Exception\Exception;
use Thelia\ImportExport\Import\AbstractImport;
use Thelia\Log\Tlog;
use Thelia\Model\Currency;
use Thelia\Model\FeatureAv;
use Thelia\Model\FeatureAvI18n;
use Thelia\Model\FeatureProduct;
use Thelia\Model\Module;
use Thelia\Model\Product;
use Thelia\Model\ProductI18n;
use Thelia\Model\ProductImage;
use Thelia\Model\ProductImageI18n;
use Thelia\Model\ProductPrice;
use Thelia\Model\ProductPriceQuery;
use Thelia\Model\ProductQuery;
use Thelia\Model\ProductSaleElements;
use Thelia\Model\ProductSaleElementsQuery;
use const DS;
use const THELIA_LOCAL_DIR;
use const THELIA_LOG_DIR;

/* * ********************************************************************************** */
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/* * ********************************************************************************** */


/**
 * Class ProductPricesImport
 * @author Benjamin Perche <bperche@openstudio.fr>
 */
class GenericProductImport extends AbstractImport {
    /* @var Tlog $log */

    protected static $logger;
    protected $mandatoryColumns = [
        'Ref'
    ];

    public function rowHasField($row, $field) {
        if (isset($row[$field])) {
            return utf8_encode($row[$field]);
        }
        return null;
    }

    public function importData(array $row) {
        $errors = null;
        $log = $this->getLogger();
        $max_time = ini_get("max_execution_time");
        ini_set('max_execution_time', 60000);
        //$brandI18nQuerry = BrandI18nQuery::create ();
        $productQuerry = ProductQuery::create();

        $currentDate = date("Y-m-d H:i:s");

        $i = 0;

        $log->debug(" generic_product_import input " . $i . implode(" ", $row));
        $this->checkMandatoryColumns($row);

        //$produkt_id = $this->rowHasField($row, "Produkt_id");
        $extern_id = $this->rowHasField($row, "Extern_id");
        $ref = $this->rowHasField($row, "Ref");
        $marke_id = $this->rowHasField($row, "Marke_id");
        $kategorie_id = $this->rowHasField($row, "Kategorie_id");
        $produkt_titel = $this->rowHasField($row, "Produkt_titel");
        $kurze_beschreibung = $this->rowHasField($row, "Kurze_beschreibung");
        $beschreibung = $this->rowHasField($row, "Beschreibung");
        $postscriptum = $this->rowHasField($row, "Postscriptum");
        $meta_titel = $this->rowHasField($row, "Meta_titel");
        $meta_beschreibung = $this->rowHasField($row, "Meta_beschreibung");
        $meta_keywords = $this->rowHasField($row, "Meta_keywords");
        $menge = $this->rowHasField($row, "Menge");
        $ist_in_Angebot = $this->rowHasField($row, "Ist_in_Angebot");
        $ist_neu = $this->rowHasField($row, "Ist_neu");
        $ist_online = $this->rowHasField($row, "Ist_online");
        $gewicht = $this->rowHasField($row, "Gewicht");
        $EAN_code = trim($this->rowHasField($row, "EAN_code"));
        //$bild_name = $this->rowHasField($row, "Bild_name");
        $bild_titel = $this->rowHasField($row, "Bild_titel");
        $bild_beschreibung = $this->rowHasField($row, "Bild_beschreibung");
        $bild_kurz_beschreibung = $this->rowHasField($row, "Bild_kurz_beschreibung");
        $bild_postscriptum = $this->rowHasField($row, "Bild_postscriptum");
        $bild_file = $this->rowHasField($row, "Bild_file");
        $price = $this->rowHasField($row, "Price");
        $promo_price = $this->rowHasField($row, "Promo_price");
        $listen_price = $this->rowHasField($row, "Listen_price");
        $ek_preis_sht = $this->rowHasField($row, "Ek_preis_sht");
        $ek_preis_gc = $this->rowHasField($row, "Ek_preis_gc");
        $ek_preis_oag = $this->rowHasField($row, "Ek_preis_oag");
        $ek_preis_holter = $this->rowHasField($row, "Ek_preis_holter");
        $preis_reuter = $this->rowHasField($row, "Preis_reuter");
        $vergleich_ek = $this->rowHasField($row, "Vergleich_ek");
        $aufschlag = $this->rowHasField($row, "Aufschlag");
        $template_id = $this->rowHasField($row, "Template_id");

        //check for existing services
        $productQuerry->clear();
        $productExists = count($productQuerry->findByRef($ref));

        if ($productExists == 0) { // product_numbers must be unique
            $log->debug(" generic_product is new ");
            //save product info
            $productThelia = new Product ();
            $productThelia->setRef($ref); // must be unique
            $productThelia->setVisible(0);
            if ($marke_id != null)
                $productThelia->setBrandId($marke_id);

            if ($extern_id != null)
                $productThelia->setExternId($extern_id);

            $productThelia->setCreatedAt($currentDate);
            $productThelia->setUpdatedAt($currentDate);
            $productThelia->setVersion(1);
            $productThelia->setVersionCreatedAt($currentDate);
            $productThelia->setVersionCreatedBy("importer.4");

            if ($template_id != null)
                $productThelia->setTemplateId($template_id);
            else
                $productThelia->setTemplateId(1);

            if ($ist_online != null)
                $productThelia->setVisible($ist_online);

            $gewicht = isset($gewicht) ? $gewicht : 'NULL';
            $price = isset($price) ? $price : 'NULL';
            $productThelia->create($kategorie_id, $price, 1, 1, $gewicht, 10);
            
            $mod = new Module();
            $mod->getActivate();
            
            if (Common::getActiveModule("AmazonIntegration") == 1)
            {
                $log->debug("AMAZON IMAGES - BEFORE get images from Amazon in Generic product import");
                // get info from amazon
                $amazonAPI = new AmazonAWSController();
                $infoAmazon = $amazonAPI->getProductInfoFromAmazon($EAN_code);

                $this->saveImageFromAmazon($log, $productThelia, $EAN_code, $infoAmazon);
                $this->saveFeaturesColorFromAmazon($log, $productThelia, $EAN_code, $infoAmazon);
                $this->saveFeaturesHeightFromAmazon($log, $productThelia, $EAN_code, $infoAmazon);
                $this->saveFeaturesLengthFromAmazon($log, $productThelia, $EAN_code, $infoAmazon);
                $this->saveFeaturesWidthFromAmazon($log, $productThelia, $EAN_code, $infoAmazon);
            }
                      
            // product description en_US
            $productI18n = new ProductI18n ();
            $productI18n->setProduct($productThelia);
            $productI18n->setLocale("en_US");

            if ($produkt_titel != null)
                $productI18n->setTitle($produkt_titel);

            if ($beschreibung != null)
                $productI18n->setDescription($beschreibung);
            
            if (Common::getActiveModule("AmazonIntegration") == 1)
            {
                if ($infoAmazon['description'] && (strlen($infoAmazon['description']) > strlen($beschreibung)))
                {
                    $productI18n->setDescription($infoAmazon['description']);
                }
            }

            if ($kurze_beschreibung != null)
                $productI18n->setChapo($kurze_beschreibung);

            if ($postscriptum != null)
                $productI18n->setPostscriptum($postscriptum);

            if ($meta_titel != null)
                $productI18n->setMetaTitle($meta_titel);

            if ($meta_beschreibung != null)
                $productI18n->setMetaDescription($meta_beschreibung);

            if ($meta_keywords != null)
                $productI18n->setMetaKeywords($meta_keywords);

            $productI18n->save();
            //$log->debug ( " product_i18n en_US is added ".$productI18n->__toString() );
            $productThelia->addProductI18n($productI18n);

            // product description de_DE
            $productI18n = new ProductI18n ();
            $productI18n->setProduct($productThelia);
            $productI18n->setLocale("de_DE");
            if ($produkt_titel != null)
                $productI18n->setTitle($produkt_titel);

            if ($beschreibung != null)
                $productI18n->setDescription($beschreibung);
            
            if (Common::getActiveModule("AmazonIntegration") == 1)
            {
                if ($infoAmazon['description'] && (strlen($infoAmazon['description']) > strlen($beschreibung)))
                {
                    $productI18n->setDescription($infoAmazon['description']);
                }
            }

            if ($kurze_beschreibung != null)
                $productI18n->setChapo($kurze_beschreibung);

            if ($postscriptum != null)
                $productI18n->setPostscriptum($postscriptum);

            if ($meta_titel != null)
                $productI18n->setMetaTitle($meta_titel);

            if ($meta_beschreibung != null)
                $productI18n->setMetaDescription($meta_beschreibung);

            if ($meta_keywords != null)
                $productI18n->setMetaKeywords($meta_keywords);

            $productI18n->save();
            //	$log->debug ( " generic_product_import product_i18n de_DE is added ".$productI18n->__toString() );
            $productThelia->addProductI18n($productI18n);

            // find product sale element
            $pse = ProductSaleElementsQuery::create()->findOneByProductId($productThelia->getId());

            if ($pse != null) {

                //$log->debug ( " generic_product_import pse found ".$pse->__toString() );
                $currency = Currency::getDefaultCurrency();
                $price = ProductPriceQuery::create()
                        ->filterByProductSaleElementsId($pse->getId())
                        ->findOneByCurrencyId($currency->getId());
            } else {
                $pse = new ProductSaleElements();
                $pse->setProduct($productThelia);
            }

            $pse->setRef($ref);

            if ($menge != null)
                $pse->setQuantity($menge);

            if ($ist_in_Angebot != null)
                $pse->setPromo($ist_in_Angebot);

            if ($ist_neu != null)
                $pse->setNewness($ist_neu);

            if ($gewicht != null)
                $pse->setWeight($gewicht);

            if (Common::getActiveModule("AmazonIntegration") == 1)
            {
                if ($gewicht == null && $infoAmazon['weight'])
                {
                    $pse->setWeight($infoAmazon['weight']);
                }
            }
            
            if ($EAN_code != null)
                $pse->setEanCode($EAN_code);

            $pse->save();

            //save price
            if ($price === null) {
                $price = new ProductPrice();
                $price->setProductSaleElements($pse);
                $price->setCurrency($currency);
            } else
                $log->debug(" generic_product_import price found");
            //$log->debug ( " generic_product_import price found ".$price->__toString() );

            if ($promo_price != null)
                $price->setPromoPrice($promo_price);

            if ($listen_price != null)
                $price->setListenPrice($listen_price);

            if ($ek_preis_sht != null)
                $price->setEkPreisSht($ek_preis_sht);

            if ($ek_preis_gc != null)
                $price->setEkPreisGc($ek_preis_gc);

            if ($ek_preis_oag != null)
                $price->setEkPreisOag($ek_preis_oag);

            if ($ek_preis_holter != null)
                $price->setEkPreisHolter($ek_preis_holter);

            if ($preis_reuter != null)
                $price->setPreisReuter($preis_reuter);

            if ($vergleich_ek != null)
                $price->setVergleichEk($vergleich_ek);

            if ($aufschlag != null)
                $price->setAufschlag($aufschlag);

            $price->save();
            $log->debug(" generic_product_import price saved");

            //save images
            $image_path = THELIA_LOCAL_DIR . "media" . DS . "images" . DS . "product" . DS;
            $image_name = 'PROD_' . preg_replace("/[^a-zA-Z0-9.]/", "", $bild_file);

            $log->debug(" generic_product_import image");

            try {
                $log->debug(" generic_product_import image from " . THELIA_LOCAL_DIR . "media" . DS . "images" . DS . "importer" . DS . $bild_file);
                $image_from_server = @file_get_contents(THELIA_LOCAL_DIR . "media" . DS . "images" . DS . "importer" . DS . $bild_file);
            } catch (Exception $e) {
                $log->debug("ProductImageException :" . $e->getMessage());
            }

            if ($image_from_server) {
                $log->debug(" generic_product_import image saved to " . $image_path);
                file_put_contents($image_path . $image_name, $image_from_server);

                $product_image = new ProductImage ();
                $product_image->setProduct($productThelia);
                $product_image->setVisible(1);
                $product_image->setCreatedAt($currentDate);
                $product_image->setUpdatedAt($currentDate);
                $product_image->setFile($image_name);
                $product_image->save();

                $product_image_i18n = new ProductImageI18n();
                $product_image_i18n->setProductImage($product_image);
                $product_image_i18n->setTitle($bild_titel);
                $product_image_i18n->setDescription($bild_beschreibung);
                $product_image_i18n->setChapo($bild_kurz_beschreibung);
                $product_image_i18n->setPostscriptum($bild_postscriptum);
                $product_image_i18n->setLocale("de_DE");
                $product_image_i18n->save();

                $productThelia->addProductImage($product_image);
            }
        } else {
            $errors .= "Product reference number " . $ref . " is already in the database ";
            $log->debug(" ref number already in the database '" . $ref . "'");
        }
        ini_set('max_execution_time', $max_time);
        if ($errors == null)
            $this->importedRows++;
        return $errors;
    }

    public function getLogger() {
        if (self::$logger == null) {
            self::$logger = Tlog::getNewInstance();

            $logFilePath = THELIA_LOG_DIR . DS . "log-generic-importer.txt";

            self::$logger->setPrefix("#LEVEL: #DATE #HOUR: ");
            self::$logger->setDestinations("\\Thelia\\Log\\Destination\\TlogDestinationRotatingFile");
            self::$logger->setConfig("\\Thelia\\Log\\Destination\\TlogDestinationRotatingFile", 0, $logFilePath);
            self::$logger->setLevel(Tlog::DEBUG);
        }
        return self::$logger;
    }
    
    public function saveImageFromAmazon($log, &$productThelia, $EAN_code, $infoAmazon)
    {
        $log->debug("AMAZON IMAGES - get images from Amazon in Generic product import");

        // save images from Amazon
        if ($infoAmazon['images']) {

            foreach ($infoAmazon['images'] as $imageAmazon) {
                $product_image = new ProductImage ();
                $product_image->setProduct($productThelia);
                $product_image->setVisible(1);
                $product_image->setCreatedAt($currentDate);
                $product_image->setUpdatedAt($currentDate);
                $product_image->setFile($imageAmazon['file_name']);
                $product_image->save();
                $product_image_i18n = new ProductImageI18n();
                $product_image_i18n->setProductImage($product_image);
                $product_image_i18n->setTitle($imageAmazon['title']);
                $product_image_i18n->setDescription($imageAmazon['title']);
                $product_image_i18n->setLocale("de_DE");
                $product_image_i18n->save();

                $productThelia->addProductImage($product_image);
                $log->debug("AMAZON IMAGES -  file was inserted in DB " . $imageAmazon['file_name']);
            }
        } else {
            $log->debug("AMAZON IMAGES - no images for this product " . $EAN_code);
        }
    }
    
    public function saveFeaturesColorFromAmazon($log, $productThelia, $EAN_code, $infoAmazon)
    {
        // save features from Amazon: color, height, length, width
        if ($infoAmazon['color']) {

            $fav = new FeatureAv();
            $fav->setFeatureId(21)->save();

            // feature en_US
            $fav_i18n = new FeatureAvI18n();
            $fav_i18n->setId($fav->getId())
                    ->setLocale('en_US')
                    ->setTitle($infoAmazon['color'])
                    ->save();

            // feature de_DE
            $fav_i18n = new FeatureAvI18n();
            $fav_i18n->setId($fav->getId())
                    ->setLocale('de_DE')
                    ->setTitle($infoAmazon['color'])
                    ->save();

            // feature product
            $fav_product = new FeatureProduct();
            $fav_product
                    ->setProductId($productThelia->getId())
                    ->setFeatureId(21)
                    ->setFeatureAvId($fav->getId())
                    ->setFreeTextValue(1)
                    ->save();

            $log->debug("AMAZON - product " . $EAN_code . " - saved color");
        }
    }
    
    public function saveFeaturesHeightFromAmazon($log, $productThelia, $EAN_code, $infoAmazon)
    {
        if ($infoAmazon['height']) {

            $fav = new FeatureAv();
            $fav->setFeatureId(17)->save();

            // feature en_US
            $fav_i18n = new FeatureAvI18n();
            $fav_i18n->setId($fav->getId())
                    ->setLocale('en_US')
                    ->setTitle($infoAmazon['height'])
                    ->save();

            // feature de_DE
            $fav_i18n = new FeatureAvI18n();
            $fav_i18n->setId($fav->getId())
                    ->setLocale('de_DE')
                    ->setTitle($infoAmazon['height'])
                    ->save();

            // feature product
            $fav_product = new FeatureProduct();
            $fav_product
                    ->setProductId($productThelia->getId())
                    ->setFeatureId(17)
                    ->setFeatureAvId($fav->getId())
                    ->setFreeTextValue(1)
                    ->save();

            $log->debug("AMAZON - product " . $EAN_code . " - saved height");
        }
    }
    
    public function saveFeaturesLengthFromAmazon($log, $productThelia, $EAN_code, $infoAmazon)
    {
        if ($infoAmazon['length']) {

            $fav = new FeatureAv();
            $fav->setFeatureId(65)->save();

            // feature en_US
            $fav_i18n = new FeatureAvI18n();
            $fav_i18n->setId($fav->getId())
                    ->setLocale('en_US')
                    ->setTitle($infoAmazon['length'])
                    ->save();

            // feature de_DE
            $fav_i18n = new FeatureAvI18n();
            $fav_i18n->setId($fav->getId())
                    ->setLocale('de_DE')
                    ->setTitle($infoAmazon['length'])
                    ->save();

            // feature product
            $fav_product = new FeatureProduct();
            $fav_product
                    ->setProductId($productThelia->getId())
                    ->setFeatureId(65)
                    ->setFeatureAvId($fav->getId())
                    ->setFreeTextValue(1)
                    ->save();

            $log->debug("AMAZON - product " . $EAN_code . " - saved length");
        }
    }
    
    public function saveFeaturesWidthFromAmazon($log, $productThelia, $EAN_code, $infoAmazon)
    {
        if ($infoAmazon['width']) {

            $fav = new FeatureAv();
            $fav->setFeatureId(88)->save();

            // feature en_US
            $fav_i18n = new FeatureAvI18n();
            $fav_i18n->setId($fav->getId())
                    ->setLocale('en_US')
                    ->setTitle($infoAmazon['width'])
                    ->save();

            // feature de_DE
            $fav_i18n = new FeatureAvI18n();
            $fav_i18n->setId($fav->getId())
                    ->setLocale('de_DE')
                    ->setTitle($infoAmazon['width'])
                    ->save();

            // feature product
            $fav_product = new FeatureProduct();
            $fav_product
                    ->setProductId($productThelia->getId())
                    ->setFeatureId(88)
                    ->setFeatureAvId($fav->getId())
                    ->setFreeTextValue(1)
                    ->save();

            $log->debug("AMAZON - product " . $EAN_code . " - saved width");
        }
    }
}
