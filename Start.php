<?php

include_once 'Pomocno.php';

class Start{

    private $pacijenti;
    private $dev;
    private $prikupi;
    private $isporuke;
    private $koncentratoriKisika;
    private $ukupnoStanje;
    
    public function __construct($argc,$argv)
    {
        $this->pacijenti=[]; // sluzi za akumuliranje pacijenata array
        $this->prikupi=[]; // sluzi za akumuliranje prikupa array
        $this->isporuke=[]; // sluzi za akumuliranje isporuka array
        $this->koncentratoriKisika=[];// sluzi za akumuliranje kisika array
        $this->ukupnoStanje = []; // akumulira stanje konc kisika
        if($argc > 1 && $argv[1] == 'dev' )
        {
            $this->testPodaci();
            $this->dev=true;
        }else
        {
            $this->dev=false; //dev narazini cijele klase i svim metodama u klasi check jesam li dev ili nisam
        }
        //$this->testPodaci(); // ubaci test pacijente
        $this->pozdravnaPoruka();
        $this->glavniIzbornik();
    }

    private function pozdravnaPoruka() //metode drzim privatnima a do njih cu doci preko constructora
    {
        echo PHP_EOL . 'Welcome to the Warevic terminal APP!' . PHP_EOL;
    }

    private function glavniIzbornik()//metode drzim privatnima a do njih cu doci preko constructora
    {
        echo '-----------------------------------' . PHP_EOL;;
        echo 'Main menu' . PHP_EOL;
        echo '1.Patients' . PHP_EOL;
        echo '2.Oxygen Concentrators' . PHP_EOL;
        echo '3.Deliveries' . PHP_EOL;
        echo '4.Collections' . PHP_EOL;
        echo '5.Warehouse status' . PHP_EOL;
        echo '6.Exit from the program' . PHP_EOL;

        $this->odabirOpcijeGlavniIzbornik();
    }

    private function odabirOpcijeGlavniIzbornik()//metode drzim privatnima a do njih cu doci preko constructora
    {
        switch(Pomocno::rasponBroja('Choose an option: ',1,6))
        {
            case 1:
                echo '-----------------------------------' . PHP_EOL;
                $this->pacijentIzbornik();
                break;
            case 2:
                echo '-----------------------------------' . PHP_EOL;
                $this->kisikIzbornik();
                break;
            case 3:
                echo '-----------------------------------' . PHP_EOL;
                $this->isporukaIzbornik();
                break;
            case 4:
                echo '-----------------------------------' . PHP_EOL;
                $this->prikupIzbornik();
                break;
            case 5:
                echo '-----------------------------------' . PHP_EOL;
                $this->stanjeIzbornik();
                break;
            case 6:
                echo '-----------------------------------' . PHP_EOL;
                echo 'Goodbey!' . PHP_EOL;
                break;
            default:
                echo '-----------------------------------' . PHP_EOL;
                $this->glavniIzbornik();
        }
    }

    //___________________________ISPORUKA_____________________________

    private function isporukaIzbornik()//metode drzim privatnima a do njih cu doci preko constructora
     {
         echo 'Deliveries menu' . PHP_EOL;
         echo '1.Review' . PHP_EOL;
         echo '2.Enter a new delivery' . PHP_EOL;
         echo '3.Changing the existing delivery' . PHP_EOL;
         echo '4.Deleting the existing delivery' . PHP_EOL;
         echo '5.Return to the main menu' . PHP_EOL;
         $this->odabirOpcijeIsporuka();
     }

