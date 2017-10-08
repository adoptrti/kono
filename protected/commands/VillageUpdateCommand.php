<?php
/**
 * Does updates to database from fixed web urls
 *
 * @author vikas
 */
class VillageUpdateCommand extends CConsoleCommand
{
    /**
     * #201710051351:Kovai:thevikas
     */
    public function actionTamilnadu()
    {
        require_once __DIR__ . '/states/TamilnaduVillages.php';
        TamilnaduVillages();
    }
    
    /**
     * #201710051351:Kovai:thevikas
     */
    public function actionKerala()
    {
        require_once __DIR__ . '/states/KeralaVillages.php';
        KeralaVillages();
    }
    
}