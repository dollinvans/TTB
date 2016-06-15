<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Gästebuch</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Pinnwand</h1>
        <fieldset>
            <legend>Neuer Eintrag</legend>
            <a href="schreiben.html">Neuer Eintrag hier >>> </a>
        </fieldset>
        <fieldset>
            <legend>Post its</legend>
            <?php
            //Verbindung zur Datenbank (Prot, Datenbankname, Passwort)
            $verbindung = mysqli_connect("localhost", "ni967587_2sql2", "testpw")
                    or die("Fehler im System");

            //explizite Datenbank auswählen
            mysqli_select_db($verbindung, "ni967587_2sql2")
                    or die("Keine Verbindung zur Datenbank");

            //Seite suchen und abfragen welche seite es ist
            $pagesuche = 0;
            $url = $_SERVER["REQUEST_URI"];
            $pagesuche = strpos($url, "?page=");

            //wenn es die Index ist dann setzte page auf 1
            if ($pagesuche == "") {
                $page = 1;
            }
            //ansonnsten hole die page Nummer
            else {
                $page = $_GET["page"];
            }

            /*
             * auf jeder Seite könne 5 Einträge stehen deswegen miuns 5
             * und nach dem 5. Eintrag wird die page hochgezählt
             * somit wird dieser auf der nächsten Seite gespeichert
             * ==========================
             * Bsp:
             * wir sind auf page 3
             * 
             * $page(3) * 5 =15
             * 15 - 5 = 10
             * also schreibe vom 6. bis 10. Eintrag auf seite 3
             * 
             * 10 =10+1   --> 11
             * somit setze den nächsten auf position 11 und damit auf seite 4
             */
            $wo = ($page * 5) - 5;
            $wo++;

            $abfrage = "SELECT id FROM gbook ORDER BY id DESC";
            $ergebnis = mysqli_query($verbindung, $abfrage)
                    or die(mysqli_error($verbindung));

            $zahl = 1;
            $pos = 1;
            /*
             * aber dieser id sollen die Einträge ausgegeben werden
             * bei page = 2 ist das der eintrag 6
             */
            while ($row = mysqli_fetch_object($ergebnis)) {
                if ($zahl == $wo) {
                    $pos = $row->id;
                }
                $zahl++;
            }

            $abfrage = "SELECT * FROM gbook WHERE id <='$pos' ORDER BY id DESC LIMIT 5";
            $ergebnis = mysqli_query($verbindung, $abfrage)
                    or die(mysqli_error($verbindung));

            while ($row = mysqli_fetch_object($ergebnis)) {
                ?>
                <h3><?php echo $row->name; ?>&nbsp;<small>schrieb</small> </h3>
                <p><?php echo $row->nachricht; ?></p>
                <h5><?php echo $row->datum; ?></h5>
                <hr>
                <?php
            }
            ?>
        </fieldset>
        <fieldset>
            <legend>Navigation</legend>
            <?php if($page >1){
                ?>
            <a href="index.php?page=<?php echo ($page -1); ?>"> Zurück</a>
            <?php
            }
            
            $anzahlseiten = ceil(($zahl-1)/5);
            $weiter = $anzahlseiten - $page;
            
            if($weiter >0){
            ?>
            <a href="index.php?page=<?php echo ($page +1); ?>"> Weiter</a>
            <?php
            }
            ?>
        </fieldset>
    </body>
</html>
