<?php

function KeralaVillages()
{
    $id_state = 19; // kerala
    $ST_CODE = 32;
    
    $vills = Village::model ()->findAll ( 
            [ 
                    'select' => 'distinct dist_name',
                    'condition' => 'id_district is null' 
            ] );
    
    foreach ( $vills as $dist )
    {
        $dist_name = $dist->dist_name;
        
        if (preg_match ( '/ContentPlaceHolder/', $dist_name ))
        {
            Village::model ()->deleteAllByAttributes ( [ 
                    'dist_name' => $dist_name 
            ] );
            continue;
        }
        
        if (empty ( trim ( $dist_name ) ) || strlen ( $dist_name ) == 1)
            continue;
        
        echo "$dist_name...\n";
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
            die ( 'Could not find district [' . $dist_name . "]\n" );
        }
        Village::model ()->updateAll ( [ 
                'id_district' => $place->id_place2 
        ], 'dist_name = ?', [ 
                $dist->dist_name 
        ] );
    }
} 