     private function odabirOpcijeIsporuka()//metode drzim privatnima a do njih cu doci preko constructora
     {
         switch(Pomocno::rasponBroja('Choose an option: ',1,5))
         {
             case 1:
                 echo '-----------------------------------' . PHP_EOL;
                 $this->pregledIsporuke();
                 break;
             case 2:
                 echo '-----------------------------------' . PHP_EOL;
                 $this->unsoNoveIsporuke();
                 break;
             case 3:
                 if(count($this->prikupi)===0)
                 {
                     
                     echo 'There are no deliveries in the App!' . PHP_EOL;
                     echo '-----------------------------------' . PHP_EOL;
                     $this->isporukaIzbornik();
                 }else
                 {
                     echo '-----------------------------------' . PHP_EOL;
                     $this->promjenaIsporuke();
                 }
                 break;
             case 4:
                 if(count($this->prikupi)===0)
                 {
                     
                     echo 'There are no deliveries in the App!' . PHP_EOL;
                     echo '-----------------------------------' . PHP_EOL;
                     $this->isporukaIzbornik();
                 }else
                 {
                     echo '-----------------------------------' . PHP_EOL;
                     $this->brisanjeIsporuke();
                 }
                 break;
             case 5:
                 //echo '-----------------------------------' . PHP_EOL;
                 $this->glavniIzbornik();
                 break;
             default:
                 echo '-----------------------------------' . PHP_EOL;
                 $this->odabirOpcijeIsporuka();
         }
     }

     private function promjenaIsporuke()//metode drzim privatnima a do njih cu doci preko constructora
     {
         $this->pregledIsporuke(false); // šaljem mu false kako mi nebi opet ucito izbornik
         $rb = Pomocno::rasponBroja('Choose a delivery: ',1,count($this->isporuke) );
         $rb--;
         $this->isporuke[$rb]->datum = Pomocno::unosTeksta('Enter the new serial number of oxygen concentrator(' . 
         $this->isporuke[$rb]->datum .'): ', // PRIKAZ STAROG NAZIVA
         $this->isporuke[$rb]->datum); // slanje stare vrijednosti(drugog parametra) u unos teksta
        
         echo '-----------------------------------' . PHP_EOL;
         $this->prikupIzbornik();
     }
 
     private function unsoNoveIsporuke()//metode drzim privatnima a do njih cu doci preko constructora
     {
         $o = new stdClass(); //obavezna deklaracija PHP-MANUAL!!
         $o->datum = Pomocno::unosTeksta('Enter the date of delivery: dd.mm.yyy ');
         
         $this->pregledPacijenata(false); // treba dohvatiti od kojeg pacijenta se prikuplja
         $rb = Pomocno::rasponBroja('Choose a patient: ',1,count($this->pacijenti) );
         $rb--;
         $o->pacijent = $this->pacijenti[$rb];
 
         $this->pregledKisika(false);// treba dohvatiti koji kisik se prikuplja
         $rb = Pomocno::rasponBroja('Choose a oxygen concentrator: ',1,count($this->koncentratoriKisika) );
         $rb--;
         $o->koncentratorKisika = $this->koncentratoriKisika[$rb];
 
 
         echo '-----------------------------------' . PHP_EOL;
 
         $this->isporuke[] = $o; // spremi u niz koji je deklariran u constructoru 
         $this->isporukaIzbornik();//otvaram opet izbornik
     }
 
     private function pregledIsporuke($prikaziIzbornik=true)
     {
         
         echo'Delivery review' . PHP_EOL;
         $i=1;
         foreach ($this->isporuke as $v) {         
             echo $i++ . '. ' . $v->datum . 
             ' (' . $v->pacijent->ime . ' ' . $v->pacijent->prezime  . ' | Serial number of oxygen concentrator: ' .
             $v->koncentratorKisika->serijskiKod . ')' . PHP_EOL; // odma i poveca $i
         }
         
         if($prikaziIzbornik) // saljem mu taj samo false pa ce ga ucitat
         {
             echo '-----------------------------------' . PHP_EOL;
             $this->isporukaIzbornik();
         }
         
     }
 
     private function brisanjeIsporuke()
     {
         $this->pregledIsporuke(false); // šaljem mu false kako mi nebi opet ucito izbornik
         $rb = Pomocno::rasponBroja('Choose a delivery: ',1,count($this->isporuke) );
         $rb--;
         if($this->dev) // za developere provjera podataka
         {
             echo 'Prije' . PHP_EOL;
             print_r($this->isporuke);
         }
         array_splice($this->isporuke,$rb,1);// 1 znacida brisemo jedan element u arrayu
         if($this->dev)
         {
             echo 'Poslije' . PHP_EOL;
             print_r($this->isporuke);
         }
         $this->isporukaIzbornik();
     }


