<?php
class m180418_174135_mcpic extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE `eci3`.`municipalresults` DROP INDEX `city`, ADD UNIQUE `city` (`id_city`, `wardno`) USING BTREE;

						ALTER TABLE `municipalresults` 
							ADD `id_mr` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id_mr`),
							CHANGE `phone` `phone` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
							CHANGE `address` `address` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
 							CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
							ADD `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `picture`, 
							ADD `updated` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created`,
							ADD `picture` VARCHAR(255) NULL AFTER `slug`;

						ALTER TABLE `municipalresults` DROP `city`;

						ALTER TABLE `constituency` 
							CHANGE `ctype` `ctype` ENUM('AMLY','PARL','MWARD','MZONE') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
						
						ALTER TABLE `officer` 
							CHANGE `desig` `desig` ENUM('DISTCOLLECTOR','CHIEFMINISTER','GOVERNER','DEPUTYCHIEFMINISTER','JOINTCOLLECTOR','SPOLICE','IGPOLICE','CHIEFENGINEER','HEALTHOFFICER') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
	}

	public function down()
	{
		return true;
	}
}