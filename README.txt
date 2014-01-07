Installation of symfony via svn externals.

working with svn externals & git.
clone svn
 $ git svn clone http://svn.symfony-project.com/branches/1.3/lib lib/vendor/1.3
When a 1.4 update comes along, go to the lib/vendor/symfony-1.4 directory and execute the following command to update your copy.

 $ git svn rebase
Now take care of the svn externals by first retrieving a script that extends git�s power (based on a work by Andre Pang) and running git-svn-update-externals inside the framework directory (lib/vendor/symfony-1.4).

 $ git clone git://git-sue.git.sourceforge.net/gitroot/git-sue/git-sue lib/vendor/git-sue
 $ cd lib/vendor/1.3
 $ ../git-sue/git-svn-update-externals
Assuming you are back on your project root directory, verify your Symfony installation.

 $ lib/vendor/1.3/data/bin/symfony -V

If Symfony has been installed correctly, the version should be displayed.
Notes: