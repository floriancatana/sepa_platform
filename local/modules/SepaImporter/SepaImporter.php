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

namespace SepaImporter;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

class SepaImporter extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'sepaimporter';

    /*
     * You may now override BaseModuleInterface methods, such as:
     * install, destroy, preActivation, postActivation, preDeactivation, postDeactivation
     *
     * Have fun !
     */
    
    public function postDeactivation(ConnectionInterface $con = null)
    {
        $database = new Database($con);
        $database->insertSql(null, [
            __DIR__ . "/Config/thelia.sql"
        ]);
        return true;
    }  
}
