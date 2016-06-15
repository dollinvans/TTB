<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>senden</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <?php
        /* werte aus der Form holen mit dem Attribut 'name' und in neue
          Variablen speichern */
        $name = $_POST["name"];
        $mail = $_POST["mail"];
        $nachricht = $_POST["nachricht"];

        //Überprüfen dass die EIngabefelder nicht leer sind           
        if ($name == "" or $mail == "" or $nachricht == "") {
            echo "Nichts ausgefüllt";
        } else {
            //Verbindung zur Datenbank (Port, Datenbankname, Passwort)
            $verbindung = mysqli_connect("localhost", "ni967587_2sql2", "testpw")
                    or die("Fehler im System");

            //explizite Datenbank auswählen
            mysqli_select_db($verbindung, "ni967587_2sql2")
                    or die("Keine Verbindung zur Datenbank");

            /* neue Variablen id, abfrage und ergebnis
             * abfrage: wird alles absteigend nach der id sortiert 
             * und der erste Eintrag genommen
             */
            $id = 0;
            $abfrage = "SELECT id FROM gbook ORDER BY id DESC LIMIT 1";

            //abfrage senden
            $ergebnis = mysqli_query($verbindung, $abfrage)
                or die(mysqli_error($verbindung));
            while ($row = mysqli_fetch_object($ergebnis)) {
                $id = $row->id;
            }
            $id++;

            //Variable für genaues Datum
            $timestamp = time();
            $datum = date("d.m.Y", $timestamp);

            //Umlaute behandeln
            $nachricht = str_replace("ä", "&auml;", $nachricht);
            $nachricht = str_replace("Ä", "&Auml;", $nachricht);
            $nachricht = str_replace("ü", "&uuml;", $nachricht);
            $nachricht = str_replace("Ü", "&Uuml;", $nachricht);
            $nachricht = str_replace("ö", "&ouml;", $nachricht);
            $nachricht = str_replace("Ö", "&Ouml;", $nachricht);
            $nachricht = str_replace("ß", "&szlig;", $nachricht);
            $nachricht = str_replace("<", "<&nbsp;", $nachricht);
            $nachricht = str_replace(">", ">&nbsp;", $nachricht);
            $nachricht = str_replace("\r\n", "<br/>", $nachricht);

            $name = str_replace("ä", "&auml;", $name);
            $name = str_replace("Ä", "&Auml;", $name);
            $name = str_replace("ü", "&uuml;", $name);
            $name = str_replace("Ü", "&Uuml;", $name);
            $name = str_replace("ö", "&ouml;", $name);
            $name = str_replace("Ö", "&Ouml;", $name);
            $name = str_replace("ß", "&szlig;", $name);
            $name = str_replace("<", "<&nbsp;", $name);
            $name = str_replace(">", ">&nbsp;", $name);

            //werte in Tabelle gbook eintragen und absenden
            $eintrag = "INSERT INTO gbook
                        (id, name, mail, nachricht, datum)
                        
                        VALUES
                        ('$id','$name', '$mail', '$nachricht', '$datum')";
            $eintragen = mysqli_query($verbindung, $eintrag);

            if ($eintragen == true) {
                ?>
                <p>Vielen Dank der Eitrag wurde gespeichert</p>
                <p><a href="index.php">Alle bisherigen Eintrag hier >>> </a></p>
                <p><a href="index.html">Zurück zur Startseite</a></p>
                <?php
            }
            else{
                echo "Fehler es wurde nichts gespeichert";
            }
            
            //Verbindung wird wieder geschlossen
            mysqli_close($verbindung);
        }
        ?>
    </body>
</html>
