---
title: README
date: 26.05.2019 19:28:38
author: t1@wb28.de
---
# select_for_sync

1. [Deutsch](#1)
2. [English](#2)

## <a name="1"></a>1. Deutsch

Mit diesem Plugin können Sie angeben, welche Adressbücher, Kalender, Aufgabenlisten und Notizen ihrer [RoundCube](https://roundcube.net) Installation Sie mit Ihrem Handy / Smartphone synchonisieren möchten. Sie können dabei bei Ihren Addressbüchern auswählen, ob Sie alle Kontakte oder nur Kontakte mit Telefonnummern synchronisieren wollen.

**Voraussetzungen**

Um dieses Plugin nutzen zu können, benötigen Sie

* Eine funktionierende [RoundCube](https://roundcube.net) Installation.
* Unser Produkt [sync*gw](https://www.syncgw.com) installiert und konfiguriert.
* Wenn Sie Adressbücher synchronisieren möchten, dann benötigen Sie kein weiteres RoundCube Plugin.
* Wenn Sie Kalender synchronisieren möchten, dann installieren Sie bitte das [calender-Plugin](https://plugins.roundcube.net/packages/kolab/calendar).
* Wenn Sie Aufgabenlisten synchrönisieren möchten, dann installieren Sie bitte das [tasklist-Plugin](https://plugins.roundcube.net/packages/kolab/tasklist).
* Wenn Sie Notizen synchronisieren mochten, dann installieren Sie bitte das [primitivenotes-Plugin](https://plugins.roundcube.net/packages/offerel/primitivenotes).

**Installation**

* Bitte installieren das sync•gw-Plugin mit der Anweisung

  ```
  composer require syncgw/roundcube-select_for_sync
  ```

* Installieren sie optional das [calender-Plugin](https://plugins.roundcube.net/packages/kolab/calendar) mit der Anweisung

  ```
  composer require kolab/calendar
  ```
  
  **Achtung:** Wenn das Plugin verwenden wollen, um Geburtstagseinträge zu synchronisieren, dann müssen Sie einen Fehler im Plugin selbst beheben (der Fehler ist seit langer Zeit berichtet worden, aber leider bisher nicht in dre Version 3.3.3 behoben worden). Bitte öffnen Sie dazu die Datei `plugins/calendar/drivers/calendar_driver.php` und gehen zur Zeile `732` (das ist in der Funktion `get_birthday_event()`). 
  
  Bitte ändern Sie die PHP Anweisung von:
  
  ```
  if ($source && $contact_id && ($abook = $rcmail->get_address_book($source))) {
  ```

  in
  
   ```
  if ($contact_id && ($abook = $rcmail->get_address_book($source))) {
  ```


* Installieren Sie ggf. das [tasklist-Plugin](https://plugins.roundcube.net/packages/kolab/tasklist) mit der Anweisung

  ```
  composer require kolab/tasklist
  ```
  
  **Achtung:** Wenn Sie das Plugin verwenden und eine Fehlermeldung in ihrer RoundCube Log-Datei erhalten, dann überprüfen sie bitte die Datei `plugins/tasklist/config.inc.php`. Dort sollte `$config['tasklist_driver'] = 'database';` angegeben sein.

* Aktivieren Sie unser Plugin indem Sie in der Datei `config/config.inc.php` das Plugin eintragen

  ```
  $config['plugins'] = array(
	...
	'roundcube_select_for_sync',
	...
  );
  ```

* Installieren Sie ggf. das [primitivenotes-Plugin](https://plugins.roundcube.net/packages/offerel/primitivenotes) mit der Anweisung

  ```
  composer require offerel/primitivenotes 
  ```

und erstellen Sie eine `config.inc.php` Datei gemäß den Installationsanweisungen zu dem Plugin.
  
**Benutzung**

* Gehen Sie in das Menü `Einstellungen` und Konfigurieren Sie die Synchronisations-Einstellungen unter dem Menüpunkt `Synchronisationseinstellungen`.
* Anschließend können Sie die ausgewählten Daten mit Ihrem Handy / Smartphone synchronisieren.

Viel Spaß bei der Benutzung!

## <a name="2"></a>2. English

With this Plugin you can specify in your [RoundCube](https://roundcube.net) installation which address books, calendars, task lists and notes you want to synchronize with your cell phone / smart phone. For address boks you can specify whether you want to synchronize only contacts with a phone number specified or if you want to synchronize all contacts within this address book.

**Requirements**

To use this plugin, you need

* A functional [RoundCube](https://roundcube.net) installation.
* Our product [sync*gw](https://www.syncgw.com) installed and configured.
* If you want to synchronize address books, then you don't need any additional RoundCube plugin.
* If you want to synchronize calendar, then you need to install [calender plugin](https://plugins.roundcube.net/packages/kolab/calendar).
* If you want to synchronize tasklis, then you need to install [tasklist plugin](https://plugins.roundcube.net/packages/kolab/tasklist).
* If you want to synchronize notes, then you need to install [primitivenotes plugin](https://plugins.roundcube.net/packages/offerel/primitivenotes).

**Installation**

* Please install plugin with the following command 

  ```
  composer require syncgw/roundcube-select_for_sync
  ```

* Optionally install [calender plugin](https://plugins.roundcube.net/packages/kolab/calendar) 

  ```
  composer require kolab/calendar
  ```

  **Caution:** If you use the calendar plugin and you want to synchronize birthday events there is a known bug, which you need to fix yourself (bug is reported, but not fixed in versoin 3.3.3 of calendar plugin). Please open file `plugins/calendar/drivers/calendar_driver.php` and go to line `732` (that's inside function `get_birthday_event()`). 
  
  Please change the code from:
  
  ```
  if ($source && $contact_id && ($abook = $rcmail->get_address_book($source))) {
  ```

  to
  
   ```
  if ($contact_id && ($abook = $rcmail->get_address_book($source))) {
  ```

* Optionally install [tasklist plugin](https://plugins.roundcube.net/packages/kolab/tasklist)

  ```
  composer require kolab/tasklist
  ```
  
  **Caution:** If you use the plugin and receive a error message in RoundCube log file, then please check file `plugins/tasklist/config.inc.php`. There `$config['tasklist_driver'] = 'database';` should be specified.
  
* Activate our plugin by adding plugin name in file `config/config.inc.php`

  ```
  $config['plugins'] = array(
	...
	'roundcube_select_for_sync',
	...
  );
  ```
	
* Optionally install [primitivenotes plugin](https://plugins.roundcube.net/packages/offerel/primitivenotes) 

  ```
  composer require offerel/primitivenotes 
  ```

and create a `config.inc.php` file according to the installation instructions of the Plugin.
  

**Usage**

* Go to menu `Settings` and configure synchronization settings by selecting `Synchronization settings`.
* Now you're ready to synchronize your selected data with your cell phone / smart phone.

Please enjoy!

