<?php


namespace CriteriaSearch\Loop;

use CriteriaSearch\Model\CriteriaSearchCategoryAttribute;
use CriteriaSearch\Model\CriteriaSearchCategoryAttributeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Attribute;
use Thelia\Model\AttributeQuery;
use Thelia\Model\Attribute as AttributeModel;
use Thelia\Model\Map\CategoryTableMap;
use Thelia\Model\Map\TemplateTableMap;

class CriteriaSearchAttributeLoop extends Attribute implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return parent::getArgDefinitions()->addArguments(array(
            Argument::createIntListTypeArgument("category"),
        ));
    }
        
    public function buildModelCriteria()
    {
        /** @var AttributeQuery $query */
        $query = parent::buildModelCriteria();

        if (null !== $attributeCategory = $this->getCategory()) {

            $categoryJoin = new Join();

            $categoryJoin->addExplicitCondition(
                TemplateTableMap::TABLE_NAME,
                'ID',
                null,
                CategoryTableMap::TABLE_NAME,
                'DEFAULT_TEMPLATE_ID',
                null
            );

            $categoryJoin->setJoinType(Criteria::JOIN);

            $query->useAttributeTemplateQuery()
                ->useTemplateQuery()
                    ->addJoinObject($categoryJoin, 'category_join')
                    ->addJoinCondition(
                        'category_join',
                        '`category`.`id` IN ('.implode(',',$attributeCategory).')'
                    )
                ->endUse()
            ->endUse();
        }

        return $query;
    }

    public function getAttributesSearch(LoopResult $loopResult)
    {
        $attributeIds = array();

        /** @var \Thelia\Model\Attribute $attribute */
        foreach ($loopResult->getResultDataCollection() as $attribute) {
            $attributeIds[] = $attribute->getId();
        }

        $query = CriteriaSearchCategoryAttributeQuery::create()
            ->filterByAttributeId($attributeIds, Criteria::IN);

        if (null !== $attributeCategory = $this->getCategory()) {
            $query->filterByCategoryId($attributeCategory);
        }

        return $query
            ->find();
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        $attributesSearches = self::getAttributesSearch($loopResult);

        $searches = array();

        /** @var CriteriaSearchCategoryAttribute $attributesSearch */
        foreach ($attributesSearches as $attributesSearch) {
            $searches[$attributesSearch->getAttributeId()] = $attributesSearch->getSearchable();
        }

        /** @var AttributeModel $attribute */
        foreach ($loopResult->getResultDataCollection() as $attribute) {
            $loopResultRow = new LoopResultRow($attribute);
            $loopResultRow->set("ID", $attribute->getId())
                ->set("IS_TRANSLATED", $attribute->getVirtualColumn('IS_TRANSLATED'))
                ->set("LOCALE", $this->locale)
                ->set("TITLE", $attribute->getVirtualColumn('i18n_TITLE'))
                ->set("CHAPO", $attribute->getVirtualColumn('i18n_CHAPO'))
                ->set("DESCRIPTION", $attribute->getVirtualColumn('i18n_DESCRIPTION'))
                ->set("POSTSCRIPTUM", $attribute->getVirtualColumn('i18n_POSTSCRIPTUM'))
                ->set("POSITION", $this->useAttributePosistion ? $attribute->getPosition() : $attribute->getVirtualColumn('position'))
                ->set('SEARCHABLE', $searches[$attribute->getId()]);

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
