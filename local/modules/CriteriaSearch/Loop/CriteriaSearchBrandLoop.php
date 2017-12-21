<?php


namespace CriteriaSearch\Loop;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Brand;
use Thelia\Model\Map\BrandTableMap;
use Thelia\Model\Map\ProductCategoryTableMap;
use Thelia\Model\Map\ProductTableMap;

class CriteriaSearchBrandLoop extends Brand implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return parent::getArgDefinitions()->addArguments(array(
            Argument::createIntListTypeArgument("category"),
        ));
    }

        
    public function buildModelCriteria()
    {
        $query = parent::buildModelCriteria();

        $productJoin = new Join();
        $productJoin->addExplicitCondition(
            BrandTableMap::TABLE_NAME,
            'ID',
            null,
            ProductTableMap::TABLE_NAME,
            'BRAND_ID',
            null
        );

        $productJoin->setJoinType(Criteria::JOIN);
        $query->addJoinObject($productJoin);

        if (null !== $categories = $this->getCategory()) {

            $productCategoryJoin = new Join();
            $productCategoryJoin->addExplicitCondition(
                ProductTableMap::TABLE_NAME,
                'ID',
                null,
                ProductCategoryTableMap::TABLE_NAME,
                'PRODUCT_ID',
                null
            );

            $productCategoryJoin->setJoinType(Criteria::JOIN);

            $query->addJoinObject($productCategoryJoin, 'product_category_join')
                ->addJoinCondition(
                    'product_category_join',
                    ProductCategoryTableMap::CATEGORY_ID . ' IN (' . implode(',', $categories) . ')'
                );
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
