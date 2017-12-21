<?php


namespace CriteriaSearch\Loop;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\AttributeAvailability;
use Thelia\Model\Map\AttributeAvTableMap;
use Thelia\Model\Map\AttributeCombinationTableMap;
use Thelia\Model\Map\ProductCategoryTableMap;
use Thelia\Model\Map\ProductSaleElementsTableMap;

class CriteriaSearchAttributeAvLoop extends AttributeAvailability implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return parent::getArgDefinitions()->addArguments(array(
            Argument::createIntListTypeArgument("category"),
            Argument::createBooleanTypeArgument("return_empty", true),
        ));
    }

        
    public function buildModelCriteria()
    {
        $query = parent::buildModelCriteria();

        if (false === $this->getReturnEmpty()) {
            $attributeAvCombinationJoin = new Join();
            $attributeAvCombinationJoin->addExplicitCondition(
                AttributeAvTableMap::TABLE_NAME,
                'ID',
                null,
                AttributeCombinationTableMap::TABLE_NAME,
                'ATTRIBUTE_AV_ID',
                null
            );

            $attributeAvCombinationJoin->setJoinType(Criteria::JOIN);
            $query->addJoinObject($attributeAvCombinationJoin, 'attribute_av_combination');

            if (null !== $categories = $this->getCategory()) {
                $pseJoin = new Join();
                $pseJoin->addExplicitCondition(
                    AttributeCombinationTableMap::TABLE_NAME,
                    'PRODUCT_SALE_ELEMENTS_ID',
                    null,
                    ProductSaleElementsTableMap::TABLE_NAME,
                    'ID',
                    null
                );
                $pseJoin->setJoinType(Criteria::JOIN);
                $query->addJoinObject($pseJoin, 'pse_join');

                $attributeAvProductCategorieJoin = new Join();
                $attributeAvProductCategorieJoin->addExplicitCondition(
                    ProductSaleElementsTableMap::TABLE_NAME,
                    'PRODUCT_ID',
                    null,
                    ProductCategoryTableMap::TABLE_NAME,
                    'PRODUCT_ID',
                    null
                );

                $attributeAvProductCategorieJoin->setJoinType(Criteria::JOIN);

                $query->addJoinObject($attributeAvProductCategorieJoin, 'attribute_av_product_category_join')
                    ->addJoinCondition(
                      'attribute_av_product_category_join',
                        ProductCategoryTableMap::CATEGORY_ID . ' IN (' . implode(',', $categories) . ')'
                    );

            }
        }

        $query->groupById();

        return $query;
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
