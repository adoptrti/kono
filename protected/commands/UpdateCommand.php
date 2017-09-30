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
        $mats = [ ];
        if (preg_match ( '/(?<bad>[^\|\"\@\'\[\]\\\+\s-;\*\:\.\/\w,\(\)&]+)/', $str, $mats ))
        {
            print_r ( $mats );
            echo 'found invalid char for in:' . $str ;
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

    public function actionSlug()
    {/*
        $rs = Constituency::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $r->slug = $this->makeslug($r->name);
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            $r->update ( [
                    'slug'
            ] );
        }

        $rs = State::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $r->slug = $this->makeslug($r->name);
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            $r->update ( [
                    'slug'
            ] );
        }
        #201709272330:Kovai:thevikas
        $rs = Committee::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $r->slug = $this->makeslug($r->name);
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            $r->update ( [
                    'slug'
            ] );
        }
          */  
        
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
                ['condition' => 'slug is null and id_place>?','order' => 'id_place','params' => [$pc]]
                ) > 0 )
        {
            echo "Starting $pc\n";
            $rs = Town::model ()->findAll (
                    [
                            'select' => 'id_place,name,tv_code',
                            'condition' => 'slug is null and id_place>?',
                            'order' => 'id_place',
                            //'limit' => 5000,
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
                    if($r->slug === false)
                        continue;
                    
                    echo sprintf ( "%5d\t%50s\t%50s\n", $r->id_place, $r->name, $r->slug );
                    try {
                        $r->update ( [
                                'slug'
                        ] );
                    } catch(CDbException $e)
                    {
                        if(preg_match('/Integrity constraint violation/',$e->getMessage()))
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
