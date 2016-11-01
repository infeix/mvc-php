<?php
$up ="

CREATE TABLE AnswerDB (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
parent_id INT(11) UNSIGNED DEFAULT NULL,
enabled INT(2) NOT NULL DEFAULT '1',
question_id INT(11) UNSIGNED NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=28 ;

CREATE TABLE Answer_labelDB (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
answer_id INT(11) UNSIGNED NOT NULL,
lang VARCHAR(6) NOT NULL DEFAULT 'DE',
label VARCHAR(50) NOT NULL,
FOREIGN KEY (answer_id) REFERENCES AnswerDB(id)
) ENGINE=MyISAM AUTO_INCREMENT=32 ;

# Frage 1
INSERT INTO `AnswerDB` VALUES (1, NULL, 1, 1);
INSERT INTO `AnswerDB` VALUES (2, NULL, 1, 1);
INSERT INTO `AnswerDB` VALUES (3, NULL, 1, 1);
INSERT INTO `Answer_labelDB` VALUES (1, 1, 'DE', 'Anfänger');
INSERT INTO `Answer_labelDB` VALUES (2, 1, 'EN', 'Beginner');
INSERT INTO `Answer_labelDB` VALUES (3, 2, 'DE', 'Fortgeschrittener');
INSERT INTO `Answer_labelDB` VALUES (4, 2, 'EN', 'Advanced');
INSERT INTO `Answer_labelDB` VALUES (5, 3, 'DE', 'Profi');

# Frage 2
INSERT INTO `AnswerDB` VALUES (4, NULL, 1, 2);
INSERT INTO `AnswerDB` VALUES (5, NULL, 1, 2);
INSERT INTO `AnswerDB` VALUES (6, NULL, 1, 2);
INSERT INTO `AnswerDB` VALUES (7, NULL, 1, 2);
INSERT INTO `AnswerDB` VALUES (8, NULL, 1, 2);
INSERT INTO `Answer_labelDB` VALUES (6, 4, 'DE', 'Image');
INSERT INTO `Answer_labelDB` VALUES (7, 4, 'EN', 'Image');
INSERT INTO `Answer_labelDB` VALUES (8, 5, 'DE', 'Preis');
INSERT INTO `Answer_labelDB` VALUES (9, 5, 'EN', 'Price');
INSERT INTO `Answer_labelDB` VALUES (10, 6, 'DE', 'Funktionalität');
INSERT INTO `Answer_labelDB` VALUES (11, 7, 'DE', 'Testberichte');
INSERT INTO `Answer_labelDB` VALUES (12, 8, 'DE', 'Meinungen');

# Frage 3
INSERT INTO `AnswerDB` VALUES (9, NULL, 1, 3);
INSERT INTO `AnswerDB` VALUES (10, NULL, 1, 3);
INSERT INTO `AnswerDB` VALUES (11, NULL, 1, 3);
INSERT INTO `AnswerDB` VALUES (12, NULL, 1, 3);
INSERT INTO `Answer_labelDB` VALUES (13, 9, 'DE', 'keines');
INSERT INTO `Answer_labelDB` VALUES (14, 10, 'DE', '1-3');
INSERT INTO `Answer_labelDB` VALUES (15, 11, 'DE', '3-5');
INSERT INTO `Answer_labelDB` VALUES (16, 12, 'DE', 'mehr als 5');

# Frage 4
INSERT INTO `AnswerDB` VALUES (13, NULL, 1, 4);
INSERT INTO `AnswerDB` VALUES (14, NULL, 1, 4);
INSERT INTO `AnswerDB` VALUES (15, NULL, 1, 4);
INSERT INTO `AnswerDB` VALUES (16, NULL, 1, 4);
INSERT INTO `AnswerDB` VALUES (17, NULL, 1, 4);
INSERT INTO `Answer_labelDB` VALUES (17, 13, 'DE', 'Foto Produkte');
INSERT INTO `Answer_labelDB` VALUES (18, 14, 'DE', 'Video Produkte');
INSERT INTO `Answer_labelDB` VALUES (19, 15, 'DE', 'Musik Produkte');
INSERT INTO `Answer_labelDB` VALUES (20, 16, 'DE', 'Online Produkte');
INSERT INTO `Answer_labelDB` VALUES (21, 17, 'DE', 'Games');

# Frage 5
INSERT INTO `AnswerDB` VALUES (18, NULL, 0, 5);
INSERT INTO `AnswerDB` VALUES (19, 18, 1, 5);
INSERT INTO `AnswerDB` VALUES (20, 18, 1, 5);
INSERT INTO `AnswerDB` VALUES (21, 18, 1, 5);
INSERT INTO `AnswerDB` VALUES (22, 18, 1, 5);
INSERT INTO `AnswerDB` VALUES (23, NULL, 1, 5);
INSERT INTO `Answer_labelDB` VALUES (22, 18, 'DE', 'Ja');
INSERT INTO `Answer_labelDB` VALUES (23, 19, 'DE', 'Modem');
INSERT INTO `Answer_labelDB` VALUES (24, 20, 'DE', 'ISDN');
INSERT INTO `Answer_labelDB` VALUES (25, 21, 'DE', 'DSL');
INSERT INTO `Answer_labelDB` VALUES (26, 22, 'DE', 'Sonstige');
INSERT INTO `Answer_labelDB` VALUES (27, 23, 'DE', 'Nein');

# Frage 6
INSERT INTO `AnswerDB` VALUES (24, NULL, 1, 6);
INSERT INTO `AnswerDB` VALUES (25, NULL, 1, 6);
INSERT INTO `AnswerDB` VALUES (26, NULL, 1, 6);
INSERT INTO `AnswerDB` VALUES (27, NULL, 1, 6);
INSERT INTO `Answer_labelDB` VALUES (28, 24, 'DE', 'unter 18');
INSERT INTO `Answer_labelDB` VALUES (29, 25, 'DE', '18 - 25');
INSERT INTO `Answer_labelDB` VALUES (30, 26, 'DE', '26 - 40');
INSERT INTO `Answer_labelDB` VALUES (31, 27, 'DE', 'über 40');


";
$down = "

DROP TABLE `Answer_labelDB`;
DROP TABLE `AnswerDB`;

";