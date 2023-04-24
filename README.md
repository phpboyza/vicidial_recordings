# vicidial_recordings
Basic Vicidial recording view / search page

N.B. This was a quick implementation and it will work out the box with very little effort.

I needed to build this to give a client, a independant of the vicidial interface, view of recordings for various campaigns on their vicidialler.
This allows you to search for recordings using the following criterea

From Date
To Date
Call Status
Phone Code
Phone Number

Please note that this does NOT have authentication so you'll have to build your own or using something like apache's htaccess
You can configure which campaign id's this interface may have access to.

Built on PHP 7.4.6, ViciBox Server v.10.0, MariaDB 10.5.8 (MySQL 8.x)

I am actively maintaining this project

**Installation**
Clone project
rename includes/config.inc.php.sample to includes/config.inc.php
edit includes/config.inc.php to suit your setup
Enjoy browsing through recordings
