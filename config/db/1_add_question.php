<?php
$up = "

CREATE TABLE QuestionDB (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
input_type VARCHAR(30) NOT NULL DEFAULT 'text',
multi_select INT(6) NOT NULL DEFAULT 0,
sort_index INT UNSIGNED NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 ;

CREATE TABLE Question_labelDB (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
question_id INT(11) UNSIGNED NOT NULL,
lang VARCHAR(6) NOT NULL DEFAULT 'DE',
label VARCHAR(100) NOT NULL,
FOREIGN KEY (question_id) REFERENCES QuestionDB(id)
) ENGINE=MyISAM AUTO_INCREMENT=9 ;

INSERT INTO `QuestionDB` VALUES (1, 'radio', 0, 1);
INSERT INTO `Question_labelDB` VALUES (1, 1, 'DE', 'Wie schätzen Sie Ihre PC-Kenntnisse ein?');
INSERT INTO `Question_labelDB` VALUES (2, 1, 'EN', 'How do you rate your computer skills?');
INSERT INTO `QuestionDB` VALUES (2, 'checkbox', 1, 2);
INSERT INTO `Question_labelDB` VALUES (3, 2, 'DE', 'Worauf achten Sie beim Kauf von Software? (Mehrfachauswahl)');
INSERT INTO `Question_labelDB` VALUES (4, 2, 'EN', 'What is important to you when you are buying software? (Multiple choice)');

INSERT INTO `QuestionDB` VALUES (3, 'radio', 0, 3);
INSERT INTO `Question_labelDB` VALUES (5, 3, 'DE', 'Wieviele Software-Titel kauften Sie in den letzten 12 Monaten?');
INSERT INTO `QuestionDB` VALUES (4, 'sortablelist', 5, 4);
INSERT INTO `Question_labelDB` VALUES (6, 4, 'DE', 'Erstellen Sie bitte Ihre persönliche TOP5 der Art der Software, die Sie nutzen.');

INSERT INTO `QuestionDB` VALUES (5, 'radio', 0, 5);
INSERT INTO `Question_labelDB` VALUES (7, 5, 'DE', 'Verfügen Sie über einen Internet-Anschluß?');
INSERT INTO `QuestionDB` VALUES (6, 'radio', 0, 6);
INSERT INTO `Question_labelDB` VALUES (8, 6, 'DE', 'Wie alt sind Sie?');

";
$down = "

DROP TABLE `Question_labelDB`;
DROP TABLE `QuestionDB`;

";