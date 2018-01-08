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

namespace HookLinks\Hook;

use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class FrontHook
 * @package HookCurrency\Hook
 * @author Julien Chanséaume <jchanseaume@openstudio.fr>
 */
class FrontHook extends BaseHook {

    public function aboutHadi(HookRenderBlockEvent $event)
    {
        $content = trim($this->render("about_links.html"));
        if ("" != $content){
            $event->add(array(
                "id" => "links-footer-body",
                "class" => "default",
                "title" => $this->trans("Über hadi", array(), "hooklinks"),
                "content" => $content
            ));
        }
    }
    
    public function service(HookRenderBlockEvent $event)
    {
    	$content = trim($this->render("service_links.html"));
    	if ("" != $content){
    		$event->add(array(
    				"id" => "links-footer-body",
    				"class" => "default",
    				"title" => $this->trans("Service", array(), "hooklinks"),
    				"content" => $content
    		));
    	}
    }
    
    public function help(HookRenderBlockEvent $event)
    {
    	$content = trim($this->render("help_links.html"));
    	if ("" != $content){
    		$event->add(array(
    				"id" => "links-footer-body",
    				"class" => "default",
    				"title" => $this->trans("Wir helfen", array(), "hooklinks"),
    				"content" => $content
    		));
    	}
    }
    
    public function payment(HookRenderBlockEvent $event)
    {
    	$content = trim($this->render("payment.html"));
    	if ("" != $content){
    		$event->add(array(
    				"id" => "links-footer-body",
    				"class" => "default",
    				"title" => $this->trans("Zahlungsarten", array(), "hooklinks"),
    				"content" => $content
    		));
    	}
    }
    
    public function safety(HookRenderBlockEvent $event)
    {
    	$content = trim($this->render("safety.html"));
    	if ("" != $content){
    		$event->add(array(
    				"id" => "links-footer-body",
    				"class" => "default",
    				"title" => $this->trans("Sicherheit", array(), "hooklinks"),
    				"content" => $content
    		));
    	}
    }

} 