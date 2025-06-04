<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} \.php$ [NC]
    RewriteRule ^ - [L]
    <Files *.php>
        Order Allow,Deny
        Allow from all
        Require all granted
    </Files>
</IfModule>
php_flag engine on
