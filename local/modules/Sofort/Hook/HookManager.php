<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/
/**
 * Created by Franck Allimant, CQFDev <franck@cqfdev.fr>
 * Date: 11/01/2016 11:57
 */

namespace Sofort\Hook;

use Sofort\Sofort;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Model\ModuleConfig;
use Thelia\Model\ModuleConfigQuery;

class HookManager extends BaseHook
{
    const MAX_TRACE_SIZE_IN_BYTES = 40000;

    public function onModuleConfigure(HookRenderEvent $event)
    {
    	$logFilePath = THELIA_LOG_DIR . DS . "log-sofort-payment.txt";
    	
    	$traces = @file_get_contents($logFilePath);
    	
    	if (false === $traces) {
    		$traces = $this->translator->trans("The log file doesn't exists yet.", [], Sofort::DOMAIN);
    	} elseif (empty($traces)) {
    		$traces = $this->translator->trans("The log file is empty.", [], Sofort::DOMAIN);
    	} else {

    		if (strlen($traces) > self::MAX_TRACE_SIZE_IN_BYTES) {
    			$traces = substr($traces, strlen($traces) - self::MAX_TRACE_SIZE_IN_BYTES);
    			// Cut a first line break;
    			if (false !== $lineBreakPos = strpos($traces, "\n")) {
    				$traces = substr($traces, $lineBreakPos+1);
    			}
    	
    			$traces = $this->translator->trans(
    					"(Previous log is in %file file.)\n",
    					[ '%file' => sprintf("log".DS."%s.log", Sofort::DOMAIN) ],
    					Sofort::DOMAIN
    					) . $traces;
    		}
    	}
    	
    	$vars = ['trace_content' => nl2br($traces)  ];

    	
        if (null !== $params = ModuleConfigQuery::create()->findByModuleId(Sofort::getModuleId())) {
            /** @var ModuleConfig $param */
            foreach ($params as $param) {
                $vars[ $param->getName() ] = $param->getValue();
            }
        }

        $event->add(
            $this->render('sofort/module-configuration.html', $vars)
        );
    }
}
