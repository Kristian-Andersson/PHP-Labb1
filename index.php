<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
date_default_timezone_set("Europe/Stockholm");

function checkFormat() {
    //Lägger csv filen i en array
    $csv = array_map('str_getcsv', file('lista.csv'));
    $totalSum = [];
    //Tar alla funktionens argument och lägger den i en array
    $landskoder = func_get_args();
    
    //Loopar igenom alla landskoder man har anropat funktionen med
    foreach ($landskoder as $x => $lk) {
        $felmeddelande = TRUE;
        //Loopar igenom arrayerna och sendan arrayerna inuti den
        foreach ($csv as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if (strpos($value2, $lk) && preg_match("/^#[A-Z]{2}\d{6}$/", $value2)) {
                    //Lägger alla raders kalkylationer i en tom variabel
                    $calcSum = $value[1] * $value[2];
                    //Pushar variabeln in i en tom array
                    array_push($totalSum, $calcSum);
                    //Kan ändra statusen från "TRUE" till "FALSE" eftersom här inne har den klarat av if "kraven"
                    $felmeddelande = FALSE;
                }
            }
        }

        //Kollar om $felmeddelande är TRUE, isåfall skriv ut felmeddelande
        if ($felmeddelande) {
            $status = "Failure";
            echo "Landskoden: " . $lk ." finns inte!<br> Status: " . $status . "<hr>";
        //Annars om det är FALSE så skriver den ut Status, Landskod och Totalsumma som sedan skrivs in i en fil med dagens datum/tid
        } else {
            $status = "Success";
            $finalSum = array_sum($totalSum);
            //Skriver ut de önskade värdena
            echo "Status: " . $status . "<br> Landskod: " . $lk . "<br> Totalsumma: " . $finalSum . "<hr>";
            //Lägger till värdena i en variabel med det korrekta formatet
            $string = $status . ", " . $lk . ", " . $finalSum;
            $fileHandle = fopen($lk . "-" . date("Ymd") . "-" . date("His") . ".csv", "w+");
            
            fwrite($fileHandle, $string);
            fclose($fileHandle);
        }
    }
}

echo checkFormat('SE');