# lgsm-ui

Automated deployment of linux game servers via web interface with VirtualBox backend.

#Architecture:

LAMP DooPHP Web Application (/app folder)
- Linux Game Server Managers Script Suite (https://github.com/dgibbs64/linuxgsm)
- Headless VirtualBox + SOAP API (https://www.virtualbox.org/wiki/Linux_Downloads)
- Gearman Job Queue (http://gearman.org/)

#Libraries:
- phpVirtualBox SOAP wrapper 
- phpspeclib SSH2
- PHP-Source-Query (https://github.com/xPaw/PHP-Source-Query)

#Deployment Workflow:

Clone Base Virtual Machine
Resize VM
Start VM
Wait for IPv4 Address on primary network interface
SSH into VM
 - git clone lgsm repo
 - cd game/script auto-install
 - cd game/script start

#Quick start using Ubuntu 14.04 LTS for both Host and Guest OS

##Install VirtualBox on bare metal Ubuntu 14.04 servers that you want to deploy dedicated linux game servers
```
sudo su root
echo 'deb http://download.virtualbox.org/virtualbox/debian trusty contrib' >> /etc/apt/sources.list
apt-get update
apt-get install virtualbox-5.0
adduser vbox (generate random password for account http://passwordsgenerator.net/)
```

##Install virtualbox extensions
```
wget http://download.virtualbox.org/virtualbox/5.0.22/Oracle_VM_VirtualBox_Extension_Pack-5.0.22-108108.vbox-extpack (check same version as your virtualbox install)
VBoxManage extpack install Oracle_VM_VirtualBox_Extension_Pack-5.0.22-108108.vbox-extpack 

echo 'VBOXWEB_USER=vbox' > /etc/default/virtualbox
echo 'VBOXWEB_HOST=0.0.0.0' >> /etc/default/virtualbox (required for remote connectivity)

/etc/init.d/vboxdrv start (Kernel Driver)
/etc/init.d/vboxweb-service start (SOAP API)
```

##Installing LAMP + gearmand job server

```
apt-get install mariadb-server php5-mysql php-pear apache2 php5 libapache2-mod-php5  gearman-job-server git
a2enmod rewrite
mysql_secure_installation (optional)
```

##Install PHP App

```
cd /var/www/html
git clone https://github.com/hurdad/lgsm-ui
cd lgsm-ui
```

##Install database, requires root and password
```
cd db/ && php install_db.php
```

##edit httpd.conf to allow override so .htaccess works
nano /etc/apache2/apache2.conf 

```
<Directory /var/www/>

        Options Indexes FollowSymLinks

        AllowOverride All 

        Require all granted

</Directory>
```

##apache reload
```
/etc/init.d/apache2 reload
```

## Configure phpvitualbox optional
```
cd lgsm-ui/includes/phpvirtualbox
nano config.php
```

## Update vbox password
```
/* Username / Password for system user that runs VirtualBox */
var $username = 'vbox';
var $password = 'pass';
```

## Open web browser (phpvirtualbox(
http://localhost/lgsm-ui/include/phpvirtualbox/
login admin/admin

## Open in web browser
http://localhost/lgsm-ui/app/

### Credentials see (app/protected/configure/routes.conf.php)
deploy and admin login: admin/admin

##  Configure via UI
 - Add Localhost Virtualbox Server
 - Add Localhost Gearman Server

## Start gearman workers (admin -> gearman workers)
```
cd /var/www/html/lgsm-ui/app
php cli.php check_workers
```

#Create Ubuntu 14.04 Base Image in your virtualbox enviroment

upload ubuntu-14.04.4-server-amd64.iso  vbox account
create new virtualmachine  with phpvirtualbox gui

http://localhost/lgsm-ui/include/phpvirtualbox
login admin/admin

install ubuntu 14.04 LTS server amd64 as new virtual machine named 'Ubuntu 14.04 LTS x64 lgsm'

set networking to 'Bridged Adapter' and select active interface

reboot vm

```
sudo su root
apt-get update
apt-get dist-upgrade
reboot (if kernel was updated.. linux-image-xx)
```

```
sudo su root
dpkg --add-architecture i386;
apt-get install lib32gcc1 libstdc++6:i386 build-essential module-assistant git openssh-server
```

## Mount Guest Additions ISO from phpVirtualBox or command line

## Install guest additions on vm
```
mount /dev/cdrom /mnt              # or any other mountpoint
cd /mnt
./VBoxLinuxAdditions.run
reboot
```
## Add lgsm user (if you havnt already)
```
adduser lgsm
```
## Add to /etc/sudoers with passwordless enabled
```
lgsm	ALL=(ALL)	NOPASSWD: ALL
```
## Add ssh keys (optional)
```
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
cat ~/.ssh/id_rsa.pub
```
add to github sshkeys
## Check glibc version
```
ldd --version
```

##shutdown
```
shutdown -h now
```

#Logs
##check gearman logs for any deploy errors
app/protected/log/




