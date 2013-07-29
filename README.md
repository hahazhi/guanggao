guanggao
========
vhost:
<VirtualHost *:80>
        <Directory "/Users/ll/project/php/ggwork/guanggao">
                AllowOverride All 
                Order allow,deny
                Allow from all 
        </Directory>
        DocumentRoot "/Users/ll/project/php/ggwork/guanggao"
        DirectoryIndex index.php index.html
        ServerName www.guanggao.com
        ErrorLog "/private/var/log/apache2/guanggao-error_log"
        CustomLog "/private/var/log/apache2/guanggao-access_log" common
        RewriteEngine On
        RewriteLogLevel 0
        RewriteRule !\.(swf|txt|js|ico|gif|jpg|png|css)$ /index.php
</VirtualHost>


host:
127.0.0.1   www.guanggao.com

ep:
http://www.guanggao.com
http://www.guanggao.com/test/


¶àÂóÁªÃË£º
http://www.duomai.com
xyx@uu116.com
xyx123456