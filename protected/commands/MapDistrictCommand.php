<?php
/**
 * Does updates to database from fixed web urls
 *
 * @author vikas
 */
class MapDistrictCommand extends CConsoleCommand
{
    var $state;

    public function actionpolygons()
    {
        while ( $this->matchItem () )
            ;
    }

    public function matchItem()
    {
        $id_state = 34;
        
        $cmd = Yii::app ()->db->createCommand ( 
                "SELECT distinct dist_name FROM acpoly p left join lb_district lb on p.dist_name=lb.name and lb.id_state=p.id_state 
where p.id_state=:sid and p.id_district is null and dist_name<>''" );
        $rs = $cmd->queryScalar ( [ 
                'sid' => 34 
        ] );
        if ($rs === false)
            return false;
        $dist_name = $rs [0];
        
        $dists = District::model ()->bystate ( $id_state )->findAll ( [ 
                'order' => 'name' 
        ] );
        $dists2 = CHtml::listData ( $dists, 'id_district', 
                function ($data)
                {
                    if (count ( $data->polygons ) == 0)
                        return $data->name;
                    return false;
                } );
        $disty_names = array_filter ( $dists2, function ($item)
        {
            return ! empty ( $item );
        } );
        print_r ( $disty_names );
        
        echo "Match $dist_name?";
        $id = intval ( fgets ( STDIN ) );
        echo "Sure $dist_name is " . $dists2 [$id] . '?';
        $yn = trim ( fgets ( STDIN ) );
        if ($yn == 'y')
            AssemblyPolygon::model ()->updateAll ( 
                    [ 
                            'id_district' => $id,
                            'dist_name' => $dists2 [$id] 
                    ], 'id_state=? and dist_name=?', 
                    [ 
                            $id_state,
                            $dist_name 
                    ] );
        return true;
    }

    public function actiontowns($id_state)
    {
        $this->state = State::model()->findbyPk($id_state);
        while ( $this->matchItemTown( $id_state ) );
    }

    public function matchItemTown($id_state)
    {
        $cmd = Yii::app ()->db->createCommand ( 
                "SELECT distinct dt_name FROM `towns2011`  where id_district is null and st_name<>dt_name and id_state=?" );
        $rs = $cmd->queryScalar ( [ 
                $id_state 
        ] );
        if ($rs === false)
            return false;
        $dist_name = $rs;
        
        $dists = District::model ()->bystate ( $id_state )->findAll ( [ 
                'order' => 'name' 
        ] );
        $dists2 = CHtml::listData ( $dists, 'id_district', 
                function ($data)
                {
                        return $data->name;
                } );
        $disty_names = array_filter ( $dists2, 
                function ($item)
                {
                    return ! empty ( $item );
                } );
        
        echo "Districts in {$this->state->name}\n";
        print_r ( $disty_names );
        
        echo "Match $dist_name?";
        $id = intval ( fgets ( STDIN ) );
        echo "Sure $dist_name is " . $dists2 [$id] . '?';
        $yn = trim ( fgets ( STDIN ) );
        if ($yn == 'y')
        {
            Town::model ()->updateAll ( 
                    [ 
                            'id_state' => $id_state,
                            'id_district' => $id,
                            'dist_name' => $dists2 [$id] 
                    ], 'id_state=? and dt_name=?', 
                    [ 
                            $id_state,
                            $dist_name 
                    ] );
            $dist = District::model()->findByPk($id);
            $dist->othername = $dist_name;
            $dist->save();
        }
        return true;
    }
}
