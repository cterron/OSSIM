use ossim;
SET AUTOCOMMIT=0;
BEGIN;

ALTER TABLE `vuln_job_schedule` CHANGE `schedule_type` `schedule_type` ENUM( 'O','D', 'W', 'M', 'NW' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'M';
DELETE FROM `user_config` WHERE category='policy' AND name='sensors_layout';
INSERT INTO config (conf , value) VALUES ('tickets_max_days', '15');

INSERT INTO `custom_report_types` VALUES(500, 'Historical View', 'Network', 'Network/HistoricalView.php', 'Interface:INTERFACE:multiselect:OSS_ALPHA.OSS_COLON.OSS_SPACE.OSS_SCORE.OSS_DOT', '', 1);
INSERT INTO `custom_report_types` VALUES(501, 'Global TCP/UDP Protocol Distribution', 'Network', 'Network/GlobalTCPUDPProtocolDistribution.php', 'Interface:INTERFACE:multiselect:OSS_ALPHA.OSS_COLON.OSS_SPACE.OSS_SCORE.OSS_DOT', '', 1);
INSERT INTO `custom_report_types` VALUES(502, 'Throughput', 'Network', 'Network/Throughput.php', 'Interface:INTERFACE:multiselect:OSS_ALPHA.OSS_COLON.OSS_SPACE.OSS_SCORE.OSS_DOT', '', 1);
INSERT INTO `custom_report_types` VALUES(145, 'Top Events', 'Logger', 'Logger/List.php', 'Top Logger Events List:top:text:OSS_DIGIT:25:250;Product Type:sourcetype:select:OSS_ALPHA.OSS_SLASH.OSS_SPACE.OSS_NULLABLE:SOURCETYPE:;Event Category:category:select:OSS_DIGIT.OSS_NULLABLE:CATEGORY:;Event SubCategory:subcategory:select:OSS_DIGIT.OSS_NULLABLE:SUBCATEGORY:', '', 1);

CREATE TABLE IF NOT EXISTS `risk_maps` (
  `map` varchar(64) NOT NULL,
  `perm` varchar(64) NOT NULL,
  PRIMARY KEY (`map`,`perm`)
);


-- From now on, always add the date of the new releases to the .sql files
-- UPDATE config SET value="2010-07-23" WHERE conf="last_update";

-- WARNING! Keep this at the end of this file
-- WARNING! Keep this at the end of this file
-- WARNING! Keep this at the end of this file
-- WARNING! Keep this at the end of this file
-- WARNING! Keep this at the end of this file
-- ATENCION! Keep this at the end of this file
-- ATENCION! Keep this at the end of this file
-- ATENCION! Keep this at the end of this file
-- ATENCION! Keep this at the end of this file
-- ATENCION! Keep this at the end of this file
REPLACE INTO config (conf, value) VALUES ('ossim_schema_version', '2.4');
COMMIT;
-- NOTHING BELOW THIS LINE / NADA DEBAJO DE ESTA LINEA
