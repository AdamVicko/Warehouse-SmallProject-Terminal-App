<?php

class Pomocno
{

    public static function rasponBroja($poruka,$min,$max){
        while(true){
            $i = readline($poruka);
            $i = (int)$i;
            if($i<$min || $i>$max){
                echo 'Unos mora biti izmedu ' . $min . ' i ' . $max . PHP_EOL;
                continue;
            }
            return $i;
        }
    }

    public static function unosTeksta($poruka,$vrijednost='')
    {
        while(true)
        {
            $s = readline($poruka);
            $s = trim($s);
            if(strlen($s) === 0 && $vrijednost==='')
            {
                echo 'Mandatory entry' . PHP_EOL;
                continue;
            }
            if(strlen($s)===0 && $vrijednost!=='') // ako je nas unos prazan a vec postoji vrijednost za to
            {
                return $vrijednost; // vrati postojeci naziv ako nista nije uneseno
            }
            return $s;
        }
    }

    public static function unosBroja($poruka)
    {
        while(true)
        {
            $s = readline($poruka);
            $s = trim($s);
            return $s;
        }
    }
}// end of class  