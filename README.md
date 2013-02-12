Newzdash
========

NewzNab dashboard, based upon free Charisma bootstrap theme
	
    Based off original work titled 'Charisma' by Muhammad Usman
    Original Charisma license in doc/charisma-license.txt


NewzDash is a Dashboard application built for the newznab indexing software (newznab.com)

Installation Instructions

- ensure that the php5-svn module is installed, on ubuntu/debian you can install with 'sudo apt-get install php5-svn'. NewzDash will
  function without this but you will not see version information.
- clone the git repository: 'git clone git@github.com:tssgery/newzdash.git /var/www/newzdash'
- Configure your web server to either: Run NewzDash on a subdomain (ref #1), or run NewzDash in a sub folder (ref #2)
- Access NewzDash via your browser and start the install process


ToDo
- Add authentication to NewzDash, using users stored within newznab
- Add system information (such as memory and cpu consumption, and disk space)
- Enable newzdash version checking and automatic updates

Ref #1
Create a new vhost file, for apache thats:
/etc/apache2/sites-enabled

Ref #2
If you are wanting to put newzdash in a sub directory of newznab, you will have to modify the .htaccess file of Newznab.

Find:
RewriteRule ^(admin|install).*$ - [L]

Change to:
RewriteRule ^(admin|install|newzdash).*$ - [L]