     //__________________________PRIKUP_________________________

     private function prikupIzbornik()//metode drzim privatnima a do njih cu doci preko constructora
     {
         echo 'Collections menu' . PHP_EOL;
         echo '1.Review' . PHP_EOL;
         echo '2.Enter a new collection' . PHP_EOL;
         echo '3.Changing the existing collection' . PHP_EOL;
         echo '4.Deleting the existing collection' . PHP_EOL;
         echo '5.Return to the main menu' . PHP_EOL;
         $this->odabirOpcijePrikup();
     }
 
     private function odabirOpcijePrikup()//metode drzim privatnima a do njih cu doci preko constructora
     {
         switch(Pomocno::rasponBroja('Choose an option: ',1,5))
         {
             case 1:
                 echo '-----------------------------------' . PHP_EOL;
                 $this->pregledPrikupa();
                 break;
             case 2:
                 echo '-----------------------------------' . PHP_EOL;
                 $this->unsoNovogPrikupa();
                 break;
             case 3:
                 if(count($this->prikupi)===0)
                 {
                     
                     echo 'There are no collections in the App!' . PHP_EOL;
                     echo '-----------------------------------' . PHP_EOL;
                     $this->prikupIzbornik();
                 }else
                 {
                     echo '-----------------------------------' . PHP_EOL;
                     $this->promjenaPrikupa();
                 }
                 break;
             case 4:
                 if(count($this->prikupi)===0)
                 {
                     
                     echo 'There are no collections in the App!' . PHP_EOL;
                     echo '-----------------------------------' . PHP_EOL;
                     $this->prikupIzbornik();
                 }else
                 {
                     echo '-----------------------------------' . PHP_EOL;
                     $this->brisanjePrikupa();
                 }
                 break;
             case 5:
                 //echo '-----------------------------------' . PHP_EOL;
                 $this->glavniIzbornik();
                 break;
             default:
                 echo '-----------------------------------' . PHP_EOL;
                 $this->odabirOpcijePrikup();
         }
     }
 
     private function promjenaPrikupa()//metode drzim privatnima a do njih cu doci preko constructora
     {
         $this->pregledPrikupa(false); // šaljem mu false kako mi nebi opet ucito izbornik
         $rb = Pomocno::rasponBroja('Choose a collection: ',1,count($this->prikupi) );
         $rb--;
         $this->prikupi[$rb]->datum = Pomocno::unosTeksta('Enter the new serial number of oxygen concentrator(' . 
         $this->prikupi[$rb]->datum .'): ', // PRIKAZ STAROG NAZIVA
         $this->prikupi[$rb]->datum); // slanje stare vrijednosti(drugog parametra) u unos teksta
        
         echo '-----------------------------------' . PHP_EOL;
         $this->prikupIzbornik();
     }
 
     private function unsoNovogPrikupa()//metode drzim privatnima a do njih cu doci preko constructora
     {
         $o = new stdClass(); //obavezna deklaracija PHP-MANUAL!!
         $o->datum = Pomocno::unosTeksta('Enter the date of collection: dd.mm.yyy ');
         
         $this->pregledPacijenata(false); // treba dohvatiti od kojeg pacijenta se prikuplja
         $rb = Pomocno::rasponBroja('Choose a patient: ',1,count($this->pacijenti) );
         $rb--;
         $o->pacijent = $this->pacijenti[$rb];
 
         $this->pregledKisika(false);// treba dohvatiti koji kisik se prikuplja
         $rb = Pomocno::rasponBroja('Choose a oxygen concentrator: ',1,count($this->koncentratoriKisika) );
         $rb--;
         $o->koncentratorKisika = $this->koncentratoriKisika[$rb];
 
 
         echo '-----------------------------------' . PHP_EOL;
 
         $this->prikupi[] = $o; // spremi u niz koji je deklariran u constructoru 
         $this->prikupIzbornik();//otvaram opet izbornik
     }
 
