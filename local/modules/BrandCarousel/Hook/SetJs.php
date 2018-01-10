<?php

/* * ********************************************************************************** */
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/* * ********************************************************************************** */

namespace BrandCarousel\Hook;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class SetJs extends BaseHook {

    public function setJs(HookRenderEvent $event) {
        $content = $this->addJS('assets/js/brand_carousel.js');
        $event->add($content);
    }
    
    public function setCss(HookRenderEvent $event) {
        $content = $this->addCss('assets/css/style.css');
        $event->add($content);
    }

}
