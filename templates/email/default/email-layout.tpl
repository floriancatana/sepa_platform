{default_translation_domain domain='email.default'}
{default_locale locale={$locale}}
{declare_assets directory='assets'}
{assign var="url_site" value="{config key="url_site"}"}
{assign var="company_name" value="{config key="store_name"}"}
{if not $company_name}
    {assign var="company_name" value="{intl l='Thelia V2'}"}
{/if}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{block name="email-subject"}{/block}</title>

<style type="text/css">
{literal}
#outlook a{
    padding:0;
}
.ReadMsgBody{
    width:100%;
}
.ExternalClass{
    width:100%;
}
.yshortcuts,a .yshortcuts,a .yshortcuts:hover,a .yshortcuts:active,a .yshortcuts:focus{
    background-color:transparent !important;
    border:none !important;
    color:inherit !important;
}
body{
    margin:0;
    padding:0;
}
img{
    border:0;
    height:auto;
    line-height:100%;
    outline:none;
    text-decoration:none;
}
table,td{
    border-collapse:collapse !important;
    mso-table-lspace:0pt;
    mso-table-rspace:0pt;
}
#bodyTable,#bodyCell{
    height:100% !important;
    margin:0;
    padding:0;
    width:100% !important;
}
#bodyCell{
    padding:20px;
}
.templateContainer{
    width:600px;
}
h1{
    color:#202020;
    display:block;
    font-family:Helvetica;
    font-size:26px;
    font-style:normal;
    font-weight:bold;
    line-height:100%;
    letter-spacing:normal;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    text-align:left;
}
h2{
    color:#404040;
    display:block;
    font-family:Helvetica;
    font-size:20px;
    font-style:normal;
    font-weight:bold;
    line-height:100%;
    letter-spacing:normal;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    text-align:left;
}
h3{
    color:#606060;
    display:block;
    font-family:Helvetica;
    font-size:16px;
    font-style:normal;
    font-weight:bold;
    line-height:100%;
    letter-spacing:normal;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    text-align:left;
}
h4{
    color:#808080;
    display:block;
    font-family:Helvetica;
    font-size:12px;
    font-style:normal;
    font-weight:bold;
    line-height:100%;
    letter-spacing:normal;
    margin-top:0;
    margin-right:0;
    margin-bottom:10px;
    margin-left:0;
    text-align:left;
}
#templatePreheader{
    background-color:#fafafa;
    border-top:10px solid #fafafa;
    border-bottom:0;
}
.preheaderContent{
    color:#707070;
    font-family:Helvetica;
    font-size:10px;
    line-height:125%;
    padding-top:10px;
    padding-bottom:10px;
    text-align:left;
}
.preheaderContent a:link,.preheaderContent a:visited,.preheaderContent a .yshortcuts {
    color:#FFFFFF;
    font-weight:normal;
    text-decoration:underline;
}
#templateHeader{
    background-color:#FFFFFF;
    border-top:10px solid #fafafa;
    border-bottom:0;
}
.headerContent{
    color:#202020;
    font-family:Helvetica;
    font-size:20px;
    font-weight:bold;
    line-height:100%;
    padding-top:40px;
    padding-right:0;
    padding-bottom:20px;
    padding-left:0;
    text-align:left;
    vertical-align:middle;
}
.headerContent a:link,.headerContent a:visited,.headerContent a .yshortcuts {
    color:#E1523D;
    font-weight:normal;
    text-decoration:underline;
}
#templateBody{
    background-color:#FFFFFF;
    border-top:0;
    border-bottom:0;
}
.titleContentBlock{
    background-color:#ffffff;
    border-top:0px solid #F47766;
    border-bottom:0px solid #B14031;
}
.titleContent{
    color:#7a7a7a;
    font-family:Arial;
    font-size:24px;
    font-weight:normal;
    line-height:110%;
    padding-top:5px;
    padding-bottom:5px;
    text-align:left;
}
.bodyContentBlock{
    background-color:#FFFFFF;
    border-top:0;
    border-bottom:0px;
}
.bodyContent{
    color:#505050;
    font-family:Helvetica;
    font-size:16px;
    line-height:150%;
    padding-top:20px;
    padding-bottom:20px;
    text-align:left;
}
.bodyContent a:link,.bodyContent a:visited,.bodyContent a .yshortcuts {
    color:#E1523D;
    text-decoration:underline;
}
.templateButton{
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    background-color:#12b3a6;
    border:0;
    border-radius:5px;
}
.templateButtonContent,.templateButtonContent a:link,.templateButtonContent a:visited,.templateButtonContent a .yshortcuts {
    color:#FFFFFF;
    font-family:Helvetica;
    font-size:15px;
    font-weight:bold;
    letter-spacing:-.5px;
    line-height:100%;
    text-align:center;
    text-decoration:none;
}
.bodyContent img{
    display:inline;
    height:auto;
    max-width:600px;
}
body,#bodyTable{
    background-color:#fafafa;
}
#templateFooter{
    border-top:0;
}
.footerContent{
    color:#808080;
    font-family:Helvetica;
    font-size:10px;
    line-height:150%;
    padding-top:20px;
    text-align:left;
}
.footerContent a:link,.footerContent a:visited,.footerContent a .yshortcuts {
    color:#606060;
    font-weight:normal;
    text-decoration:underline;
}
.footerContent img{
    display:inline;
    max-width:600px;
}
@media only screen and (max-width: 480px){
    body,table,td,p,a,li,blockquote{
        -webkit-text-size-adjust:none !important;
    }

    body{
        width:auto !important;
    }

    table[class=templateContainer]{
        width:100% !important;
    }

    table[class=templateContainer]{
        max-width:600px !important;
        width:100% !important;
    }

    h1{
        font-size:24px !important;
        line-height:100% !important;
    }

    h2{
        font-size:20px !important;
        line-height:100% !important;
    }

    h3{
        font-size:18px !important;
        line-height:100% !important;
    }

    h4{
        font-size:16px !important;
        line-height:100% !important;
    }

    table[id=templatePreheader]{
        display:none !important;
    }

    img[id=headerImage]{
        height:auto !important;
        max-width:233px !important;
        width:100% !important;
    }

    td[class=headerContent]{
        font-size:20px !important;
        line-height:150% !important;
        padding-top:40px !important;
        padding-right:10px !important;
        padding-bottom:20px !important;
        padding-left:10px !important;
    }

    img[class=bodyImage]{
        height:auto !important;
        max-width:580px !important;
        width:100% !important;
    }

    td[class=titleContent]{
        font-size:20px !important;
        line-height:125% !important;
        padding-right:10px;
        padding-left:10px;
    }

    td[class=bodyContent]{
        font-size:16px !important;
        line-height:125% !important;
        padding-right:10px;
        padding-left:10px;
    }

    td[class=footerContent]{
        font-size:14px !important;
        line-height:150% !important;
        padding-right:10px;
        padding-left:10px;
    }

    td[class=footerContent] a{
        display:block !important;
    }
}

