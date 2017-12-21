<?php


namespace CriteriaSearch\Controller\Front;

use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\JsonResponse;

class SearchController extends BaseFrontController
{
    /**
     * Return render of product search results
     * @return \Thelia\Core\HttpFoundation\Response
     */
    public function getSearchRender()
    {
        $request = $this->getRequest();

        $params = [];

        $this->container->get('criteriasearch.handler')->getLoopParamsFromAjaxQuery($params, $request);

        return $this->render("criteria-search/search-results", $params);
    }


    /**
     * Return new url with good params for update with history
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function getSearchUrl()
    {
        $request = $this->getRequest();

        $params = [];

        $this->container->get('criteriasearch.handler')->getLoopParamsFromAjaxQuery($params, $request);

        $urlQuery = "?criteria=true";
        foreach ($params as $paramKey => $paramValue) {
            if ($paramValue) {
                $urlQuery .= "&".$paramKey."=".str_replace(":", "_",$paramValue);
            }
        }

        return JsonResponse::create(["url" => $urlQuery]);
    }


    /**
     * Return page search info like what checkbox is checked,
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function getSearchPageInfo()
    {
        $request = $this->getRequest();

        $multiCheckBox = [];
        $features = $request->get('features');
        $this->getCheckedCriteria($multiCheckBox, $features, 'feature');
        $attributes = $request->get('attributes');
        $this->getCheckedCriteria($multiCheckBox, $attributes, 'attribute');
        $this->getCheckedChoiceParam($multiCheckBox, $request->get('brands'), 'brand');

        $simpleCheckBox = [];
        if ($request->get('new') === "true") {
            $simpleCheckBox[] = 'is-new';
        }

        if ($request->get('in_stock') === "true") {
            $simpleCheckBox[] = 'in-stock';
        }

        if ($request->get('promo') === "true") {
            $simpleCheckBox[] = 'is-promo';
        }

        return JsonResponse::create(["multiCheckBox" => $multiCheckBox, 'simpleCheckBox' => $simpleCheckBox]);
    }

    protected function getCheckedCriteria(&$checkedBox, $criteriasString, $criteriaName)
    {
        $criterias = explode(',', $criteriasString);
        foreach ($criterias as $criteria) {
            $criteriaParams = explode('_', $criteria);
            $criteria = $criteriaParams[0];
            $criteriaAvailabilities = explode("|", str_replace(['(',')'], '', $criteriaParams[1]));
            $checkedBox[$criteriaName.'-'.$criteria] = $criteriaAvailabilities;
        }
    }

    protected function getCheckedChoiceParam(&$checkedBox, $paramValue, $paramName)
    {
        if (!$paramValue instanceof \Traversable) {
            $paramValue = explode(',', $paramValue);
        }

        foreach ($paramValue as $param) {
            $checkedBox[$paramName][] = $param;
        }
    }
}