     private function pregledPrikupa($prikaziIzbornik=true)
     {
         
         echo'Collection review' . PHP_EOL;
         $i=1;
         foreach ($this->prikupi as $v) {         
             echo $i++ . '. ' . $v->datum . 
             ' (' . $v->pacijent->ime . ' ' . $v->pacijent->prezime  . ' | Serijski kod kisika: ' .
             $v->koncentratorKisika->serijskiKod . ')' . PHP_EOL; // odma i poveca $i
         }
         
         if($prikaziIzbornik) // saljem mu taj samo false pa ce ga ucitat
         {
             echo '-----------------------------------' . PHP_EOL;
             $this->prikupIzbornik();
         }
         
     }
 
     private function brisanjePrikupa()
     {
         $this->pregledPrikupa(false); // šaljem mu false kako mi nebi opet ucito izbornik
         $rb = Pomocno::rasponBroja('Choose a collection: ',1,count($this->prikupi) );
         $rb--;
         if($this->dev) // za developere provjera podataka
         {
             echo 'Prije' . PHP_EOL;
             print_r($this->prikupi);
         }
         array_splice($this->prikupi,$rb,1);// 1 znacida brisemo jedan element u arrayu
         if($this->dev)
         {
             echo 'Poslije' . PHP_EOL;
             print_r($this->prikupi);
         }
         $this->prikupIzbornik();
     }

    //___________________________________STANJE SKLADISTA_________________________

    private function stanjeIzbornik()//metode drzim privatnima a do njih cu doci preko constructora
    {
        echo 'Warehouse status menu' . PHP_EOL;
        echo '1.Review' . PHP_EOL;
        echo '2.Return to the main menu' . PHP_EOL;
        $this->odabirOpcijeStanje();
    }

    private function odabirOpcijeStanje()//metode drzim privatnima a do njih cu doci preko constructora
    {
        switch(Pomocno::rasponBroja('Choose an option: ',1,2))
        {
            case 1:
                echo '-----------------------------------' . PHP_EOL;
                $this->pregledStanja();
                break;
            case 2:
                //echo '-----------------------------------' . PHP_EOL;
                $this->glavniIzbornik();
                break;
            default:
                echo '-----------------------------------' . PHP_EOL;
                $this->odabirOpcijeStanje();
        }
    }

    private function pregledStanja($prikaziIzbornik=true)
    {
        echo'Warehouse status review' . PHP_EOL;
        $i=1;
        foreach ($this->koncentratoriKisika as $stanje) {         
            echo $i++ . '. ' . $stanje->serijskiKod . ' ' . $stanje->radniSat . PHP_EOL; // odma i poveca $i
        }
        
        if($prikaziIzbornik) // saljem mu taj samo false pa ce ga ucitat
        {
            echo '-----------------------------------' . PHP_EOL;
            $this->stanjeIzbornik();
        }
        
    }


    //_________________________________PACIJENT_____________________________

    private function pacijentIzbornik()//metode drzim privatnima a do njih cu doci preko constructora
    {
        echo 'Patient menu' . PHP_EOL;
        echo '1.Review' . PHP_EOL;
        echo '2.Enter a new patient' . PHP_EOL;
        echo '3.Changing the existing patient' . PHP_EOL;
        echo '4.Deleting the existing patient' . PHP_EOL;
        echo '5.Return to the main menu' . PHP_EOL;
        $this->odabirOpcijePacijent();
    }

