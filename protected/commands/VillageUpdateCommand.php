<?php
/**
 * Does updates to database from fixed web urls
 *
 * @author vikas
 */
class VillageUpdateCommand extends CConsoleCommand
{
    var $state;

    public function actionRefactor()
    {
        
        // *******************
        // identify districts
        // create in lb_distrcts
        // update in villages2011
        $places = Village::model ()->findAll ( 
                [ 
                        'select' => 'distinct id_district',
                        'condition' => 'id_lb_district is null' 
                ] );
        foreach ( $places as $district )
        {
            $this->refactorDistrict ( $district->id_district );
        }
        
        // *******************
        // find blocks
        // create in lb_block
        // update in villages2011
        $places = Village::model ()->findAll ( 
                [ 
                        'select' => 'distinct id_lb_district' 
                ] );
        foreach ( $places as $district )
        {
            $this->refactorBlock ( $district->id_lb_district );
        }
        
        // *******************
        // find panchayats
        // create in lb_panchayat
        // update village2011
        $places = Village::model ()->findAll ( 
                [ 
                        'select' => 'distinct id_block' 
                ] );
        foreach ( $places as $block )
        {
            $this->refactorPanchayat ( $block->id_block );
        }
        
        // *******************
        // find villages
        // create in lb_panchayat
        // update village2011
        
        $places1 = Village::model ()->findAll ( 
                [ 
                        'select' => 'distinct id_block' 
                ] );
        foreach ( $places as $block )
        {
            
            $places2 = Village::model ()->findAll ( 
                    [ 
                            'select' => 'distinct id_panchayat',
                            'condition' => 'id_block = ? and id_lb_village is null',
                            'params' => [$block->id_block]
                    ] );
            foreach ( $places2 as $py )
            {
                $this->refactorVillage ( $py->id_panchayat );
                // find wards
                // create in fb_wards
                // update in villages2011
            }
        }
    }

    public function refactorPanchayat($id_lb_block)
    {
        $panchayats = Village::model ()->findAll ( 
                [ 
                        'select' => 'distinct panchayat',
                        'condition' => 'id_block = ? and id_panchayat is null',
                        'params' => [ 
                                $id_lb_block 
                        ] 
                ] );
        
        foreach ( $panchayats as $panchayat )
        {
            $obj = new Panchayat ();
            $obj->name = $panchayat->panchayat;
            $obj->id_block = $id_lb_block;
            if (! $obj->save ())
            {
                print_r ( $obj->errors );
                die ();
            }
            $upctr = Village::model ()->updateAll ( 
                    [ 
                            'id_panchayat' => $obj->id_panchayat 
                    ], 'id_block = :dis and panchayat=:vn', 
                    [ 
                            ':dis' => $id_lb_block,
                            ':vn' => $panchayat->panchayat 
                    ] );
            
            if (! $upctr)
                die ( "id_lb_block = $id_lb_block, pname=[{$block->panchayat}] nothing found!?" );
            
            echo "{$obj->block->district->state->name} \t {$obj->block->district->name} \t {$obj->block->name} \t {$obj->name} Saved. village records updated:$upctr\n";
        }
    }

    public function refactorVillage($id_lb_panchayat)
    {
        echo "Finding pending villages...\t$id_lb_panchayat ";
        $villages = Village::model ()->findAll ( 
                [ 
                        'select' => 'distinct village',
                        'condition' => 'id_panchayat = ? and id_lb_village is null',
                        'params' => [ 
                                $id_lb_panchayat 
                        ] 
                ] );
        echo "Done.\n";
        
        foreach ( $villages as $village )
        {
            $obj = new LBVillage ();
            $obj->name = $village->village;
            $obj->id_panchayat = $id_lb_panchayat;
            if (! $obj->save ())
            {
                print_r ( $obj->errors );
                die ();
            }
            $upctr = Village::model ()->updateAll ( 
                    [ 
                            'id_lb_village' => $obj->id_village 
                    ], 'id_panchayat = :dis and village=:vn', 
                    [ 
                            ':dis' => $id_lb_panchayat,
                            ':vn' => $village->village 
                    ] );
            
            if (! $upctr)
                die ( "id_lb_block = $id_lb_block, vname=[{$village->name}] nothing found!?" );
            
            echo "{$obj->panchayat->block->district->state->name} \t {$obj->panchayat->block->name} \t {$obj->panchayat->name} \t {$obj->name}Saved. village records updated:$upctr\n";
        }
    }

