<?php

$cbtext = "\xf0\x9f\x91\x8c\xf0\x9f\x8f\xbb Ok!";

if($cbdata == "Informazioni"){
    $kb[] = array(
        array(
            "text" => "\xf0\x9f\x8f\xa1 Torna al menu",
            "callback_data" => "Start"
        )
    );
    cb_reply($cbid, $cbtext, false, $cbmid, "\xf0\x9f\xa4\x94 <b>Informazioni e F.A.Q.</b>\n\n<b>Le password vengono salvate?</b>\nNo, le password non vengono salvate, come puoi vedere dal <a href='https://github.com/iDoppioclick/ArgoBot'>codice sorgente</a>. Una volta effettuato il login viene creata una sessione, e viene salvato solo il token univoco.\n\n<b>Lo sviluppatore può vedere la mia password, voti o qualsiasi altro dato personale?</b>\nAssolutamente no, ma per sicurezza consiglio sempre, quando si effettua il login, di eliminare il messaggio contenente la password, in modo tale da non permettere a nessun altro di vederla.\n\n<b>Posso contribuire al bot?</b>\nOvviamente. Puoi inviare una pull request (Richiesta di modifica del codice) nella pagina del bot su <a href='https://github.com/iDoppioclick/ArgoBot'>GitHub</a>.", $kb, true);
}

if($cbdata == "Start") {
    $kb[] = array(
        array(
            "text" => "\xf0\x9f\x94\x90 Effettua il login",
            "callback_data" => "Login"
        )
    );
    $kb[] = array(
        array(
            "text" => "\xe2\x84\xb9\xef\xb8\x8f Informazioni",
            "callback_data" => "Informazioni"
        )
    );
    cb_reply($cbid, $cbtext, false, $cbmid, "\xf0\x9f\x93\x9a <b>Ciao!</b>\n<b>Benvenuto su PArgoBot</b>!\n\nQuesto bot ti permette di vedere i tuoi <b>voti</b>, <b>compiti</b> e tutto ciò che vedresti sul sito di Argo direttamente da <b>Telegram!</b>\n\nPer iniziare, clicca il pulsante qui sotto per <b>effetturare il login</b>.", $kb);
}

if($cbdata == "Login"){
    setPage($userID, 'SendSchoolCode');
    $kb[] = array(
        array(
            "text" => "\xe2\x81\x89\xef\xb8\x8f Come lo ottengo?",
            "callback_data" => "GetSchoolCode|$cbmid"
        )
    );
    $kb[] = array(
        array(
            "text" => "\xe2\x9d\x8c Annulla",
            "callback_data" => "AnnullaLogin"
        )
    );
    cb_reply($cbid, $cbtext, false, $cbmid, "\xe2\x8c\xa8\xef\xb8\x8f <b>Inviami ora il codice scuola.</b>\nQuesto sarà necessario per effettuare il login con la tua scuola.", $kb);
}

if($cbdata == "LoginIMG"){
    delete($userID, $cbmid);
    setPage($userID, 'SendSchoolCode');
    $kb[] = array(
        array(
            "text" => "\xe2\x81\x89\xef\xb8\x8f Come lo ottengo?",
            "callback_data" => "GetSchoolCode|$cbmid"
        )
    );
    $kb[] = array(
        array(
            "text" => "\xe2\x9d\x8c Annulla",
            "callback_data" => "AnnullaLogin"
        )
    );
    sm($chatID, "\xe2\x8c\xa8\xef\xb8\x8f <b>Inviami ora il codice scuola.</b>\nQuesto sarà necessario per effettuare il login con la tua scuola.", $kb);
}

if($cbdata && explode("|", $cbdata)[0] == "GetSchoolCode"){
    $menu[] = array(
        array(
            "text" => "\xf0\x9f\x94\x99 Indietro",
            "callback_data" => "LoginIMG"
        )
    );
    setPage($userID);
    delete($userID, $cbmid);
    si($chatID, 'AgADBAADgasxG7NbIFN9fvCm1v7lfAe94RkABB1kCBbn9kfhElADAAEC', $menu, "Il codice scuola è l'elemento evidenziato nella foto.\nPer ottenerlo, vai nella pagina di login della tua scuola, e cerca la dicitura \"Codice scuola\".");
}

