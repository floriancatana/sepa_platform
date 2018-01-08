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

namespace HookNewsletter;

use Thelia\Module\BaseModule;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;

class HookNewsletter extends BaseModule
{
	public function postActivation(ConnectionInterface $con = null)
	{
		$database = new Database($con);
		$database->insertSql(null, [__DIR__ . "/Config/insert_hooks.sql"]);
		
	}
}
