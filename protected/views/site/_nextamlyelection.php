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

            <?php

            $model=new ElectionCandidates('search');
            $model->unsetAttributes();  // clear any default values
            $model->id_election = $election->id_election;
            $model->eci_ref = $constituency->eci_ref;		
            $consti = Constituency::model()->findByAttributes([
                    'ctype' => 'AMLY',
                    'eci_ref' => $constituency->eci_ref,
                    'id_state' => $election->id_state,
            ]);

            $state = $election->state;
            $election= $election;

            $this->pageTitle = __('{consti} #{eciref} - Contesting Candidates - {state} Assembly Elections {eyear}',[
                    '{state}' => $state->name,
                    '{eyear}' => $election->year,
                    '{consti}' => $constituency->name,
                    '{eciref}' => $constituency->eci_ref,]);


            $this->renderPartial('//election/candidates',array(
                'model'=>$model,
                'constituency' => $constituency,
                'state' => $state,
                'election' => $election,
                'small' => true,
            ));
            ?>
</div>