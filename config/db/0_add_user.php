<?php
$up = "

#
# Tabellenstruktur für Tabelle `UserDB`
#
CREATE TABLE `UserDB` (
`CID` int(11) NOT NULL auto_increment,
`Firstname` varchar(30) NOT NULL default '',
`Lastname` varchar(30) NOT NULL default '',
`Email` varchar(80) NOT NULL default '',
`Password` varchar(32) NOT NULL default '',
PRIMARY KEY (`CID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 ;
#
# Daten für Tabelle `UserDB`
#
INSERT INTO `UserDB` VALUES (1, 'Max', 'Mustermann', 'max@mustermann.de',
'ebdb1656ffd5b099252c533e9ad42d11');
INSERT INTO `UserDB` VALUES (2, 'Rudi', 'Testmann', 'rudi@testmann.com',
'3d82c14b83ec3dad46f2f6b2fe7f09e8');

";
$down = "

DROP TABLE `UserDB`;

";