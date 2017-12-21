<?php


namespace CriteriaSearch\Loop;

use CriteriaSearch\Model\CriteriaSearchCategoryFeature;
use CriteriaSearch\Model\CriteriaSearchCategoryFeatureQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Feature;
use Thelia\Model\Feature as FeatureModel;
use Thelia\Model\FeatureQuery;
use Thelia\Model\Map\CategoryTableMap;
use Thelia\Model\Map\TemplateTableMap;

class CriteriaSearchFeatureLoop extends Feature implements PropelSearchLoopInterface
{
    protected function getArgDefinitions()
    {
        return parent::getArgDefinitions()->addArguments(array(
            Argument::createIntListTypeArgument("category"),
        ));
    }
        
    public function buildModelCriteria()
    {
        /** @var FeatureQuery $query */
        $query = parent::buildModelCriteria();

        //If categories specified only returns features of this categories
        if (null !== $featureCategory = $this->getCategory()) {
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

            $query->useFeatureTemplateQuery()
                ->useTemplateQuery()
                    ->addJoinObject($categoryJoin, 'category_join')
                    ->addJoinCondition(
                        'category_join',
                        '`category`.`id` IN ('.implode(',',$featureCategory).')'
                    )
                ->endUse()
            ->endUse();
        }

        return $query;
    }

    public function getFeaturesSearch(LoopResult $loopResult)
    {
        $featureIds = array();

        /** @var FeatureModel $feature */
        foreach ($loopResult->getResultDataCollection() as $feature) {
            $featureIds[] = $feature->getId();
        }

        $query = CriteriaSearchCategoryFeatureQuery::create()
            ->filterByFeatureId($featureIds, Criteria::IN);

        if (null !== $featureCategory = $this->getCategory()) {
            $query->filterByCategoryId($featureCategory);
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
        $featureSearches = self::getFeaturesSearch($loopResult);

        $searches = array();

        /** @var CriteriaSearchCategoryFeature $featureSearch */
        foreach ($featureSearches as $featureSearch) {
            $searches[$featureSearch->getFeatureId()] = $featureSearch->getSearchable();
        }

        /** @var FeatureModel $feature */
        foreach ($loopResult->getResultDataCollection() as $feature) {
            $loopResultRow = new LoopResultRow($feature);
            $loopResultRow->set("ID", $feature->getId())
                ->set("IS_TRANSLATED", $feature->getVirtualColumn('IS_TRANSLATED'))
                ->set("LOCALE", $this->locale)
                ->set("TITLE", $feature->getVirtualColumn('i18n_TITLE'))
                ->set("CHAPO", $feature->getVirtualColumn('i18n_CHAPO'))
                ->set("DESCRIPTION", $feature->getVirtualColumn('i18n_DESCRIPTION'))
                ->set("POSTSCRIPTUM", $feature->getVirtualColumn('i18n_POSTSCRIPTUM'))
                ->set("POSITION", $this->useFeaturePosition ? $feature->getPosition() : $feature->getVirtualColumn('position'))
                ->set('SEARCHABLE', $searches[$feature->getId()]);

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
