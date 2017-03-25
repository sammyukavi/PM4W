<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>{$site_name}</title>
        <style type="text/css">
            .links a, .links a:hover{
                color: #969494;
                text-decoration: none;
            }
        </style>
    </head>
    <body style="padding: 0; margin: 0; width: 100%;background-color: #ffffff;
          ">
        <table style="width: 100%; background-color: #fff; height: 60px; margin: 0; padding: 0;">
            <tr style="margin: 0; padding: 0;">
                <td style="margin: 0; padding: 0; width: 25px;"></td>
                <td style="margin: 0; padding: 0;">
                    <a href="{$site_url}">
                        <img src="{$site_url}/assets/images/logo.png"/>
                    </a>
                </td>                
            </tr>
        </table> 
        <table style="width: 100%; background-color: #e30918; margin: 0; padding: 0;margin-bottom: 5px;">
            <tr style="width: 100%;margin: 0; padding: 0;">
                <td style="width: 100%;margin: 0; padding: 0;height: 1px;"></td>
            </tr>
        </table> 
        <table style="width: 100%; background-color: #fff;">
            <tr style="width: 100%; background-color: #fff;">               
                <td style="width: 100%; background-color: #fff; padding:25px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                    font-family: 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;
                    font-size: 14px;
                    font-style: normal;
                    font-variant: normal;
                    font-weight: 400;
                    line-height: 14px;">                    
                    {$email_content}                    
                </td>                
            </tr>
        </table> 
        <table style="width: 100%; background-color: #e30918; margin: 0; padding: 0;margin-top: 5px; margin-bottom: 5px;">
            <tr style="width: 100%;margin: 0; padding: 0;">
                <td style="width: 100%;margin: 0; padding: 0;height: 1px;"></td>
            </tr>
        </table>       
        <table style="width: 100%; background-color: #fff;">
            <tr  style="width: 100%; background-color: #fff;">                
                <td  style="width: 100%; background-color: #fff; text-align: center; font-weight: bold; color: #969494;
                     font-family: 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;
                     font-size: 14px;
                     font-style: normal;
                     font-variant: normal;
                     font-weight: 900;
                     line-height: 14px;">
                    {$company_name} | Support: {$support_email} | Support: {$support_phone_number} 
                </td>
            </tr>       
        </table> 
    </body>
</html>