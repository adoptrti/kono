<?php

class m170913_135018_acploy_add_field_defaults extends CDbMigration
{
	public function up()
	{
	    $this->execute("ALTER TABLE `acpoly` 
                            CHANGE `DT_CODE` `DT_CODE` INT(11) NULL DEFAULT NULL,
                            CHANGE `PC_ID` `PC_ID` INT(11) NULL DEFAULT NULL,
                            CHANGE `DIST_NAME` `DIST_NAME` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
	}

	public function down()
	{
	}
}