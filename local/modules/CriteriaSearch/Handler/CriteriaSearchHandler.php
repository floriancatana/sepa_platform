<?php


namespace CriteriaSearch\Handler;

use CriteriaSearch\Model\Map\CriteriaSearchCategoryTaxRuleTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\Map\TaxRuleTableMap;
use Thelia\Model\TaxRuleQuery;
use Thelia\TaxEngine\Calculator;

class CriteriaSearchHandler
{
    /** @var  Request */
    protected $request;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     * @param Request $request
     */
    public function __construct(ContainerInterface $container, Request $request)
    {
        $this->container = $container;
    }

    public function getLoopParamsFromAjaxQuery(&$params, Request $request)
    {
        $features = $request->get('features');
        if (is_array($features)) {
            $params['features'] = self::formatCriteriaAvailabilityForLoop($features);
        }

        $attributes = $request->get('attributes');
        if (is_array($attributes)) {
            $params['attributes'] = self::formatCriteriaAvailabilityForLoop($attributes);
        }

        $brands = $request->get('brands');
        if (is_array($brands)) {
            $params['brands'] = implode(',', $brands);
        }

        self::getSimpleParams($params, $request);
    }

    public function getLoopParamsFromQuery(&$params, Request $request)
    {
        $featureRequest = $request->get('features');
        if ($featureRequest) {
            $params['features']  = str_replace('_', ':', $featureRequest);
        }

        $attributeRequest = $request->get('attributes');
        if ($attributeRequest) {
            $params['attributes'] = str_replace('_', ':', $attributeRequest);
        }

        $params['brands'] = $request->get('brands');

        self::getSimpleParams($params, $request);
    }

    protected function getSimpleParams(&$params, Request $request)
    {
        $params['category_id'] = $request->get('category_id');

        $params['price_min'] = $request->get('price_min');
        $params['price_max'] = $request->get('price_max');
        $params['in_stock'] = $request->get('in_stock');
        $params['new'] = $request->get('new');
        $params['promo'] = $request->get('promo');
        $params['limit'] = $request->get('limit') ? $request->get('limit') : 20;
        $params['page'] = $request->get('page') ? $request->get('page') : 1;
        $params['order'] = $request->get('order') ? $request->get('order') : 'alpha';
    }

    protected function formatCriteriaAvailabilityForLoop($criteriaArray)
    {
        $criteriaCount = 0;
        $criteriaString = "";
        foreach ($criteriaArray as $criteria => $availabilities) {
            if ($criteriaCount > 0) {
                $criteriaString .= ",";
            }
            $criteriaString .= $criteria.":(".implode('|', $availabilities).")";
            $criteriaCount++;
        }
        return $criteriaString;
    }

}
