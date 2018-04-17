<?php

class m170913_135018_acploy_add_field_defaults extends CDbMigration
{
	public function up()
	{
	    $this->execute("
		ALTER TABLE `officer` 
			CHANGE `desig` `desig` ENUM('DISTCOLLECTOR','CHIEFMINISTER','GOVERNER','DEPUTYCHIEFMINISTER','JOINTCOLLECTOR','SPOLICE','IGPOLICE') 
			CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
		
		ALTER TABLE `acpoly` 
                            CHANGE `DT_CODE` `dt_code` INT(11) NULL DEFAULT NULL,
                            CHANGE `PC_ID` `pc_id` INT(11) NULL DEFAULT NULL,
                            CHANGE `DIST_NAME` `dist_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
	}

	public function down()
	{
	}
}