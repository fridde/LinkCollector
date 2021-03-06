LinkCollector
=============

Collect links created by Evernote

This linkCollector is a simple website that serves as a simpler viewer for your links you have collected via Evernote.
It is a one-person pet-project and may therefore contain bugs or horrible code. But it works, mostly.

# How to install

* Download this folder, extract it and upload everything unto your server. I assume it knows php and has a sql-database available.
*  Open your sql-database and execute this code (it will create two tables that are needed later):
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
* Configure the file ´config.ini´ as to match your settings.
* Download the imagefiles from the dropbox-link given within the folder ´images´ and insert them into the same folder.
* Open the page `yourdomain.com/linkCollector/update_db.php`. This will hopefully populate your table "links" with the links given in `evernote.enex`

Now you are basically done. The rest is fine-tuning.

# Things you will want to do

## Access your page
Your page will reside in `yourdomain.com/linkCollector` or wherever you put the folder.

## Updating your database
Right now it's rather manually, although the steps can be done in a few seconds/minutes.

* Export the links you want to export as a database with the name `Evernote.enex`.
* If your file is larger than 10 MB, you may want to use the awesome python-script `enex_minimizer.py` (found in ´files´) to decrease the size of the file. See the file for instructions.
* Upload your database unto your server into the folder ´files´.
* "Visit" the page `yourdomain.com/linkCollector/update_db.php`. You are all set.

## Protecting certain links

Here comes the fun! If you have certain links that should only be visible to some certain person, you tag the link with a tag starting with `@`, i.e. `@emil`. The tagging is solely done in Evernote. If you now define a password in the sql-table "passwords" for that tag, a user can only access these links if they use the hard-to-guess-link `yourdomain.com/linkCollector/?t=@emil&p=emils_password`.
 
