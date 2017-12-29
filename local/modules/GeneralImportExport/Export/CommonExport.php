<?php
namespace GeneralImportExport\Export;

use Thelia\Model\CategoryI18nQuery;
use Thelia\Model\CategoryQuery;
use Thelia\Model\ProductCategoryQuery;

class CommonExport
{
    public static function getCategoryHierarchy($productID)
    {
        $prodCategory = ProductCategoryQuery::create()
                ->findByProductId($productID);
        
        if (!empty($prodCategory[0]))
        {
            $categoryId = $prodCategory[0]->getCategoryId();
            $categoryTitle = CommonExport::getTitleCategory($categoryId);
            
            $categoryTitle = CommonExport::getParentId($categoryId, $categoryTitle);
            return $categoryTitle;
        }
        return null;
    }
    
    public static function getParentId($id, $catTitle)
    {
        
        $category = CategoryQuery::create()
        ->findById($id);
        
        $parentId = $category[0]->getParent();
        
        if ($parentId != 0) {
        	$catTitle = CommonExport::getTitleCategory($parentId)." ".$catTitle;
            return CommonExport::getParentId($parentId, $catTitle);
        }
        return $catTitle;
    }
    
    public static function getTitleCategory($id)
    {
        $title = CategoryI18nQuery::create()->findById($id);
        return $title[0]->getTitle();
    }
}

