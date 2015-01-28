LinkCollector
=============

Collect links created by Evernote

This linkCollector is a simple website that serves as a simpler viewer for your links you have collected via Evernote.
It is a one-person pet-project and may therefore contain bugs or horrible code. But it works, mostly.

# How to install

1. Download this folder, extract it and upload everything unto your server. I assume it knows php and has a sql-database available.
1. Open your sql-program and execute this code:
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
