<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SepaImporter\Import;

use Thelia\Model\ModuleQuery;

/**
 * Description of Common
 *
 * @author Catana Florin
 */
class Common {

    static function getActiveModule($moduleName)
    { 
       $module = ModuleQuery::create()->filterByCode($moduleName)->findOne();
        
       if ($module != NULL)
       {
           return $module->getActivate();
       }
        
       return 0;
    }
}
