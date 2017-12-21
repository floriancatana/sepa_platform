<?php

namespace CriteriaSearch\Controller\Admin;

use CriteriaSearch\CriteriaSearch;
use CriteriaSearch\Model\CriteriaSearchCategoryAttributeQuery;
use CriteriaSearch\Model\CriteriaSearchCategoryFeatureQuery;
use CriteriaSearch\Model\CriteriaSearchCategoryTaxRuleQuery;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;

class ConfigurationController extends BaseAdminController
{
    public function viewAction()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('CriteriaSearch'), AccessManager::VIEW)) {
            return $response;
        }

        $params['brand'] = CriteriaSearch::getConfigValue('brand_filter');
        $params['price'] = CriteriaSearch::getConfigValue('price_filter');
        $params['new'] = CriteriaSearch::getConfigValue('new_filter');
        $params['promo'] = CriteriaSearch::getConfigValue('promo_filter');
        $params['stock'] = CriteriaSearch::getConfigValue('stock_filter');

        return $this->render(
            "criteria-search/configuration",
            $params
        );
    }

    public function toggleCriteria($type)
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('CriteriaSearch'), AccessManager::UPDATE)) {
            return $response;
        }

        if ($type == "filter") {
            return $this->toggleFilter();
        }

        return $this->toggleSearchable($type);
    }

    protected function toggleFilter()
    {
        $checked = $this->getRequest()->get('checked');
        $filter = $this->getRequest()->get('filter');

        CriteriaSearch::setConfigValue($filter.'_filter', $checked);

        return Response::create();
    }

    protected function toggleSearchable($type)
    {
        try {
            $categoryId = $this->getRequest()->get('category_id');
            $objectId = $this->getRequest()->get('object_id');
            $searchable = $this->getRequest()->get('searchable') === "true" ? true : false;

            if ($type === 'feature') {
                $query = CriteriaSearchCategoryFeatureQuery::create()
                    ->filterByCategoryId($categoryId)
                    ->filterByFeatureId($objectId)
                    ->findOneOrCreate();
            } elseif ($type === 'attribute') {
                $query = CriteriaSearchCategoryAttributeQuery::create()
                    ->filterByCategoryId($categoryId)
                    ->filterByAttributeId($objectId)
                    ->findOneOrCreate();
            }

            $query->setSearchable($searchable)
                ->save();

            return Response::create();

        } catch (\Exception $e) {
            return Response::create($e->getMessage(), 500);
        }
    }

    public function setCategoryTaxRule()
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('CriteriaSearch'), AccessManager::UPDATE)) {
            return $response;
        }

        try {
            $searchCategoryTaxRule = CriteriaSearchCategoryTaxRuleQuery::create()
                ->filterByCategoryId($this->getRequest()->get('category_id'))
                ->findOneOrCreate();

            $searchCategoryTaxRule->setTaxRuleId($this->getRequest()->get('tax_rule_id'))
                ->save();
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
    }

}
