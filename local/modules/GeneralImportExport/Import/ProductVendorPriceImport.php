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

namespace GeneralImportExport\Import;

use Thelia\Core\Translation\Translator;
use Thelia\ImportExport\Import\AbstractImport;
use Thelia\Model\Currency;
use Thelia\Model\CurrencyQuery;
use Thelia\Model\ProductPrice;
use Thelia\Model\ProductPriceQuery;
use Thelia\Model\ProductSaleElementsQuery;

/**
 * Class ProductPricesHausfabrikImport
 * @author Emanuel Plopu <emanuel.plopu@sepa.at>
 */
class ProductVendorPriceImport extends AbstractImport
{
    protected $mandatoryColumns = [
    		'Ref'
        //'Id',
    	//'Product_id',
        //'price',
    	//'ek_price_sht',
    	//'ek_price_gc'
    ];
    
    public function rowHasField($row,$field){
    	if(isset($row[$field])){
    		return utf8_encode($row[$field]);
    	}
    	return null;
    }

    public function importData(array $row)
    {
    	$pse = ProductSaleElementsQuery::create();
    	
    	if($this->rowHasField($row, "Id"))
        $pse = $pse->findPk($row['Id']);

        if ($pse === null) {
            return Translator::getInstance()->trans(
                'The product sale element id %id doesn\'t exist',
                [
                    '%id' => $row['Id']
                ]
            );
        } else {
            $currency = null;
            if (isset($$row['currency'])) {
                $currency = CurrencyQuery::create()->findOneByCode($row['currency']);
            }
            if ($currency === null) {
                $currency = Currency::getDefaultCurrency();
            }

            $price = ProductPriceQuery::create()
                ->filterByProductSaleElementsId($pse->getId())
                ->findOneByCurrencyId($currency->getId())
            ;

            if ($price === null) {
                $price = new ProductPrice;

                $price
                    ->setProductSaleElements($pse)
                    ->setCurrency($currency)
                ;
            }

            $price->setPrice($row['price']);

            if (isset($row['promo_price'])) {
                $price->setPromoPrice($row['promo_price']);
            }

            if (isset($row['promo'])) {
                $price
                    ->getProductSaleElements()
                    ->setPromo((int) $row['promo'])
                    ->save()
                ;
            }
            $price->setEkPreisSht($row['ek_price_sht']);
            $price->setEkPreisGc($row['ek_price_gc']);

            $price->save();
            $this->importedRows++;
        }

        return null;
    }
}
