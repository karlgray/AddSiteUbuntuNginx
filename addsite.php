#!/usr/bin/php -q
<?php

error_reporting(0);
if ($argc <=1) {
  echo "No parameters given\n./addsite.php -h<hostname> -a<alias> -u<username> -p<password>\n";
  exit;
  }

# Parse command line options
$options = getopt("h:a::u:p::");

# extract options
$user=$options['u'];
$hostname=$options['h'];
$aliases=$options['a'];
$passwd=$options['p'];

$templatename="nginx.tmpl";

# FUNCTIONS
function adduser($username){
        ##  Add add user code here.
        }

# Is hostname valid?
function is_hostname_valid($hostname)
{
    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $hostname) //valid chars check
            && preg_match("/^.{1,253}$/", $hostname) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $hostname)   ); //length of each label
}


# START OF MAIN() CODE

# Is hostname valid?
echo "Checking if hostname appears valid\n";
if (!(is_hostname_valid($hostname))) {
        echo "\033[31mHostname appears to be invalid please check and retry\n\033[0m\n";
        exit;
        } else {
                Echo "Hostname appears to be \033[32mok\n\033[0mI have set the path to be \033[36m/var/www/$hostname\n\033[0m";
                $hostpath="/var/www/$hostname";
                }

# Create folder structure.

exec ("mkdir /var/www/$hostname");

# Check if user exists already
#Is username valid?


echo "Checking to see if user \033[36m$user\033[0m exists already\n";
$cmd="awk -F\":\" '{ print $1 }' /etc/passwd | grep -x '$user'";
if (($result=exec($cmd))==$user) {
        echo "User $user exists\n";
        } else {
                echo "Adding username $user and setting their home folder to $path\n";
                exec ("adduser $user --home $hostpath --disabled-login --gecos \"nginx site user\" --no-create-home >/dev/null");
                if ($passwd=='') {
                        echo "You haven't provided a password for the new user $user,  please use \npasswd $user\n to set the password";
                        } else {
                                exec ("echo \"$user:$passwd\" | chpasswd");
                                }
                }

# Change ownership of directory structure
exec ("chown $user.$user /var/www/$hostname");

# Create nginx config.

# Build aliases string;
foreach (array_unique($aliases) as $alias) {
        $aliaslist.="\tserver_name $alias;\n";
        }

$fd=fopen($templatename,'r');
$file=fread($fd,filesize($templatename));
fclose($fd);


$file=str_replace("%%hostname%%",$hostname,$file);
$file=str_replace("%%aliases%%",$aliaslist,$file);
if (!($fd=fopen("/etc/nginx/sites-enabled/$hostname.conf",'x'))) {
        echo "I was unable to create the requested file as it already exists\nPlease either remove the file or provide a new hostname\n\n";
        exit;
        }
$file=fwrite($fd,$file);
fclose($fd);

# Restart nginx config.
echo "Restarting Nginx\n";
exec ("service nginx restart");
?>
