<?php
const IGFILE = 'ignores.txt';
$ignores = file_get_contents ( IGFILE );
if (empty ( $ignores ))
    $ignores = [ ];
else
    $ignores = unserialize ( $ignores );

print "Ignoring...\n";
print_r ( $ignores );
sleep ( 2 );
$f = fopen ( 'Udir_2011_00_ALL.csv', 'r' );
$f2 = fopen ( 'out.csv', 'w' );
$lc = 0;
while ( ! feof ( $f ) )
{
    $fields = fgetcsv ( $f );
    
    if (! $lc ++ || !$fields)
        continue;
    // print_r($fields);
    $mats = [ ];
    if (preg_match_all ( '/\((?<bracket>[^\)]+)\)/', $fields [7], $mats ))
    {
        print_r ( $fields );
        print_r ( $mats );
        foreach ( $mats ['bracket'] as $i => $str )
        {
            $str2 = str_replace('.', '',$str);
            $str2 = strtolower ( trim (str_replace(' ', '',$str2)));
            switch ($str2)
            {
                case 'm+og' :
                case 'mb+og' :
                case 'na+og' :
                case 'mcorp+og' :
                case 'mcl+og' :
                case 'nac+og' :
                case 'its+og' :
                case 'mci+og' :
                case 'mc+og' :
                case 'tmc+og' :
                case 'cmc+og' :
                case 'np+og' :
                case 'tp+og' :
                case 'gp+og' :
                case 'npp+og' :
                case 'mcl' :
                case 'mcorp' :
                case 'cb' :
                case 'mcl' :
                case 'nac' :
                case 'mc' :
                case 'np' :
                case 'npp' :
                case 'its' :
                case 'mci' :
                case 'ina' :
                case 'cmc' :
                case 'tmc' :
                case 'nt' :
                case 'tc' :
                case 'tp' :
                case 'gp' :
                case 'na' :
                case 'mb' :
                case 'st' :
                case 'm' :
                    $fields [] = $str2;
                    $f7 = trim(str_replace('(' . $str . ')','',$fields [7]));
                    $fields [7] = $f7;
                    break;
                default :
                    {
                        if (! isset ( $ignores [$str] ) && ! isset ( $ignores [$str2] ))
                        {
                            print "$lc: Ignore? (y/n) [$str]:";
                            $ch = trim ( fgets ( STDIN ) );
                            if (strtolower ( $ch ) == 'y')
                            {
                                $ignores [$str] = 1;
                                file_put_contents ( IGFILE, serialize ( $ignores ) );
                            }
                            else
                                die ( $lc . ": ($ch) Not known:" . $str . ', ctr=' . count ( $fields ) );
                        }                        
                    }
            }
            if (count ( $fields ) == 9)
                break;
        }
    }
    fputcsv ( $f2, $fields );
}
fclose ( $f );
fclose ( $f2 );
?>
