<?php

function updateDCTamilNadu()
{
    $id_state = 32; // Tamil Nadu
    
    $stateobj = State::model ()->findByPk ( $id_state );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://www.tn.gov.in/collectors' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $dcfile = Yii::app ()->basePath . '/../docs/' . $stateobj->slug . '/dc.html';
        if (! file_exists ( $dcfile ))
        {
            $html = file_get_contents ( 'http://www.tn.gov.in/collectors' );
            if ($html)
                file_put_contents ( $dcfile, $html );
            else
                die ( "Could not fetch URL" );
        }
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( $dcfile ) );
        
        // since its the only table
        $xpath = new DOMXpath ( $doc );
        $DIVs = $xpath->query ( "//div[@class='dist_connent']" );
        
        if ($DIVs->length == 0)
            die ( 'District parsing failed. DIVs not found' );
        
        $rctr = 0;
        foreach ( $DIVs as $div )
        {
            // ignore the first one
            if ($rctr ++ == 0)
                continue;
            
            $tds = $div->getElementsByTagName ( 'div' );
            $col = 0;
            $phones = null;
            $rs = [ ];
            // $picture_path = null;
            foreach ( $tds as $td )
            {
                if (in_array ( $td->getAttribute ( 'class' ), 
                        [ 
                                'dist_col_map',
                                'dist_right_contact' 
                        ] ))
                    continue;
                $nv = trim ( $td->nodeValue );
                $nv2 = preg_replace ( '/\s+/', ' ', $nv );
                switch ($col++)
                {
                    case 2 : // dist name
                        $ss = explode ( '-', $nv2 );
                        //print_r ( $ss );
                        $dist_name = trim ( $ss [0] );
                        $pincode = trim ( $ss [1] );
                        if ('Pudukottai' == $dist_name)
                            $dist_name = 'Pudukkottai';
                        
                        if ('Sivagangai' == $dist_name)
                            $dist_name = 'Sivaganga';
                        
                        if ('Thiruvannamalai' == $dist_name)
                            $dist_name = 'Tiruvannamalai';
                        
                        if ('Thirunelveli' == $dist_name)
                            $dist_name = 'Tirunelveli';
                        
                        if ('Trichirappalli' == $dist_name)
                            $dist_name = 'Tiruchirappalli';
                        
                        if ('Tuticorin' == $dist_name)
                            $dist_name = 'Thoothukkudi';
                        
                        if ('Villupuram' == $dist_name)
                            $dist_name = 'Viluppuram';
                        
                        if ('Kanyakumari' == $dist_name)
                            $dist_name = 'Kanniyakumari';
                        
                        $distdb = District::model ()->bystate ( $id_state )->findByAttributes ( 
                                [ 
                                        'name' => $dist_name 
                                ] );
                        if (! $distdb)
                            die ( "district $nv2 not found" );
                        $nv2 = $distdb->id_district;
                        break;
                    case 5 : // Phone
                    case 6 : // Fax
                    case 7 : // Email
                        $nv2 = trim ( 
                                str_replace ( 
                                        [ 
                                                'Phone :',
                                                'Fax :',
                                                'E-Mail :' 
                                        ], '', $nv2 ) );
                        break;
                }
                $rs [] = $nv2;
            } // foreach ( $tds as $td )
            
            print_r ( $rs );
            // echo "$col = " . $nv . "\n";
            //continue;
            
            $officer = new Officer ();
            $officer->fkey_place = $rs [2];
            $officer->desig = Officer::DESIG_DISTCOLLECTOR;
            $officer->name = $rs [0];
            $officer->phone = $rs [5];
            $officer->fax = $rs [6];
            $officer->email = $rs [7];
            
            if (! $officer->save ())
            {
                print_r ( $officer->errors );
                die ( 'Saving collector failed ' );
            }
            // echo $MLA->acname . " saved!\n";
        } // foreach TRs
    } // foreach URLs
}
