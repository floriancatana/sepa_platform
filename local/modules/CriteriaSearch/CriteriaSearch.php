<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace CriteriaSearch;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

class CriteriaSearch extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'criteriasearch';

    public function postActivation(ConnectionInterface $con = null)
    {
        if (!self::getConfigValue('is_initialized', false)) {
            $database = new Database($con);
            $database->insertSql(null, [__DIR__ . "/Config/thelia.sql"]);
            self::setConfigValue('is_initialized', true);
        }
    }

    public function getHooks()
    {
        return [
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "criteria-search.search-page",
                "title" => array(
                    "fr_FR" => "Hook page de recherche",
                    "en_US" => "Criteria search hook",
                ),
                "description" => array(
                    "fr_FR" => "Hook page de recherche",
                    "en_US" => "Criteria search hook",
                ),
                "active" => true
            ],
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "criteria-search.search-js",
                "title" => array(
                    "fr_FR" => "Hook js page de recherche",
                    "en_US" => "Criteria search js hook",
                ),
                "description" => array(
                    "fr_FR" => "Hook js page de recherche",
                    "en_US" => "Criteria search js hook",
                ),
                "active" => true
            ],
            [
                "type" => TemplateDefinition::FRONT_OFFICE,
                "code" => "criteria-search.search-css",
                "title" => array(
                    "fr_FR" => "Hook css page de recherche",
                    "en_US" => "Criteria search css hook",
                ),
                "description" => array(
                    "fr_FR" => "Hook css page de recherche",
                    "en_US" => "Criteria search css hook",
                ),
                "active" => true
            ]
        ];
    }
}
