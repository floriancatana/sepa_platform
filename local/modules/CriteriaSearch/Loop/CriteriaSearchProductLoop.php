<?php


namespace CriteriaSearch\Loop;

use CriteriaSearch\Model\Map\CriteriaSearchCategoryTaxRuleTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Product;
use Thelia\Model\CurrencyQuery;
use Thelia\Model\Map\AttributeCombinationTableMap;
use Thelia\Model\Map\ProductPriceTableMap;
use Thelia\Model\Map\ProductSaleElementsTableMap;
use Thelia\Model\Map\TaxRuleTableMap;
use Thelia\Model\TaxRuleQuery;
use Thelia\TaxEngine\Calculator;
use Thelia\Type\IntToCombinedIntsListType;
use Thelia\Type\TypeCollection;

class CriteriaSearchProductLoop extends Product implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return parent::getArgDefinitions()->addArguments(
            [
                Argument::createIntTypeArgument("category_id"),
                Argument::createFloatTypeArgument('min_price_ttc'),
                Argument::createFloatTypeArgument('max_price_ttc'),
                new Argument(
                    'attribute_availability',
                    new TypeCollection(
                        new IntToCombinedIntsListType()
                    )
                ),
            ]);
    }

        
    public function buildModelCriteria()
    {
        $query = parent::buildModelCriteria();

        $attributeAvailability = $this->getAttributeAvailability();

        $this->manageAttributeAv($query, $attributeAvailability);

        $minPriceTTC = $this->getMinPriceTtc();
        $maxPriceTTC = $this->getMaxPriceTtc();

        if ($minPriceTTC || $maxPriceTTC) {
            $this->managePriceFilter($search, $minPriceTTC, $maxPriceTTC);
        }

        return $query;
    }

    protected function manageAttributeAv(&$search, $attributeAvailability)
    {
        $search->innerJoinProductSaleElements('pse_attribute');

        if (null !== $attributeAvailability) {
            foreach ($attributeAvailability as $attribute => $attributeChoice) {
                foreach ($attributeChoice['values'] as $attributeAv) {
                    $attributeAlias = 'aa_' . $attribute;
                    if ($attributeAv != '*') {
                        $attributeAlias .= '_' .$attributeAv;
                    }
                    $attributeAvJoin =  new Join();
                    $attributeAvJoin->addExplicitCondition(
                        ProductSaleElementsTableMap::TABLE_NAME,
                        'ID',
                        'pse_attribute',
                        AttributeCombinationTableMap::TABLE_NAME,
                        'PRODUCT_SALE_ELEMENTS_ID',
                        $attributeAlias
                    );
                    $attributeAvJoin->setJoinType(Criteria::LEFT_JOIN);

                    $search->addJoinObject($attributeAvJoin, $attributeAlias);

                    if ($attributeAv != '*') {
                        $search->addJoinCondition(
                            $attributeAlias,
                            "`$attributeAlias`.ATTRIBUTE_AV_ID = ?",
                            $attributeAv,
                            null,
                            \PDO::PARAM_INT
                        );
                    }
                }

                /* format for mysql */
                $sqlWhereString = $attributeChoice['expression'];
                if ($sqlWhereString == '*') {
                    $sqlWhereString = 'NOT ISNULL(`aa_' . $attribute . '`.ID)';
                } else {
                    $sqlWhereString = preg_replace('#([0-9]+)#', 'NOT ISNULL(`aa_' . $attribute . '_' . '\1`.ATTRIBUTE_ID)', $sqlWhereString);
                    $sqlWhereString = str_replace('&', ' AND ', $sqlWhereString);
                    $sqlWhereString = str_replace('|', ' OR ', $sqlWhereString);
                }

                $search->where("(" . $sqlWhereString . ")");
            }
        }
    }

    /**
     * Copy of default product loop price filter but with tax applied to asked price
     * @param $search
     * @param $minPriceTTC
     * @param $maxPriceTTC
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function managePriceFilter(&$search, $minPriceTTC, $maxPriceTTC)
    {
        $categoryId = $this->getCategoryId();

        $taxeRuleQuery = TaxRuleQuery::create();

        $categoryJoin = new Join();
        $categoryJoin->addExplicitCondition(
            TaxRuleTableMap::TABLE_NAME,
            'ID',
            null,
            CriteriaSearchCategoryTaxRuleTableMap::TABLE_NAME,
            'TAX_RULE_ID',
            null
        );
        $categoryJoin->setJoinType(Criteria::LEFT_JOIN);

        $taxeRuleQuery->addJoinObject($categoryJoin, 'category_join')
            ->addJoinCondition(
                'category_join',
                CriteriaSearchCategoryTaxRuleTableMap::CATEGORY_ID . ' = ' .$categoryId
            );

        $taxeRule = $taxeRuleQuery->findOne();

        $taxCountry = $this->container->get('thelia.taxEngine')->getDeliveryCountry();

        $calculator = new Calculator();
        $calculator->loadTaxRuleWithoutProduct($taxeRule, $taxCountry);

        $currencyId = $this->getCurrency();
        if (null !== $currencyId) {
            $currency = CurrencyQuery::create()->findOneById($currencyId);
            if (null === $currency) {
                throw new \InvalidArgumentException('Cannot found currency id: `' . $currency . '` in product_sale_elements loop');
            }
        } else {
            $currency = $this->request->getSession()->getCurrency();
        }

        $defaultCurrency = CurrencyQuery::create()->findOneByByDefault(1);
        $defaultCurrencySuffix = '_default_currency';

        if (null !== $minPriceTTC) {
            $minPriceHt = round($calculator->getUntaxedPrice($minPriceTTC), 2);

            $isPSELeftJoinList[] = 'is_min_price';

            $minPriceJoin = new Join();
            $minPriceJoin->addExplicitCondition(ProductSaleElementsTableMap::TABLE_NAME, 'ID', 'is_min_price_ttc', ProductPriceTableMap::TABLE_NAME, 'PRODUCT_SALE_ELEMENTS_ID', 'min_price_ttc_data');
            $minPriceJoin->setJoinType(Criteria::LEFT_JOIN);

            $search->joinProductSaleElements('is_min_price_ttc', Criteria::LEFT_JOIN)
                ->addJoinObject($minPriceJoin, 'is_min_price_ttc_join')
                ->addJoinCondition('is_min_price_ttc_join', '`min_price_ttc_data`.`currency_id` = ?', $currency->getId(), null, \PDO::PARAM_INT);

            if ($defaultCurrency->getId() != $currency->getId()) {
                $minPriceJoinDefaultCurrency = new Join();
                $minPriceJoinDefaultCurrency->addExplicitCondition(ProductSaleElementsTableMap::TABLE_NAME, 'ID', 'is_min_price_ttc', ProductPriceTableMap::TABLE_NAME, 'PRODUCT_SALE_ELEMENTS_ID', 'min_price_ttc_data' . $defaultCurrencySuffix);
                $minPriceJoinDefaultCurrency->setJoinType(Criteria::LEFT_JOIN);

                $search->addJoinObject($minPriceJoinDefaultCurrency, 'is_min_price_ttc_join' . $defaultCurrencySuffix)
                    ->addJoinCondition('is_min_price_ttc_join' . $defaultCurrencySuffix, '`min_price_ttc_data' . $defaultCurrencySuffix . '`.`currency_id` = ?', $defaultCurrency->getId(), null, \PDO::PARAM_INT);

                /**
                 * In propel we trust : $currency->getRate() always returns a float.
                 * Or maybe not : rate value is checked as a float in overloaded getRate method.
                 */
                $MinPriceToCompareAsSQL = 'CASE WHEN ISNULL(CASE WHEN `is_min_price_ttc`.PROMO=1 THEN `min_price_ttc_data`.PROMO_PRICE ELSE `min_price_ttc_data`.PRICE END) OR `min_price_ttc_data`.FROM_DEFAULT_CURRENCY = 1 THEN
                    CASE WHEN `is_min_price_ttc`.PROMO=1 THEN `min_price_ttc_data' . $defaultCurrencySuffix . '`.PROMO_PRICE ELSE `min_price_ttc_data' . $defaultCurrencySuffix . '`.PRICE END * ' . $currency->getRate() . '
                ELSE
                    CASE WHEN `is_min_price_ttc`.PROMO=1 THEN `min_price_ttc_data`.PROMO_PRICE ELSE `min_price_ttc_data`.PRICE END
                END';
            } else {
                $MinPriceToCompareAsSQL = 'CASE WHEN `is_min_price_ttc`.PROMO=1 THEN `min_price_ttc_data`.PROMO_PRICE ELSE `min_price_ttc_data`.PRICE END';
            }

            $search->where('ROUND(' . $MinPriceToCompareAsSQL . ', 2)>=?', $minPriceHt, \PDO::PARAM_STR);
        }

        if (null !== $maxPriceTTC) {
            $maxPriceHt = round($calculator->getUntaxedPrice($maxPriceTTC), 2);

            $isPSELeftJoinList[] = 'is_max_price_ttc';

            $maxPriceJoin = new Join();
            $maxPriceJoin->addExplicitCondition(ProductSaleElementsTableMap::TABLE_NAME, 'ID', 'is_max_price_ttc', ProductPriceTableMap::TABLE_NAME, 'PRODUCT_SALE_ELEMENTS_ID', 'max_price_ttc_data');
            $maxPriceJoin->setJoinType(Criteria::LEFT_JOIN);

            $search->joinProductSaleElements('is_max_price_ttc', Criteria::LEFT_JOIN)
                ->addJoinObject($maxPriceJoin, 'is_max_price_ttc_join')
                ->addJoinCondition('is_max_price_ttc_join', '`max_price_ttc_data`.`currency_id` = ?', $currency->getId(), null, \PDO::PARAM_INT);

            if ($defaultCurrency->getId() != $currency->getId()) {
                $maxPriceJoinDefaultCurrency = new Join();
                $maxPriceJoinDefaultCurrency->addExplicitCondition(ProductSaleElementsTableMap::TABLE_NAME, 'ID', 'is_max_price_ttc', ProductPriceTableMap::TABLE_NAME, 'PRODUCT_SALE_ELEMENTS_ID', 'max_price_ttc_data' . $defaultCurrencySuffix);
                $maxPriceJoinDefaultCurrency->setJoinType(Criteria::LEFT_JOIN);

                $search->addJoinObject($maxPriceJoinDefaultCurrency, 'is_max_price_ttc_join' . $defaultCurrencySuffix)
                    ->addJoinCondition('is_max_price_ttc_join' . $defaultCurrencySuffix, '`max_price_ttc_data' . $defaultCurrencySuffix . '`.`currency_id` = ?', $defaultCurrency->getId(), null, \PDO::PARAM_INT);

                /**
                 * In propel we trust : $currency->getRate() always returns a float.
                 * Or maybe not : rate value is checked as a float in overloaded getRate method.
                 */
                $MaxPriceToCompareAsSQL = 'CASE WHEN ISNULL(CASE WHEN `is_max_price_ttc`.PROMO=1 THEN `max_price_ttc_data`.PROMO_PRICE ELSE `max_price_ttc_data`.PRICE END) OR `min_price_data`.FROM_DEFAULT_CURRENCY = 1 THEN
                    CASE WHEN `is_max_price_ttc`.PROMO=1 THEN `max_price_ttc_data' . $defaultCurrencySuffix . '`.PROMO_PRICE ELSE `max_price_ttc_data' . $defaultCurrencySuffix . '`.PRICE END * ' . $currency->getRate() . '
                ELSE
                    CASE WHEN `is_max_price_ttc`.PROMO=1 THEN `max_price_ttc_data`.PROMO_PRICE ELSE `max_price_ttc_data`.PRICE END
                END';
            } else {
                $MaxPriceToCompareAsSQL = 'CASE WHEN `is_max_price_ttc`.PROMO=1 THEN `max_price_ttc_data`.PROMO_PRICE ELSE `max_price_ttc_data`.PRICE END';
            }

            $search->where('ROUND(' . $MaxPriceToCompareAsSQL . ', 2)<=?', $maxPriceHt, \PDO::PARAM_STR);
        }
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        return parent::parseResults($loopResult);
    }
}
