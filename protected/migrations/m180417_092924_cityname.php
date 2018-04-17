<?php

class m180417_092924_cityname extends CDbMigration
{
	public function up()
	{
		$this->execute("
		ALTER TABLE `acpoly` ADD UNIQUE( `wardno`, `st_code`, `dt_code`);
		
		ALTER TABLE `municipalresults` 
			CHANGE `slug` `slug` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
			CHANGE `city` `city` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
	}

	public function down()
	{
		return true;
	}
}