# AddSiteUbuntuNginx
Add a site to nginx on an Ubuntu server script

This script was written quickly for a client who wanted to be able to add sites to his nginx server.  Please note it is not foolproof and is missing quite a bit of error checking.  If you use this you do so at your own risk.

It creates the new nginx conf file based on a template with 2 fields.

%%hostname%%
%%aliases%%

It optionally adds a new user/password.  The client in question uses the same username across groups of sites.  If you want a new user for each site then this will do that for you.

It creates a folder in /var/www/  using the hostname.


Usage is
./addsite.php -hhostname -aalias -uusername -ppassword

Examples:
This one creates a new site using an existing username
./addsite.php -hmydomain.com -ukarlgray

This one creates a new site with the www. alias and a new username.
./addsite.php -hmydomain.com -awww.mydomain.com -ukarlgray -ppassword
