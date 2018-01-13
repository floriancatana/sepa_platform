<nav class="nav-breadcrumb" role="navigation" aria-labelledby="breadcrumb-label">
    <strong id="breadcrumb-label" class="sr-only">{intl l="You are here:"}</strong>
	<div class="container">
	{if $breadcrumbs[0].detail_page }
    <ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList" >
        <li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" ><a href="{navigate to="index"}" itemprop="item">
            <span itemprop="name">{intl l="Home"}</span></a>
            <meta itemprop="position" content="1">
        </li>
        {foreach $breadcrumbs as $breadcrumb}
        {if $breadcrumb.title && !$breadcrumb.is_product_page}
            {if $breadcrumb@last}
                <li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" class="active"><span itemprop="name">{$breadcrumb.title|unescape}</span></li>
            {else}
                <li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" >
                    <a href="{$breadcrumb.url|default:'#' nofilter}"  title="{$breadcrumb.title|unescape}" itemprop="item"><span itemprop="name">{$breadcrumb.title|unescape}</span></a>
                    <meta itemprop="position" content="{$breadcrumb@key+2}">
                </li>
            {/if}
        {/if}
        {if $breadcrumb.is_product_page }
             <li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" class="backToTheRoot">
                <a href="{$breadcrumb.url|default:'#' nofilter}"  title="{$breadcrumb.title|unescape}" itemprop="item"><span itemprop="name">{$breadcrumb.title|unescape}</span></a>
                <meta itemprop="position" content="{$breadcrumb@key+2}">
            </li>
        {/if}
        {/foreach}
	</ul>
    {else}    
       
		{$product_total={count type="search_product" complex="true" feature_availability=$features attribute_availability=$attributes category=$category_id brand=$brands min_price=$price_min max_price=$price_max category_id=$category_id min_stock=$in_stock new=$new promo=$promo limit="100000" depth="10"}}
      
    	<ul class="breadcrumb col-xs-4" itemscope itemtype="http://schema.org/BreadcrumbList" >
       		<li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" ><a href="{navigate to="index"}" itemprop="item">
            	<span itemprop="name">{intl l="Home"}</span></a>
            	<meta itemprop="position" content="1">
        	</li>
        	{foreach $breadcrumbs as $breadcrumb}
        	{if $breadcrumb.title}
            	{if $breadcrumb@last}
                	<li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" class="active">
                		<span itemprop="name">{$breadcrumb.title|unescape}</span>
                		<span class="amount">{if ($product_total > 1)}{intl l="- %nb Items" nb="{$product_total}"}{else}{intl l="- %nb Item" nb="{$product_total}"}{/if}</span>
                	</li>
            	{else}
                	<li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement" >
                    	<a href="{$breadcrumb.url|default:'#' nofilter}"  title="{$breadcrumb.title|unescape}" itemprop="item"><span itemprop="name">{$breadcrumb.title|unescape}</span></a>
                    	<meta itemprop="position" content="{$breadcrumb@key+2}">
                	</li>
            	{/if}
        	{/if}
        	{/foreach}
    	</ul>
    	<div class="col-xs-4"></div>
    	<div class="toolbar toolbar-top col-xs-4" role="toolbar">
    		<div class="sorter-container clearfix">
            	<span class="view-mode">
                	<span class="view-mode-label sr-only">{intl l="Ansicht"}</span>
                	<span class="view-mode-btn">
                    	<a href="/?view=category&amp;locale=en_US&amp;category_id=4&amp;mode=grid" data-toggle="view" role="button" title="Grid" rel="nofollow" class="btn btn-default grid active"></a>
                    	<a href="/?view=category&amp;locale=en_US&amp;category_id=4&amp;mode=list" data-toggle="view" role="button" title="List" rel="nofollow" class="btn btn-default list"></a>
                	</span>
            	</span>
    		</div>
		</div>
    </ul>
    {/if}
    </div>

</nav><!-- /.nav-breadcrumb -->
