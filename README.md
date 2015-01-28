LinkCollector
=============

Collect links created by Evernote

This linkCollector is a simple website that serves as a simpler viewer for your links you have collected via Evernote.
It is a one-person pet-project and may therefore contain bugs or horrible code. But it works, mostly.

# How to install

1. Download this folder, extract it and upload everything unto your server. I assume it knows php and has a sql-database available.
1. Open your sql-database and execute this code (it will create two tables that are needed later):
```
 CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `adress` text NOT NULL,
  `tags` text,
  `created` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `passwords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` tinytext NOT NULL,
  `password` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
```
2. Configure the file ´config.ini´ as to match your settings.
3. Download the imagefiles from the dropbox-link given within the folder ´images´ and insert them into the same folder.
4. Open the page `yourdomain.com/linkCollector/update_db.php`. This will hopefully populate your table "links" with the links given in `evernote.enex`

Now you are basically done. The rest is fine-tuning.

# Things you will want to do

## Access your page
Your page will reside in yourdomain.com/linkCollector or wherever you put the folder.

## Cn
