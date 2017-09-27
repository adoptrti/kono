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
        if (preg_match ( '/(?<bad>[^\'\s-\.\w,\(\)&]+)/', $str, $mats ))
        {
            print_r ( $mats );
            die ( 'found invalid char for in:' . $str );
        }

        $str = str_replace("'", '',$str);

        $slug1 = strtolower (
                str_replace (
                        [
                                ',',
                                '.',
                                ' ',
                                '(',
                                ')',
                                '&'
                        ], '-', trim ( $str ) ) );

        $slug1 = preg_replace ( '/-+/', '-', $slug1 );
        $slug1 = preg_replace ( '/-$/', '', $slug1 );
        $slug1 = preg_replace ( '/^-/', '', $slug1 );
        return $slug1;
    }

    public function actionSlug()
    {
        $rs = Constituency::model ()->findAll ();
        foreach ( $rs as $r )
        {
            $r->slug = $this->makeslug($r->name);
            echo sprintf ( "%50s\t%50s\n", $r->name, $r->slug );
            $r->update ( [
                    'slug'
            ] );
        }

        $rs = States::model ()->findAll ();
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