    public function refactorBlock($id_lb_district)
    {
        $blocks = Village::model ()->findAll ( 
                [ 
                        'select' => 'distinct block',
                        'condition' => 'id_lb_district = ? and id_block is null',
                        'params' => [ 
                                $id_lb_district 
                        ] 
                ] );
        
        foreach ( $blocks as $block )
        {
            $obj = new Block ();
            $obj->name = $block->block;
            $obj->id_district = $id_lb_district;
            if (! $obj->save ())
            {
                print_r ( $obj->errors );
                die ();
            }
            $upctr = Village::model ()->updateAll ( [ 
                    'id_block' => $obj->id_block 
            ], 'id_lb_district = :dis and block=:bn', 
                    [ 
                            ':dis' => $id_lb_district,
                            ':bn' => $block->block 
                    ] );
            
            if (! $upctr)
                die ( "district = $id_lb_district, name=[{$block->block}] nothing found!?" );
            
            echo "{$obj->district->state->name} \t {$obj->district->name} \t {$obj->name} Saved. block records updated:$upctr\n";
        }
    }

    public function refactorDistrict($id_place2)
    {
        $place = Place::model ()->findByPk ( $id_place2 );
        if (! $place)
        {
            echo "$id_place2 Not found in place_names\n";
        }
        $dis = new District ();
        $dis->name = $place->name;
        $dis->id_state = $place->id_state;
        if (! $dis->save ())
        {
            print_r ( $dis->errors );
            die ();
        }
        $upctr = Village::model ()->updateAll ( [ 
                'id_lb_district' => $dis->id_district 
        ], 'id_district = :dis', [ 
                ':dis' => $id_place2 
        ] );
        echo "{$dis->state->name} \t {$dis->name} Saved. village records updated:$upctr\n";
    }

    public function actionIndex($id_state)
    {
        $this->state = State::model ()->findByPk ( $id_state );
        echo "Working for " . $this->state->name . "\n";
        // 1:download the url
        $data = $this->step1 ( $id_state );
        // 2:cleanup the html file
        $htmlfile = $this->step2 ( $id_state, $data );
        // 3:call R script
        $this->step3 ( $id_state, realpath ( $htmlfile ) );
        // 4:domysql import
        $this->step4 ( $id_state );
        // 5:process in step3
        $this->step5 ( $id_state );
    }

    public function step1($id_state)
    {
        $dirtyfile = getcwd () . '/villages.html';
        
        if (file_exists ( $dirtyfile ))
        {
            echo $dirtyfile . ' already found, continue using it? (y/n)';
            $yn = strtolower ( trim ( fgets ( STDIN ) ) );
            if ('y' != $yn)
                unlink ( $dirtyfile );
        }
        
        if (! file_exists ( $dirtyfile ))
        {
            echo "Please provide the url:";
            $url = trim ( fgets ( STDIN ) );
            print "Downloading...\n";
            // system("wget -O $dirtyfile $url");
            system ( "curl -o \"$dirtyfile\" \"$url\"" );
            // $data = file_get_contents ( $url );
            // print "Completed.\n";
        }
        return file_get_contents ( $dirtyfile );
    }

    public function step2($id_state, $data)
    {
        libxml_use_internal_errors ( true );
        $doc = new DOMDocument ();
        $doc->loadHTML ( $data );
        $TABLE = $doc->getElementById ( 'ctl00_ContentPlaceHolder_GVHabitation' );
        $data2 = $doc->saveHTML ( $TABLE );
        
        $htmlfile = getcwd () . '/villages.html';
        file_put_contents ( $htmlfile, $data2 );
        return $htmlfile;
    }

    public function step3($id_state, $htmlfile)
    {
        $script = fopen ( getcwd () . "/rfile.r", 'w' );
        $csvfile = getcwd () . '/villages2011.csv';
        
        if (! $htmlfile)
            die ( __DIR__ . '/villages.html not found!' );
        fputs ( $script, "library(XML)\n" );
        fputs ( $script, "var{$id_state} = readHTMLTable(\"$htmlfile\")\n" );
        fputs ( $script, "write.csv(var{$id_state},file=\"$csvfile\")\n" );
        fclose ( $script );
        
        system ( "Rscript \"" . getcwd () . "/rfile.r\"" );
    }

    public function step4()
    {
        $dbuser = Yii::app ()->params ['dbuser'];
        system ( 
                "mysqlimport --fields-terminated-by=, --fields-enclosed-by='\"' --fields-escaped-by='\"'  --columns='del1,del2,state_name,dist_name,block,panchayat,village,ward' --local -u $dbuser eci3 villages2011.csv" );
    }

    /**
     * #201710081840:Kovai:thevikas
     */
    public function step5($id_state)
    {
        $state = $this->state;
        
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
        
        $totalupdated = 0;
        
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
                        $place = new Place ();
                        $place->id_state = $id_state;
                        $place->name = $place->dt_name = $dist_name;
                        $place->state_code = $place->sdt_code = $place->tv_code = 0;
                        if (! $place->save ())
                        {
                            print_r ( $place->errors );
                            die ();
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
            $totalupdated += $rups;
        }
        echo "Total updated: $totalupdated\n";
    }
}