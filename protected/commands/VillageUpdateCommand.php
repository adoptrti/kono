<?php
/**
 * Does updates to database from fixed web urls
 *
 * @author vikas
 */
class VillageUpdateCommand extends CConsoleCommand
{

    /**
     * #201710081840:Kovai:thevikas
     */
    public function actionIndex($id_state)
    {
        $state = State::model ()->findByPk ( $id_state );
        
        $vills = Village::model ()->findAll ( 
                [ 
                        'select' => 'distinct dist_name',
                        'condition' => 'id_district is null and state_name=?',
                        'params' => [ 
                                $state->name 
                        ] 
                ] );
        
        echo "Total " . count ( $vills ) . " found.\n";
        if (count ( $vills ) == 0)
            return;
        
        foreach ( $vills as $dist )
        {
            $dist_name = $dist->dist_name;
            
            if (preg_match ( '/ContentPlaceHolder/', $dist_name ))
            {
                Village::model ()->deleteAllByAttributes ( 
                        [ 
                                'dist_name' => $dist_name 
                        ] );
                continue;
            }
            
            if (empty ( trim ( $dist_name ) ) || strlen ( $dist_name ) == 1)
                continue;
            
            echo "$dist_name...";
            /*
             * else if ('TIRUVARUR' == $dist_name)
             * $dist_name = 'Thiruvarur';
             * else if ('VILLUPURAM' == $dist_name)
             * $dist_name = 'Viluppuram';
             */
            
            $place = Place::model ()->findByAttributes ( 
                    [ 
                            'id_state' => $id_state,
                            'dt_name' => $dist_name,
                            'sdt_code' => 0,
                            'tv_code' => 0 
                    ] );
            if (! $place)
            {
                while ( true )
                {
                    echo 'Could not find district [' . $dist_name . "]\n";
                    echo 'Please provide the place id (0 for new):';
                    $id_place2 = intval ( fgets ( STDIN ) );
                    if ($id_place2)
                        $place = Place::model ()->findByPk ( $id_place2 );
                    else
                    {
                        $place = new Place();
                        $place->id_state = $id_state;
                        $place->name = $place->dt_name = $dist_name;
                        $place->state_code = $place->sdt_code = $place->tv_code = 0;
                        if(!$place->save())
                        {
                            print_r($place->errors);
                            die;
                        }
                    }
                    if (! $place)
                    {
                        echo "$id_place2 Not found.\n";
                        $id_place2 = 0;
                    }
                    else
                    {
                        echo "{$place->id_place2} found as [{$place->dt_name}] vs [$dist_name] Confirm (y/n)?";
                        $yn = strtolower ( trim ( fgets ( STDIN ) ) );
                        if ('y' == $yn)
                            break;
                    }
                }
            }
            $rups = Village::model ()->updateAll ( 
                    [ 
                            'id_district' => $place->id_place2,
                            'id_state' => $id_state,
                            'state_name' => null,
                            'dist_name' => null,
                            'del1' => null,
                            'del2' => null 
                    ], 'dist_name = ?', [ 
                            $dist->dist_name 
                    ] );
            echo "$rups updated\n";
        }
    }
}