    private function odabirOpcijePacijent()//metode drzim privatnima a do njih cu doci preko constructora
    {
        switch(Pomocno::rasponBroja('Choose an option: ',1,5))
        {
            case 1:
                echo '-----------------------------------' . PHP_EOL;
                $this->pregledPacijenata();
                break;
            case 2:
                echo '-----------------------------------' . PHP_EOL;
                $this->unsoNovogPacijenta();
                break;
            case 3:
                if(count($this->pacijenti)===0)
                {
                    
                    echo 'There are no patients in the App!' . PHP_EOL;
                    echo '-----------------------------------' . PHP_EOL;
                    $this->pacijentIzbornik();
                }else
                {
                    echo '-----------------------------------' . PHP_EOL;
                    $this->promjenaPacijenta();
                }
                break;
            case 4:
                if(count($this->pacijenti)===0)
                {
                    
                    echo 'There are no patients in the App!' . PHP_EOL;
                    echo '-----------------------------------' . PHP_EOL;
                    $this->pacijentIzbornik();
                }else
                {
                    echo '-----------------------------------' . PHP_EOL;
                    $this->brisanjePacijenta();
                }
                break;
            case 5:
                //echo '-----------------------------------' . PHP_EOL;
                $this->glavniIzbornik();
                break;
            default:
                echo '-----------------------------------' . PHP_EOL;
                $this->odabirOpcijePacijent();
        }
    }

    private function brisanjePacijenta()
    {
        $this->pregledPacijenata(false); // šaljem mu false kako mi nebi opet ucito izbornik
        $rb = Pomocno::rasponBroja('Choose a patient: ',1,count($this->pacijenti) );
        $rb--;
        if($this->dev) // za developere provjera podataka
        {
            echo 'Prije' . PHP_EOL;
            print_r($this->pacijenti);
        }
        array_splice($this->pacijenti,$rb,1);// 1 znacida brisemo jedan element u arrayu
        if($this->dev)
        {
            echo 'Poslije' . PHP_EOL;
            print_r($this->pacijenti);
        }
        $this->pacijentIzbornik();
    }

    private function promjenaPacijenta()//metode drzim privatnima a do njih cu doci preko constructora
    {
        $this->pregledPacijenata(false); // šaljem mu false kako mi nebi opet ucito izbornik
        $rb = Pomocno::rasponBroja('Choose a patient: ',1,count($this->pacijenti) );
        $rb--;
        $this->pacijenti[$rb]->ime = Pomocno::unosTeksta('Enter the new name of patient (' . 
        $this->pacijenti[$rb]->ime .'): ', // PRIKAZ STAROG NAZIVA
        $this->pacijenti[$rb]->ime); // slanje stare vrijednosti(drugog parametra) u unos teksta
        $this->pacijenti[$rb]->prezime = Pomocno::unosTeksta('Enter the new surname of patient(' . 
        $this->pacijenti[$rb]->prezime . '): ', 
        $this->pacijenti[$rb]->prezime); 
        $this->pacijenti[$rb]->telefon = pomocno::unosTeksta('Enter the new telephone number(' . 
        $this->pacijenti[$rb]->telefon . '): ', 
        $this->pacijenti[$rb]->telefon);
        $this->pacijenti[$rb]->datumrodenja = pomocno::unosTeksta('Enter the new birth date of patient( dd.mm.yyy '  . 
        $this->pacijenti[$rb]->datumrodenja . '): ', 
        $this->pacijenti[$rb]->datumrodenja);
        $this->pacijenti[$rb]->adress = pomocno::unosTeksta('Enter the new adress of patient(' . 
        $this->pacijenti[$rb]->adress . '): ', 
        $this->pacijenti[$rb]->adress);
        $this->pacijenti[$rb]->oib = pomocno::unosTeksta('Enter the new OIB of patient(' . 
        $this->pacijenti[$rb]->oib . '): ', 
        $this->pacijenti[$rb]->oib); 
        echo '-----------------------------------' . PHP_EOL;
        $this->pacijentIzbornik();
    }