.preheaderContent a:link,.preheaderContent a:visited,.preheaderContent a .yshortcuts{
    color:#12b3a6;
}
.footerContent a:link,.footerContent a:visited,.footerContent a .yshortcuts{
    color:#ffffff;
}
.bodyContent a:link,.bodyContent a:visited,.bodyContent a .yshortcuts{
    color:#12b3a6;
    text-decoration:none;
}
.templateButtonContent,.templateButtonContent a:link,.templateButtonContent a:visited,.templateButtonContent a .yshortcuts{
    font-weight:normal;
}

body {margin: 0 !important; padding: 0; background-color: #ffffff;}
table {border-spacing: 0;font-family: sans-serif; color: #333333;}
td {padding: 0;}
th {padding: 0;}
img {border: 0;}
div[style*="margin: 16px 0"] { margin:0 !important;}
.wrapper {width: 100%;table-layout: fixed; -webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;}
.webkit {max-width: 800px; margin: 0 auto;}
.outer {margin: 0 auto; width: 100%; max-width: 800px;}
.full-width-image img {width: 100%; max-width: 800px; height: auto;}
caption {width: 50%; display: block;margin-top: 15px;margin-bottom: 15px;}
.cap1{float: left;}
.cap2{float: right;}
.full-width-text-title{width: 100%; max-width: 800px; background-color: #ffffff;margin-bottom: 20px; display:block; color: #12b3a6; font-size: 26px; text-align: center;}
.full-width-text-title a{color: #12b3a6; text-decoration: none; font-weight: bold;}
.full-width-text, .full-width-text-link{ text-align: center;}
.full-width-text-subtitle {
	font-size: 16px; 
	color: #12b3a6;
	font-weight: bold;
}
.social-media-icons {
	text-align: center;
}

.social-media-icons img{margin-left: 10px; margin-right: 10px; margin-top: 30px; margin-bottom: 30px; width:24px; height: 24px;}
.social-media-icons a{text-decoration: none;}
.mcnTextContent{ text-align: center; }
.headerContent img {
	width: 100%;
}

.full-width-text-link {
	margin-top: 50px;
	display: block;
}

.full-width-text-link a {
	font-size: 16px;
    font-weight: bold;
}

a {
	color: #12b3a6;
}
{/literal}
</style>
{hook name="email-html.layout.css"}
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="margin: 0;padding: 0;background-color: #fafafa;">
<center>
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;margin: 0;padding: 0;background-color: #fafafa;border-collapse: collapse !important;height: 100% !important;width: 100% !important;">
        <tr>
            <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                    {block name="pre-header"}
                    <tr>
                        <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templatePreheader" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;background-color: #fafafa;border-top: 10px solid #fafafa;border-bottom: 0;border-collapse: collapse !important;">
                                <tr>
                                    <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                                        <table border="0" cellpadding="0" cellspacing="0" class="templateContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 600px;border-collapse: collapse !important;">
                                            <tr>
                                                <td valign="top" class="preheaderContent" style="text-align: left; mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #707070;font-family: Helvetica;font-size: 10px;line-height: 125%;padding-top: 10px;padding-bottom: 10px;text-align: left;border-collapse: collapse !important;">
                                                    {block name="email-intro"}{intl l="Juhu HADI App hat ein Investment!"}{/block}
                                                </td>

                                                <td valign="top" class="preheaderContent" style="text-align: right; padding-left: 20px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #707070;font-family: Helvetica;font-size: 10px;line-height: 125%;padding-top: 10px;padding-bottom: 10px;text-align: left;border-collapse: collapse !important;" width="200">
                                                    {block name="browser"}{intl l="Email not displaying correctly?"}<br><a href="{config key="url_site"}?view=email/register" target="_blank" style="color: #12b3a6;font-weight: normal;text-decoration: underline;">{intl l="View it in your browser"}</a>.{/block}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {/block}

                    {block name="logo-header"}
                    <tr>
                        <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateHeader" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;background-color: #FFFFFF;border-top: 10px solid #fafafa;border-bottom: 0;border-collapse: collapse !important;">
                                <tr>
                                    <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                                        <table border="0" cellpadding="0" cellspacing="0" class="templateContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 600px;border-collapse: collapse !important;">
                                            <tr>
                                                <td class="headerContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #202020;font-family: Helvetica;font-size: 20px;font-weight: bold;line-height: 100%;padding-top: 40px;padding-right: 0;padding-bottom: 20px;padding-left: 0;text-align: left;vertical-align: middle;border-collapse: collapse !important;">
                                                    {local_media type="logo"}
                                                        <a href="https://hadiapp.at/">
                            								<img src="{image file='assets/img/mail_header.png'}" alt="" />
                        								</a>
                                                    {/local_media}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {/block}
                    <tr>
                        <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;background-color: #FFFFFF;border-top: 0;border-bottom: 0;border-collapse: collapse !important;">
                                <tr>
                                    <td align="center" valign="top" style="padding-top: 20px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                                        <table border="0" cellpadding="0" cellspacing="0" class="templateContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 600px;border-collapse: collapse !important;">
                                            <tr>
                                                <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                                                    <table border="0" cellpadding="10" cellspacing="0" width="100%" class="titleContentBlock" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;background-color: #ffffff;border-top: 0px solid #F47766;border-bottom: 0px solid #B14031;border-collapse: collapse !important;">
                                                        <tr>
                                                            <td valign="top" class="titleContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #7a7a7a;font-family: Arial;font-size: 24px;font-weight: normal;line-height: 110%;padding-top: 5px;padding-bottom: 5px;text-align: left;border-collapse: collapse !important;">
                                                                {block name="email-title"}{/block}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" valign="top" style="padding-bottom: 40px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                                                    <table border="0" cellpadding="10" cellspacing="0" width="100%" class="bodyContentBlock" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;background-color: #FFFFFF;border-top: 0;border-collapse: collapse !important;">
                                                        {block name="image-header"}
                                                        <!--  <tr>
                                                            <td class="bodyContent" style="padding-bottom: 20px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #505050;font-family: Helvetica;font-size: 16px;line-height: 150%;padding-top: 20px;text-align: left;border-collapse: collapse !important;">
                                                                {local_media type="banner"}
                                                                <img class="bodyImage" src="{$MEDIA_URL}" alt="" border="0" style="border: 0px none;border-color: ;border-style: none;border-width: 0px;margin: 0;padding: 0;line-height: 100%;outline: none;text-decoration: none;display: inline;max-width: 600px;">
                                                                {/local_media}
                                                            </td>
                                                        </tr> -->
                                                        {/block}
                                                        <tr>
                                                            <td valign="top" class="bodyContent" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #505050;font-family: Helvetica;font-size: 14px;line-height: 150%;padding-top: 0px;padding-bottom: 20px;text-align: left;border-collapse: collapse !important;">
                                                                {block name="email-content"}{/block}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse !important;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateFooter" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-top: 3px solid #EAEAEA;border-collapse: collapse !important;">
                               	<tr class="social-media-icons">
             						<td style="text-align: center; border-bottom: 2px solid #EAEAEA; width: 600px; display: inline-block;">
                 						<a href="https://www.facebook.com/hadiapp/">
                     						<img src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-facebook-48.png" alt="" />
                 						</a>
                 						<a href="https://www.instagram.com/hadi_app/">
                     						<img src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-instagram-48.png" alt="" />
                 						</a>
                 						<a href="https://vimeo.com/user43044245">
                     						<img src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-vimeo-48.png" alt="" />
                 						</a>
             						</td>
         						</tr>
         						<tr>
             						<td class="mcnTextContent" style="padding: 0 18px 9px 18px" valign="top">
                 						<br><br>
                 						<em>Copyright © {$smarty.now|date_format:"%Y"} {intl l="HADISHOP"}, All rights reserved.</em><br> 
                 						<br><br>
                 						{block name="footer-content"}
                 							<strong>Our mailing address is:</strong><br><a href="mailto:support@hadishop.eu">support@hadishop.eu</a><br><br> 
                 						{/block}
             						</td>
         						</tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</center>
</body>
</html>