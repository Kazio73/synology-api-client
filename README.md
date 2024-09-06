Synology FileStation Client API
=================

This is a PHP Library that consume Synology FileStation APIs. The library is being updated. A small SynologyDrive support has appeared.

* SYNO.Api :
  * connect
  * disconnect
  * getAvailableApi

* SYNO.FileStation:
  * connect
  * disconnect
  * getInfo
  * getShares
  * getObjectInfo
  * getList
  * getPathInfo
  * search
  * createFolder
  * uploadFile
  * download
  * delete
* SYNO.DriveStation:
  * connect
  * disconnect
  * getInfo
  * getTeamFolders
  * getMyDrive
  * getDir
  * createFolder
  * deleteFolder



Usage for FileStationClient Synology Api:
```php
$synology = new FileStationClient('192.168.10.5', 5000, 'http', 1);
$synology->activateDebug();
$synology->connect('admin', 'xxxx');
print_r($synology->getAvailableApi());
``` 
Usage for DriveStationClient Synology Api:
```php
$synology = new FileStationClient('192.168.10.5', 5000, 'http', 1);
$synology->activateDebug();
$synology->connect('admin', 'xxxx');
print_r($synology->getInfo('type: private || team', 'path')); 
print_r($synology->getDir('type: private || team', 'path', 'name')); 
```