    private function unsoNovogPacijenta()//metode drzim privatnima a do njih cu doci preko constructora
    {
        $s = new stdClass(); //obavezna deklaracija PHP-MANUAL!!
        $s->ime = Pomocno::unosTeksta('Enter the name of patient: ');
        $s->prezime = Pomocno::unosTeksta('Enter the surname of patient: ');
        $s->telefon = Pomocno::unosBroja('Enter the telephone number of patient: ');
        $s->datumrodenja = Pomocno::unosTeksta('Enter the birth date of patient: dd.mm.yyy ');
        $s->adress = Pomocno::unosTeksta('Enter the adress of patient: ');
        $s->oib = Pomocno::unosBroja('Enter the OIB of patient: ');
        echo '-----------------------------------' . PHP_EOL;

        $this->pacijenti[]=$s; // spremi u niz koji je deklariran u constructoru 
        $this->pacijentIzbornik();//otvaram opet izbornik
    }

    private function pregledPacijenata($prikaziIzbornik=true)
    {
        echo'Patient review' . PHP_EOL;
        $i=1;
        foreach ($this->pacijenti as $pacijent) {         
            echo $i++ . '. ' . $pacijent->ime . ' ' . $pacijent->prezime . PHP_EOL; // odma i poveca $i
        }
        
        if($prikaziIzbornik) // saljem mu taj samo false pa ce ga ucitat
        {
            echo '-----------------------------------' . PHP_EOL;
            $this->pacijentIzbornik();
        }
        
    }
    
    //_____________________________KONC KISIKA__________________________________

    private function odabirOpcijeKisik()//metode drzim privatnima a do njih cu doci preko constructora
    {
        switch(Pomocno::rasponBroja('Choose an option: ',1,5))
        {
            case 1:
                echo '-----------------------------------' . PHP_EOL;
                $this->pregledKisika();
                break;
            case 2:
                echo '-----------------------------------' . PHP_EOL;
                $this->unsoNovogKisika();
                break;
            case 3:
                if(count($this->koncentratoriKisika)===0)
                {
                        
                    echo 'There are no oxygen concentrators in the App!' . PHP_EOL;
                    echo '-----------------------------------' . PHP_EOL;
                    $this->kisikIzbornik();
                }else
                {
                    echo '-----------------------------------' . PHP_EOL;
                    $this->promjenaKisika();
                }
                break;
            case 4:
                if(count($this->koncentratoriKisika)===0)
                {
                        
                    echo 'There are no oxygen concentrators in the App!' . PHP_EOL;
                    echo '-----------------------------------' . PHP_EOL;
                    $this->kisikIzbornik();
                }else
                {
                    echo '-----------------------------------' . PHP_EOL;
                    $this->brisanjeKisika();
                }
                break;
            case 5:
                //echo '-----------------------------------' . PHP_EOL;
                $this->glavniIzbornik();
                break;
            default:
                echo '-----------------------------------' . PHP_EOL;
                $this->odabirOpcijeKisik();
        }
    }

    private function kisikIzbornik()//metode drzim privatnima a do njih cu doci preko constructora
    {
        echo 'Oxygen Concentrator menu' . PHP_EOL;
        echo '1.Review' . PHP_EOL;
        echo '2.Enter a Oxygen Concentrator' . PHP_EOL;
        echo '3.Changing the existing Oxygen Concentrator' . PHP_EOL;
        echo '4.Deleting the existing Oxygen Concentrator' . PHP_EOL;
        echo '5.Return to the main menu' . PHP_EOL;
        //echo '-----------------------------------' . PHP_EOL;
        $this->odabirOpcijeKisik();
    }

    private function brisanjeKisika()
    {
        $this->pregledKisika(false); // šaljem mu false kako mi nebi opet ucito izbornik
        $rb = Pomocno::rasponBroja('Choose a oxygen concentrator: ',1,count($this->pacijenti) );
        $rb--;
        if($this->dev) // za developere provjera podataka
        {
            echo 'Prije' . PHP_EOL;
            print_r($this->koncentratoriKisika);
        }
        array_splice($this->koncentratoriKisika,$rb,1);// 1 znacida brisemo jedan element u arrayu
        if($this->dev)
        {
            echo 'Poslije' . PHP_EOL;
            print_r($this->koncentratoriKisika);
        }
        $this->kisikIzbornik();
    }

