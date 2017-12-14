<?php
/**
 * Does updates to database from fixed web urls
 *
 * @author vikas
 *
 */
class UpdateCommand extends CConsoleCommand
{
    function makeslug($str)
    {
        $str = trim($str);
        $mats = [ ];
        $str = preg_replace( '/[^\|\"\@\'\[\]\\\+\s-;\*\:\.\/\w,\(\)&]+/', '', $str);
        
        if (preg_match_all ( '/(?<bad>[^\|\"\@\'\[\]\\\+\s-;\*\:\.\/\w,\(\)&]+)/', $str, $mats ))
        {
            print_r ( $mats );
            echo 'found invalid char [' . $mats ['bad'] . '] for in:' . $str;
            return false;
        }
        
        $str = str_replace("'", '',$str);

        $slug1 = strtolower (
                str_replace (
                        [
                                ';',
                                '*',
                                ':',
                                '|',
                                '"',                                
                                '@',
                                '[',
                                ']',
                                '(',
                                ')',
                                '\\',
                                '/',
                        ], '', trim ( $str ) ) );
        
        $slug1 = strtolower (
                str_replace (
                        [
                                '+',
                                ',',
                                '.',
                                ' ',
                                '&'
                        ], '-', trim ( $str ) ) );

        $slug1 = preg_replace ( '/-+/', '-', $slug1 );
        $slug1 = preg_replace ( '/-$/', '', $slug1 );
        $slug1 = preg_replace ( '/^-/', '', $slug1 );
        return $slug1;
    }

    /**
     * Slug maker for LokSabha2014, MunicipalResults, AssemblyResults, Constituency, State, Committee, Town 
     * @throws Exception
     */
    public function actionSlug()
    {
        
        $rs = LokSabha2014::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $r->slug = $this->makeslug($r->name);
            $r->name = ucwords(strtolower($r->name));
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            $r->update ( [
                    'slug','name',
            ] );
        }
        
