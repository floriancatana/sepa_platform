<?php

namespace HookFront\Hook\Front;

use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class FrontHook extends BaseHook
{
	public function displayCartWishList(HookRenderEvent $event)
	{
		$product["product_id"] = $event->getArgument('product_id');
		$product["virtual"] = $event->getArgument('virtual');
		$product["quantity"] = $event->getArgument('quantity');
		$product["pse_count"] = $event->getArgument('pse_count');
		$product["pse"] = $event->getArgument('pse');
		$product["type_products"] = $event->getArgument('type_products');
		
		$event->add($this->render(
				'cart-wish-list.html' ,
				$product
				));
	}
	
	public function homepageContent(HookRenderEvent $event)
	{
		$event->add($this->render(
				'homepage.html'
				));
	}
	
	public function displayBanner(HookRenderEvent $event)
	{
		$event->add($this->render(
				'hp-banner.html'
				));
	}
}
