<?php


namespace CriteriaSearch\Hook\Admin;

use CriteriaSearch\Model\CriteriaSearchCategoryTaxRuleQuery;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class CategoryEditHook extends BaseHook
{
    public function onCategoryTabContent(HookRenderEvent $event)
    {
        $categoryTaxeRule = CriteriaSearchCategoryTaxRuleQuery::create()
            ->findOneByCategoryId($event->getArgument('id'));

        if (null !== $categoryTaxeRule) {
            $params['taxe_rule_id'] = $categoryTaxeRule->getTaxRuleId();
        }

        $params['category_id'] = $event->getArgument('id');
        $event->add($this->render(
            'criteria-search/category/category-edit.html',
            $params
        ));
    }

    public function onCategoryEditJs(HookRenderEvent $event)
    {
        $event->add($this->render(
            'criteria-search/category/category-edit-js.html'
        ));
    }

}
