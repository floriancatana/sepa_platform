<?php
namespace Keyword\Controller\Front;

use Keyword\Model\Map\KeywordTableMap;
use Keyword\Model\Map\ProductAssociatedKeywordTableMap;
use Keyword\Model\ProductAssociatedKeywordQuery;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Controller\Front\BaseFrontController;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KeywordController
 *
 * @author Catana Florin
 */
class KeywordController extends BaseFrontController
{
    public static function getKeyword($idProd)
    {
        $keyword = ProductAssociatedKeywordQuery::create()                
                ->addJoin(ProductAssociatedKeywordTableMap::KEYWORD_ID, KeywordTableMap::ID, Criteria::INNER_JOIN)
                ->withColumn(KeywordTableMap::CODE, 'CODE' )
                ->where(ProductAssociatedKeywordTableMap::PRODUCT_ID.' = ?', $idProd, PDO::PARAM_STR)
                ->findOne();
        
        return $keyword ? $keyword->getVirtualColumn('CODE') : NULL;
    }
}
