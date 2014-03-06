# YiGG

HowTo setup YiGG.

## Releasing a new version

the release process follows two steps. You have to release a staging version first and if you are sure this version is working you can release this version to the live server.

### Staging server

add your ssh-key so the ssh-agent is able to use it

    $ ssh-add

to release the staging version go to your local yigg directory and run

    $ cap staging deploy

now you are able to test the changes on <http://staging.yigg.de>

### Live server

you can't access the live server directly, so you have to connect to the staging server

    $ ssh ec2-user@staging.yigg.de

on that server you will be able to run a deploy script to release the live version

    $ deploy/prod.sh

or you can connect to the live server with

    $ ssh/yigg_web.sh

## Installation of symfony via svn externals. (optional)

this is not really necessary because the latest working version of symfony is included in this package

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