        $rs = MunicipalResults::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $r->slug = $this->makeslug($r->name);
            $r->name = ucwords(strtolower($r->name));
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            $r->update ( [
                    'slug','name',
            ] );
        }
        
        $rs = AssemblyResults::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $r->slug = $this->makeslug($r->name);
            $r->name = ucwords(strtolower($r->name));
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            try {
            $r->update ( [
                    'slug','name',
            ] );
            }
            catch(CDbException $e)
            {
                if(!preg_match('/1366 Incorrect string value/',$e->getMessage()))
                    throw new Exception($e);
            }
        }
        
        $rs = Constituency::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $r->slug = $this->makeslug($r->name);
            $r->name = ucwords(strtolower($r->name));
            
            if(empty($r->name_clean))
                $r->name_clean = preg_replace( '/^([^\(]+)/','$1', $r->name);
            
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            $r->update ( [
                    'slug','name','name_clean'
            ] );
        }

        $rs = State::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $r->slug = $this->makeslug($r->name);
            $r->name = ucwords(strtolower($r->name));
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            $r->update ( [
                    'slug','name',
            ] );
        }
        #201709272330:Kovai:thevikas
        $rs = Committee::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $r->slug = $this->makeslug($r->name);
            $r->name = ucwords(strtolower($r->name));
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            $r->update ( [
                    'slug','name',
            ] );
        }
         
        
        $pc=0;
            // 201709291310:Kovai:thevikas
        /*while ( Place::model ()->count (  
                ['condition' => 'slug is null and sdt_code=0 and tv_code=0 and id_place2>?','order' => 'id_place2','params' => [$pc]] 
         ) > 0 )
        {
            echo "Starting $pc\n";
            $rs = Place::model ()->findAll ( 
                    [ 
                            'select' => 'id_place2,name',
                            'condition' => 'slug is null AND dt_code>0 and sdt_code=0 and tv_code=0 and id_place2>?',
                            //'limit' => 5000,
                            'params' => [$pc]
                    ] );
            if (count ( $rs ))
            {
                foreach ( $rs as $r )
                {
                    $pc = $r->id_place2;
                    $r->slug = $this->makeslug ( $r->name );
                    #echo sprintf ( "%5d\t%50s\t%50s\n", $r->id_place2, $r->name, $r->slug );
                    try {
                    $r->update ( [ 
                            'slug' 
                    ] );
                    } catch(CDbException $e)
                    {
                        if(preg_match('/Integrity constraint violation/',$e->getMessage()));
                        else 
                            throw new Exception($e);
                    }
                }
            }
        }*/
        
        $pc=0;
        // 201709291310:Kovai:thevikas
        while ( Town::model ()->count (
                ['condition' => 'id_place>?','order' => 'id_place','params' => [$pc]]
                ) > 0 )
        {
            echo "Starting $pc\n";
            $rs = Town::model ()->findAll (
                    [
                            'select' => 'id_place,name,tv_code',
                            'condition' => 'id_place>?',
                            'order' => 'id_place',
                            'limit' => 10000,
                            'params' => [$pc]
                    ] );
            if (count ( $rs ))
            {
                foreach ( $rs as $r )
                {
                    $pc = $r->id_place;
                    if($r->tv_code==0)
                        continue;
                    $pc = $r->id_place;
                    $r->slug = $this->makeslug ( $r->name );
                    $r->name = ucwords(strtolower($r->name));
                    if($r->slug === false)
                        continue;
                    
                    echo sprintf ( "%5d\t%50s\t%50s\n", $r->id_place, $r->name, $r->slug );
                    try {
                        $r->update ( [
                                'slug','name',
                        ] );
                    } catch(CDbException $e)
                    {
                        
                        if(preg_match('/1366 Incorrect string value/',$e->getMessage()) ||
                           preg_match('/Integrity constraint violation/',$e->getMessage()))
                        {
                            echo $e->getMessage()  . "\n\n";
                        }
                        else
                            throw new Exception($e);
                    }
                }
            }
        }
    }

    public function actionIndex()
    {
        $this->actionSlug ();
        return;
        $methods = get_class_methods ( $this );
        foreach ( $methods as $method )
        {
            $mats = [ ];
            if (preg_match ( '/^updateKono(?<name>\w+)/', $method, $mats ))
            {
                print "Updating " . $mats ['name'] . "\n";
                $this->$method ();
            }
        }
    }

    /**
     * Sikkim
     * URL: http://www.sikkimassembly.org.in/mla.html
     */
    public function actionKonoSikkim()
    {
        require_once __DIR__ . '/update/updateActionSikkim.php';
        updateActionSikkim();
    }

    /**
     * Delhi
     * URL: http://delhiassembly.nic.in/aspfile/listmembers_VIth_Assembly.htm
     */
    public function updateKonoDelhi()
    {
        require_once __DIR__ . '/update/updateActionDelhi.php';
        updateActionDelhi();
    }

    /**
     * Kerala
     * URL: http://www.niyamasabha.org/codes/members.htm
     */
    public function actionKonoKerala()
    {
        require_once __DIR__ . '/update/updateActionKerala.php';
        updateActionKerala();
    }

    /**
     * 201909242238:Kovai:thevikas
     * Himachal
     * URL: http://hpvidhansabha.nic.in/Member/AllMembers?page=2
     */
    public function actionKonoHimachal()
    {
        require_once __DIR__ . '/update/updateActionHimachal.php';
        updateActionHimachal();
    }

    /**
     * 2019092516:29:Kovai:thevikas
     * Chhattisgarh
     * URL: http://cgvidhansabha.gov.in/english_new/mla_current.htm
     */
    public function actionKonoChhattisgarh()
    {
        require_once __DIR__ . '/update/updateActionChhattisgarh.php';
        updateActionChhattisgarh();
    }

    public function actionKonoChhattisgarhCommittee()
    {
        require_once __DIR__ . '/update/updateActionChhattisgarh.php';
        updateActionChhattisgarhCommittee();
    }

    /**
     * Not directly from website. Needs cleaning.
     */
    public function actionKarnataka()
    {
        require_once __DIR__ . '/update/updateActionKarnataka.php';
        updateActionKarnataka();
    }

    /**
     * 20171001
     * Not directly from website. Needs cleaning.
     */
    public function actionKonoHaryana()
    {
        require_once __DIR__ . '/update/updateActionHaryana.php';
        updateActionHaryana();
    }

    /**
     * #201710021448:Kovai:thevikas
     */
    public function actionKonoGoa()
    {
        require_once __DIR__ . '/update/updateActionGoa.php';
        updateActionGoa();
    }

    /**
     * #201710021448:Kovai:thevikas
     * Not directly from website. Needs cleaning.
     */
    public function actionKonoOdisha()
    {
        require_once __DIR__ . '/update/updateActionOdisha.php';
        updateActionOdisha();
    }

    /**
     * #201710021448:Kovai:thevikas
     */
    public function actionKonoPuducherry()
    {
        require_once __DIR__ . '/update/updateActionPuducherry.php';
        updateActionPuducherry();
    }

    /**
     * #201710032139:Kovai:thevikas
     */
    public function actionKonoWestBengal()
    {
        require_once __DIR__ . '/update/updateActionWestBengal.php';
        updateActionWestBengal();
    }
    
    /**
     * #201710032139:Kovai:thevikas
     */
    public function actionKonoAndhraPradesh()
    {
        require_once __DIR__ . '/update/updateActionAndhraPradesh.php';
        updateActionAndhraPradesh();
    }
    
    /**
     * #201710040820:Kovai:thevikas
     */
    public function actionKonoRajasthan()
    {
        require_once __DIR__ . '/update/updateActionRajasthan.php';
        updateActionRajasthan();
    }
    
    /**
     * #201710040820:Kovai:thevikas
     */
    public function actionKonoArunachalPradesh()
    {
        require_once __DIR__ . '/update/updateActionArunachalPradesh.php';
        updateActionArunachalPradesh();
    }

    /**
     * #201710041904:Kovai:thevikas
     */
    public function actionKonoGujarat()
    {
        require_once __DIR__ . '/update/updateActionGujarat.php';
        updateActionGujarat();
    }

    /**
     * #201710051351:Kovai:thevikas
     */
    public function actionKonoPunjab()
    {
        require_once __DIR__ . '/update/updateActionPunjab.php';
        updateActionPunjab();
    }

    /**
     * #201710051351:Kovai:thevikas
     */
    public function actionKonoAssam()
    {
        require_once __DIR__ . '/update/updateActionAssam.php';
        updateActionAssam();
    }

    /**
     * To parse the collectors of Deputy Commissioner of District
     * #201711171356:Kovai:thevikas
     */
    public function actionKonoDCTamilNadu()
    {
        require_once __DIR__ . '/update/updateDCTamilNadu.php';
        updateDCTamilNadu();
    }
}

function reducer($carry, $item)
{
    if (empty ( trim ( $item ) ))
        return $carry;

    $carry [] = str_replace ( ' ', '', trim ( $item ) );
    return $carry;
}

function cleanspace($txt,$keep = true)
{
    return preg_replace('/\s+/',$keep ? ' ' : '', trim($txt));
}