if($cbdata == "AnnullaLogin"){
    $qq = $sql->prepare('DELETE FROM Utenti WHERE ID = '.$userID);
    $qq->execute();
    cb_reply($cbid, $cbtext, false, $cbmid, '<b>Annullato.</b>');
    sleep(2);
    $kb[] = array(
        array(
            "text" => "\xf0\x9f\x94\x90 Effettua il login",
            "callback_data" => "Login"
        )
    );
    $kb[] = array(
        array(
            "text" => "\xe2\x84\xb9\xef\xb8\x8f Informazioni",
            "callback_data" => "Informazioni"
        )
    );
    cb_reply($cbid, $cbtext, false, $cbmid, "\xf0\x9f\x93\x9a <b>Ciao!</b>\n<b>Benvenuto su PArgoBot</b>!\n\nQuesto bot ti permette di vedere i tuoi <b>voti</b>, <b>compiti</b> e tutto ciò che vedresti sul sito di Argo direttamente da <b>Telegram!</b>\n\nPer iniziare, clicca il pulsante qui sotto per <b>effetturare il login</b>.", $kb);
    $q = $sql->prepare('SELECT * FROM Utenti WHERE ID = :id');
    $q->execute(array(':id' => $userID));
    if($q->rowCount() == 0){
        $q = $sql->prepare('INSERT INTO Utenti(ID) VALUES(:id)');
        $q->execute(array(':id' => $userID));
    }
}

if($msg) {
    switch(getStatus($userID)) {
        case 'SendSchoolCode':
            $kb[] = array(
                array(
                "text" => "\xe2\x9d\x8c Annulla",
                "callback_data" => "AnnullaLogin"
                )
            );
            if (preg_match('/SG[0-9][0-9][0-9][0-9][0-9]/is', $msg, $matches)) {
                sm($chatID, "\xf0\x9f\x91\xa4 <b>Inviami ora l'username di Argo.</b>\nQuesto è l'unico dato che verrà salvato nel database oltre al codice scuola.", $kb);
                setPage($userID, 'SendUsername');
                $q = $sql->prepare('UPDATE Utenti SET SchoolCode = :sc WHERE ID = :id');
                $q->execute(array(':id' => $userID, ':sc' => strtoupper($msg)));
            }
            else
            {
                sm($chatID, "\xe2\x9c\x8b\xf0\x9f\x8f\xbb <b>Errore</b>\nIl codice scuola non è valido. Deve essere nel formato SGXXXXX.", $kb);
            }
        break;

        case 'SendUsername':
            $kb[] = array(
                array(
                    "text" => "\xf0\x9f\x94\x99 Indietro",
                    "callback_data" => "Login"
                )
            );
            if (!preg_match('/\s/', $msg)) {
                sm($chatID, "\xf0\x9f\x94\x90 <b>Inviami ora la password di Argo.</b>\nNon preoccuparti, questo dato <b>non</b> verrà salvato, verrà solo utilizzato per fare il login.\n\n<i>\xe2\x9a\xa0\xef\xb8\x8f Per una maggiore sicurezza, comunque, elimina il messaggio contenente la password dopo averla inviata.</i>", $kb);
                $q = $sql->prepare('UPDATE Utenti SET Username = :sc WHERE ID = :id');
                $q->execute(array(':id' => $userID, ':sc' => $msg));
                setPage($userID, 'SendPassword');
            }
            else
            {
                sm($chatID, "\xe2\x9c\x8b\xf0\x9f\x8f\xbb <b>Errore</b>\nL'username non è valido. Non può contenere spazi.", $kb);
            }
            break;

        case 'SendPassword':
            $kb[] = array(
                array(
                    "text" => "\xf0\x9f\x94\x99 Indietro",
                    "callback_data" => "Login"
                )
            );
$logged[] = array(array("text" => "Vai al pannello", "callback_data" => "Panel"));
            $json = sm($chatID, "<b>\xf0\x9f\xa4\x9e\xf0\x9f\x8f\xbb Provo ad effettuare il login...</b>\nAttendi.", $kb);
            setPage($userID);
            $MessageID = json_decode($json, TRUE)['result']['message_id'];
            $q = $sql->prepare('SELECT * FROM Utenti WHERE ID = :id');
            $q->execute(array(':id' => $userID));
            $res = $q->fetch(PDO::FETCH_ASSOC);
            sleep(2);
            try {
                $user = new argoUser($res['SchoolCode'], $res['Username'], $msg, 0);
                cb_reply($cbid, '', false, $MessageID, "<b>\xf0\x9f\x91\x8c\xf0\x9f\x8f\xbb Loggato correttamente!</b>", $logged);
                $q = $sql->prepare('UPDATE Utenti SET LoggedIn = :sc WHERE ID = :id');
                $q->execute(array(':id' => $userID, ':sc' => 'Si'));
            }
            catch (Exception $e) {
                if(strpos(" ".$e, 'Unable to login')) sm($chatID, "\xe2\x8f\xb0 <b>Sessione scaduta</b>\nLa tua sessione è scaduta, potrebbe essere per un errore nelle credenziali. Ri-effettua il login.", $kbb);
            }

            break;


    }
}