    private function promjenaKisika()//metode drzim privatnima a do njih cu doci preko constructora
    {
        $this->pregledKisika(false); // šaljem mu false kako mi nebi opet ucito izbornik
        $rb = Pomocno::rasponBroja('Choose a oxygen concentrator: ',1,count($this->pacijenti) );
        $rb--;
        $this->koncentratoriKisika[$rb]->serijskiKod = Pomocno::unosTeksta('Enter the new serial number of oxygen concentrator(' . 
        $this->koncentratoriKisika[$rb]->serijskiKod .'): ', // PRIKAZ STAROG NAZIVA
        $this->koncentratoriKisika[$rb]->serijskiKod); // slanje stare vrijednosti(drugog parametra) u unos teksta
        $this->koncentratoriKisika[$rb]->radniSat = Pomocno::unosTeksta('Enter the new operating hours of oxygen concentrator(' . 
        $this->koncentratoriKisika[$rb]->radniSat . '): ', 
        $this->koncentratoriKisika[$rb]->radniSat); 
       
        echo '-----------------------------------' . PHP_EOL;
        $this->kisikIzbornik();
    }

    private function unsoNovogKisika()//metode drzim privatnima a do njih cu doci preko constructora
    {
        $s = new stdClass(); //obavezna deklaracija PHP-MANUAL!!
        $s->serijskiKod = Pomocno::unosTeksta('Enter the serial number of Oxygen Concentrator: ');
        $s->radniSat = Pomocno::unosTeksta('Enter the operating hours of the Oxygen Concentrator: ');
        echo '-----------------------------------' . PHP_EOL;

        $this->koncentratoriKisika[]=$s; // spremi u niz koji je deklariran u constructoru 
        $this->kisikIzbornik();//otvaram opet izbornik
    }

    private function pregledKisika($prikaziIzbornik=true)
    {
        
        echo'Oxygen concentrator review' . PHP_EOL;
        $i=1;
        foreach ($this->koncentratoriKisika as $koncentratorKisika) {         
            echo $i++ . '. ' . $koncentratorKisika->serijskiKod . ' ' . $koncentratorKisika->radniSat . PHP_EOL; // odma i poveca $i
        }
        
        if($prikaziIzbornik) // saljem mu taj samo false pa ce ga ucitat
        {
            echo '-----------------------------------' . PHP_EOL;
            $this->kisikIzbornik();
        }
        
    }

   


    private function testPodaci() // ubacivanje test podataka
    {

        $this->pacijenti[] = $this->kreirajPacijenta('Marko','Marulić');
        $this->pacijenti[] = $this->kreirajPacijenta('Nahid','Kulenović');
        $this->pacijenti[] = $this->kreirajPacijenta('Matija','Gubec');
        $this->pacijenti[] = $this->kreirajPacijenta('Nijaz','Batlak');

        $this->prikupi[] = $this-> kreirajPrikup('22.06.2022');
        $this->prikupi[] = $this-> kreirajPrikup('12.10.2022');

        $this->isporuke[] = $this-> kreirajIsporuku('02.08.2022');
        $this->isporuke[] = $this-> kreirajIsporuku('01.14.2022');

        $this->koncentratoriKisika[] = $this-> kreirajKoncentratorKisika('BK32048324','456h');
        $this->koncentratoriKisika[] = $this-> kreirajKoncentratorKisika('TU983928','7891h');

    }

    private function kreirajKoncentratorKisika($serijskiKod, $radniSat)
    {
        $o = new stdClass();
        $o->serijskiKod = $serijskiKod;
        $o->radniSat = $radniSat;
        return $o;
    }

    private function kreirajIsporuku($datum)
    {
        $o = new stdClass();
        $o->datum = $datum;
        return $o;
    }

    private function kreirajPrikup($datum)
    {
        $o = new stdClass();
        $o->datum = $datum;
        return $o;
    }

    private function kreirajPacijenta($ime, $prezime)
    {
        $o = new stdClass();
        $o->ime = $ime;
        $o->prezime = $prezime;
        return $o;
    }
}
//echo $argv[1], PHP_EOL; pregled
new Start($argc,$argv);