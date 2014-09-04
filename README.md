Gun
===

Gun is a lead management platform.

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
cd path/to/gun/init/
./install.sh
```

Configuration
===

Example configuration options are located in the path/to/gun/init/config.ini.sample.  Copy this file to path/to/gun/init/config.ini and configure the application to your liking.  
You can set your database connection information, session key, ftp information and other settings in config.ini

VirtualHost
===

A sample virtualhost file is provided for you in path/to/gun/init/config/virtualhost.  You can copy this to your /etc/httpd/conf.d/ folder and configure the ServerNames to your liking.  Make sure you have NameVirtualHost enabled in your /etc/httpd/conf/httpd.conf file.

```
cp path/to/gun/init/config/virtualhost /etc/httpd/conf.d/gun.vhost.conf
```


Upgrading
===
As features are released, you can keep your data structure up to date by running the included upgrade script located in path/to/gun/init.

```
cd path/to/gun/init/
./upgrade.sh
```