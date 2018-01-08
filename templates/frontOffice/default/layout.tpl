<!doctype html>
<!--
 ______   __  __     ______     __         __     ______
/\__  _\ /\ \_\ \   /\  ___\   /\ \       /\ \   /\  __ \
\/_/\ \/ \ \  __ \  \ \  __\   \ \ \____  \ \ \  \ \  __ \
   \ \_\  \ \_\ \_\  \ \_____\  \ \_____\  \ \_\  \ \_\ \_\
    \/_/   \/_/\/_/   \/_____/   \/_____/   \/_/   \/_/\/_/


Copyright (c) OpenStudio
email : info@thelia.net
web : http://www.thelia.net

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the
GNU General Public License : http://www.gnu.org/licenses/
-->

{* Declare assets directory, relative to template base directory *}
{declare_assets directory='assets/dist'}
{* Set the default translation domain, that will be used by {intl} when the 'd' parameter is not set *}
{default_translation_domain domain='fo.default'}

{* -- Define some stuff for Smarty ------------------------------------------ *}
{config_load file='variables.conf'}
{block name="init"}{/block}
{block name="no-return-functions"}{/block}
{assign var="store_name" value={config key="store_name"}}
{assign var="store_description" value={config key="store_description"}}
{assign var="lang_code" value={lang attr="code"}}
{assign var="lang_locale" value={lang attr="locale"}}
{if not $store_name}{assign var="store_name" value={intl l='Thelia V2'}}{/if}
{if not $store_description}{assign var="store_description" value={$store_name}}{/if}

{* paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither *}
<!--[if lt IE 7 ]><html class="no-js oldie ie6" lang="{$lang_code}"> <![endif]-->
<!--[if IE 7 ]><html class="no-js oldie ie7" lang="{$lang_code}"> <![endif]-->
<!--[if IE 8 ]><html class="no-js oldie ie8" lang="{$lang_code}"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="{$lang_code}" class="no-js"> <!--<![endif]-->
<head>
    {hook name="main.head-top"}
    {* Test if javascript is enabled *}
    <script>(function(H) { H.className=H.className.replace(/\bno-js\b/,'js') } )(document.documentElement);</script>

    <meta charset="utf-8">

    {* Page Title *}
    <title>{block name="page-title"}{strip}{if $page_title}{$page_title}{elseif $breadcrumbs}{foreach from=$breadcrumbs|array_reverse item=breadcrumb}{$breadcrumb.title|unescape} - {/foreach}{$store_name}{else}{$store_name}{/if}{/strip}{/block}</title>

    {* Meta Tags *}
    <meta name="generator" content="{intl l='Thelia V2'}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    {block name="meta"}
        <meta name="description" content="{if $page_description}{$page_description}{else}{$store_description|strip|truncate:120}{/if}">
    {/block}

    {stylesheets file='assets/dist/css/thelia.min.css'}
        <link rel="stylesheet" href="{$asset_url}">
    {/stylesheets}
    {*
     If you want to generate the CSS assets on the fly, just replace the stylesheet inclusion above by the following.
     Then, in your back-office, go to Configuration -> System Variables and set process_assets to 1.
     Now, when you're accessing the front office in developpement mode (index_dev.php)  the CSS is recompiled when a
     change in the source files is detected.

     See http://doc.thelia.net/en/documentation/templates/assets.html#activate-automatic-assets-generation for details.

    {stylesheets file='assets/src/less/thelia.less' filters='less'}
        <link rel="stylesheet" href="{$asset_url}">
    {/stylesheets}

    *}

    {hook name="main.stylesheet"}

    {block name="stylesheet"}{/block}

    {* Favicon *}
    {* PNG file favicons are not supported by IE 10 and lower. In this case, we use the default .ico file in the template. *}

    <!--[if lt IE 11]>
    <link rel="shortcut icon" type="image/x-icon" href="{image file='assets/dist/img/favicon.ico'}" />
    <![endif]-->

    {local_media type="favicon" width=32 height=32}
    <link rel="icon" type="{$MEDIA_MIME_TYPE}" href="{$MEDIA_URL}" />
    {/local_media}

    {* Feeds *}
    <link rel="alternate" type="application/rss+xml" title="{intl l='All products'}" href="{url path="/feed/catalog/%lang" lang=$lang_locale}" />
    <link rel="alternate" type="application/rss+xml" title="{intl l='All contents'}" href="{url path="/feed/content/%lang" lang=$lang_locale}" />
    <link rel="alternate" type="application/rss+xml" title="{intl l='All brands'}"   href="{url path="/feed/brand/%lang" lang=$lang_locale}" />
    {block name="feeds"}{/block}

    {* HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries *}
    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    {javascripts file="assets/dist/js/vendors/html5shiv.min.js"}
        <script>window.html5 || document.write('<script src="{$asset_url}"><\/script>');</script>
    {/javascripts}

    <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js"></script>
    {javascripts file="assets/dist/js/vendors/respond.min.js"}
        <script>window.respond || document.write('<script src="{$asset_url}"><\/script>');</script>
    {/javascripts}
    <![endif]-->

    {hook name="main.head-bottom"}
