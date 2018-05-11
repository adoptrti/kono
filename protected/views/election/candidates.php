<?php
/* @var $this ElectionController */
/* @var $model ElectionCandidates */
if(!isset($small) || !$small)
{
$this->breadcrumbs=array(
	$state->name => array('state/view','id' => $state->id_state),
	$constituency->name => array('state/assembly','id_state' => $state->id_state,'acno' => $constituency->eci_ref),		
	__('Contesting Candidates')
);

$this->menu=array(
	array('label'=>'List Election', 'url'=>array('index')),
	array('label'=>'Create Election', 'url'=>array('create')),
);

?>

<h1><?=__('{state} Assembly Elections {eyear}',[
		'{state}' => $state->name,
		'{eyear}' => $election->year,
		'{consti}' => $constituency->name,
		'{eciref}' => $constituency->eci_ref,		
])?></h1>
<h2><?=__('{consti} #{eciref}',[
		'{state}' => $state->name,
		'{eyear}' => $election->year,
		'{consti}' => $constituency->name,
		'{eciref}' => $constituency->eci_ref,		
])?></h2>
<h3><?=__('Contesting Candidates',[
		'{state}' => $state->name,
		'{eyear}' => $election->year,
		'{consti}' => $constituency->name,
		'{eciref}' => $constituency->eci_ref,		
])?></h3>

<?php
}

 $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'election-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		[
			'header' => __("EVM Button Number"),
			'name' => 'button',		
		],
			[
				'header' => __('Candidate'),
				'type' => 'raw',
				'value' => function($data) use($state,$election)
				{
					$img = CHtml::image("/images/pics/" . $state->slug . '/elections-' . $election->id_election 
							. '/' . $data->eci_ref . '-' . $data->button . '.png',$data->name,
							[
								'width' => '100','title' => $data->name
							]
							) . '<br/>' . $data->name;
					if($data->adr_id>0)
						$img = CHtml::link($img,"http://www.myneta.info/karnataka2018/candidate.php?candidate_id={$data->adr_id}");
					return $img;

				}
			],
			[
					'header' => __('Election Symbol'),
					'type' => 'raw',
					'value' => function($data) use($state,$election)
					{
						return CHtml::image("/images/pics/" . $state->slug . '/elections-' . $election->id_election . '/' . $data->eci_ref . '-' . $data->button . '-party.png',$data->party,['width' => '100','title' => $data->party]) . '<br/>' . $data->party;
					}
			],
			[
				'header' => __('Criminal Cases'),
				'type' => 'raw',
				'name' => 'cases',
			],
			[
				'header' => __('Assets'),
				'type' => 'raw',
				'name' => 'assets',
			],
			[
				'header' => __('Liabilities'),
				'type' => 'raw',
				'name' => 'liabilities',
			],
		
			),
)); ?>
