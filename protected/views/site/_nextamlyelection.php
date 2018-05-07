<?php
/* @var $this SiteController */
/* @var $data AssemblyResult */

if(!isset($constituency))
        return;

$myneta_map = [];     
if(18 == $constituency->id_state )
{
	$csv = "karnataka-eci-myneta.csv";
	$F = fopen(YiiBase::getPathOfAlias("application.data") . '/' . $csv,"r");
	if($F)
	{
		while(!feof($F))
		{
			list($eci,$name,$neta) = fgetcsv($F);
			$myneta_map[$eci] = $neta;
		}
		fclose($F);
	}
}
if(!isset($myneta_map[$constituency->eci_ref]))
	return;
?>

<div class="view amly">
    <h2 class="acname"><?= CHtml::link(__('{state} Assembly Elections {eyear} - {acname} Assembly Constituency - #{acno}',
            [
                '{acname}' => strtolower($constituency->name),
                '{acno}' => $constituency->eci_ref,
            	'{eyear}' => date('Y',strtotime($election->edate)),
            	'{state}' => $constituency->state->name,
            ]),
            [
                'state/assembly',
                'acno' => $constituency->eci_ref,
                'id_state' => $constituency->id_state
            ])?></h2>
            
            <ol>
            <li>
            <?=CHtml::link(__('See list and details of all contesting candidates on myneta.info'),
            		"http://www.myneta.info/karnataka2018/index.php?action=show_candidates&constituency_id=" . $myneta_map[$constituency->eci_ref])?>
            </li>
            </ol>
</div>