</head>
<body class="{block name="body-class"}{/block}" itemscope itemtype="http://schema.org/WebPage">
    {hook name="main.body-top"}

    <!-- Accessibility -->
    <a class="sr-only" href="#content">{intl l="Skip to content"}</a>

    <div class="page" role="document">

        <div class="header-container " itemscope itemtype="http://schema.org/WPHeader">
            {hook name="main.header-top"}
            <div class="navbar navbar-default navbar-secondary" itemscope itemtype="http://schema.org/SiteNavigationElement">
                <div class="container container-hadi">
                    <div class="row hotline_row">
                        <div class="hotline_content col-lg-offset-2 col-lg-10 row">
                            <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12 row row-eq-height">
                                <div class="hotline_phone_wrapper col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                    <span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
                                    <span class="hotline_phone"> HADI Hotline +43 664 4083452 </span>
                                </div>
                                <div class="hotline_partners col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="dropdown">
                                        <span class="phonebook" aria-hidden="true" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ></span>
                                            Partner Hotlines
                                            <span class="caret"></span>
                                        </span>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="#">Partner 1</a></li>
                                            <li><a href="#">Partner 2</a></li>
                                            <li><a href="#">Partner 3</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="header_wrapper row is-table-row"> -->
                    <div class="header_wrapper row equal row-eq-height is-table-row">
                        <div class="col-1 col-lg-2 col-md-3 col-sm-12 col-xs-12">
                            <div class="logo header_section_1">
                                <a href="{navigate to="index"}" title="{$store_name}" class="img-responsive">
                                    {local_media type="logo"}
                                        <img src="{$MEDIA_URL}" alt="{$store_name}" class="img-responsive">
                                    {/local_media}
                                </a>
                            </div>
                        </div>
                        <div class="col-2 col-lg-10 col-md-9 col-sm-12 col-xs-12">
                            <!-- Create Container structure -->
                            <div class=" header_section_2">
                                {ifhook rel="main.navbar-secondary"}
                                    {* Place everything within .nav-collapse to hide it until above 768px *}
                                    <nav class="navbar-collapse collapse nav-secondary nav-secondary-hadi row row-eq-height" role="navigation" aria-label="{intl l="Secondary Navigation"}">
                                       {hook name="main.navbar-secondary"}
                                    </nav>
                                {/ifhook}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- <header class="container" role="banner"> -->
            <header class="container-fluid" role="banner">
                <div class="header row">
           {*         <h1 class="logo container hidden-xs">
                        <a href="{navigate to="index"}" title="{$store_name}">
                            {local_media type="logo"}
                            <img src="{$MEDIA_URL}" alt="{$store_name}">
                            {/local_media}
                        </a>
                    </h1>*}
                    {hook name="main.navbar-primary"}
                </div>
            </header><!-- /.header -->

            {hook name="main.header-bottom"}
        </div><!-- /.header-container -->

        <main class="main-container" role="main">
            {hook name="main.content-top"}
            <div class="container">
{*                {hook name="main.content-top"}*}
                {block name="breadcrumb"}{include file="misc/breadcrumb.tpl"}{/block}
                <div id="content">{block name="main-content"}{/block}</div>
                {hook name="main.content-bottom"}
            </div><!-- /.container -->
        </main><!-- /.main-container -->

        <section class="footer-container" itemscope itemtype="http://schema.org/WPFooter">

            {ifhook rel="main.footer-body"}
                <section class="footer-block">
                    <div class="container">
                        <div class="blocks row">
                            {hookblock name="main.footer-body"  fields="id,class,title,content"}
                            {$step=1}
                            {forhook rel="main.footer-body"}
                            	{if $step==1}
                                <div class="col col-sm-3 col-xs-12">
                                    <section {if $id} id="{$id}"{/if} class="block {if $class} block-{$class}{/if}">
                                        <div class="block-heading"><h3 class="block-title">{$title}</h3></div>
                                        <div class="block-content">
                                            {$content nofilter}
                                        </div>
                                    </section>
                                </div>
                                {elseif $step!=1 && $step!=5 && $step!=6}
                                <div class="col col-sm-2 col-xs-4">
                                    	<section {if $id} id="{$id}"{/if} class="block {if $class} block-{$class}{/if}">
                                        <div class="block-heading"><h3 class="block-title">{$title}</h3></div>
                                        <div class="block-content">
                                            {$content nofilter}
                                        </div>
                                    </section>
                                </div>
                                {else}
                                <div class="col col-sm-3 col-xs-12 payment_safety">
                                    	<section {if $id} id="{$id}"{/if} class="block {if $class} block-{$class}{/if}">
                                        <div class="block-heading"><h3 class="block-title">{$title}</h3></div>
                                        <div class="block-content">
                                            {$content nofilter}
                                        </div>
                                    </section>
                                </div>
								{/if}
                                {$step=$step+1}
                            {/forhook}
                            {/hookblock}
                        </div>
                    </div>
                </section>
            {/ifhook}
           
			<footer class="footer-info" role="contentinfo">
				<div class="container">
					<div class="info row">
						<div class="col-lg-12"> 
							<section class="copyright">HADI SHOP &copy; {intl l="2017. All Rights Reserved."}</section>
						</div>
					</div>
				</div>
			</footer>

        </section><!-- /.footer-container -->

    </div><!-- /.page -->

    {block name="before-javascript-include"}{/block}
    <!-- JavaScript -->

    <!-- Jquery -->
    <!--[if lt IE 9]><script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> <![endif]-->
    <!--[if (gte IE 9)|!(IE)]><!--><script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script><!--<![endif]-->
    {javascripts file="assets/dist/js/vendors/jquery.min.js"}
        <script>window.jQuery || document.write('<script src="{$asset_url}"><\/script>');</script>
    {/javascripts}

    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
    {* do no try to load messages_en, as this file does not exists *}
    {if $lang_code != 'en'}
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/localization/messages_{$lang_code}.js"></script>
    {/if}

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {javascripts file="assets/dist/js/vendors/bootstrap.min.js"}
        <script>if(typeof($.fn.modal) === 'undefined') { document.write('<script src="{$asset_url}"><\/script>'); }</script>
    {/javascripts}

    {javascripts file="assets/dist/js/vendors/bootbox.js"}
        <script src="{$asset_url}"></script>
    {/javascripts}

    {hook name="main.after-javascript-include"}

    {block name="after-javascript-include"}{/block}

    {hook name="main.javascript-initialization"}
    <script>
       // fix path for addCartMessage
       // if you use '/' in your URL rewriting, the cart message is not displayed
       // addCartMessageUrl is used in thelia.js to update the mini-cart content
       var addCartMessageUrl = "{url path='ajax/addCartMessage'}";
    </script>
    {block name="javascript-initialization"}{/block}

    <!-- Custom scripts -->
    <script src="{javascript file='assets/dist/js/thelia.min.js'}"></script>

    {hook name="main.body-bottom"}
</body>
</html>
