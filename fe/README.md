FluxFE
===

FluxFE is a frontend lead and path management platform.

Installation
===

This assumes you are on a CentOS server with yum. First, you must create the 10gen repo in the /etc/yum.repos.d/ folder:

```
[10gen]
name=10gen Repository
baseurl=http://downloads-distro.mongodb.org/repo/redhat/os/x86_64
enabled=1
gpgcheck=0
```

Second, you need the most remi repository in the /etc/yum.repos.d folder:

```
wget http://dl.fedoraproject.org/pub/epel/6/i386/epel-release-6-8.noarch.rpm
wget http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
rpm -Uvh remi-release-6*.rpm epel-release-6*.rpm
```

Having the access to the latest remi repository is the easiest way to install the php mongo driver (php-pecl-mongo). Assuming you have the remi & 10gen repos active, to install mongo client, mongo server, and the php mongo driver, just run:

```
yum install mongodb-org php-pecl-mongo
```

You will need php 5.5, and it is suggested that you run opcache as well. Make sure the php-55 repo is enabled in remi, and run
```
yum install php php-opcache --enablerepo=remi-php55
```

After installing mongo client, mongo server, the php mongo driver, and php 5.5, start mongo and restart apache:

```
chkconfig mongod on
service mongod start
service httpd restart
```

After mongo has been started and the php mongo driver is active (by restarting apache after installing the php mongo driver), run the installer like so:

```
cd path/to/fluxfe/init/
./install.sh
```

Configuration
===

Example configuration options are located in the path/to/fluxfe/init/config.ini.sample.  Copy this file to path/to/fluxfe/init/config.ini and configure the application to your liking.  At this time,
fluxfe requires flux to be installed on the same server because they share the same libraries.  You will need to set the flux library path in config.ini

```
; Sets the path to the lib directory (when building an rpm, the lib folder will be included)
lib_path=/home/flux/admin/webapp/lib/
```

VirtualHost
===

A sample virtualhost file is provided for you in path/to/fluxfe/init/config/virtualhost.  You can copy this to your /etc/httpd/conf.d/ folder and configure the ServerNames to your liking.  Make sure you have NameVirtualHost enabled in your /etc/httpd/conf/httpd.conf file.

```
cp path/to/fluxfe/init/config/virtualhost /etc/httpd/conf.d/fluxfe.vhost.conf
```

Examples
===

Included in this project are several example flows.  Each flow requires the libraries of FluxFE and you can configure this in the config.ini file located in each flows' lib/ folder.

Testing
===
You should be able to test the various aspects of the frontend using the links below (after you setup your virtual hosts correctly).  Make sure you replace the campaign_key with a valid campaign key from Flux:

This url should take you to the normal redirect page, which will save the lead (sorry you'll have to search in the database for it) and redirect you to wherever the offer's redirect url points:

```
http://www.fluxrt.local/r?campaign_key=2e3ea6dc2d859a37e9fd4aa8ed2e8bec5ca9b390&state=CA&zip=92673&conversion=1
```

This url should take you to a json equivalent of the redirect page, which will save the lead and return the lead id in the json.  This page does not return the redirect url yet:

```
http://www.fluxrt.local/j?campaign_key=2e3ea6dc2d859a37e9fd4aa8ed2e8bec5ca9b390&state=CA&zip=92673&conversion=1
```

This url should take you to the debtMover example offer and show debugging information at the top:

```
http://www.debt.local/index.php?campaign_key=2e3ea6dc2d859a37e9fd4aa8ed2e8bec5ca9b390
```