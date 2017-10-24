<?php

function setPage($userID, $page = '-'){
    global $sql;
    $sth = $sql->prepare('UPDATE Utenti SET Stato = :page WHERE ID = ' . $userID);
    $sth->bindParam(':page', $page, PDO::PARAM_STR, 7);
    $sth->execute();
}

function getStatus($userID){
    global $sql;
    $sth = $sql->prepare('SELECT * FROM Utenti WHERE ID = ' . $userID);
    $sth->execute();
    $res = $sth->fetch(PDO::FETCH_ASSOC);
    $stato = $res['Stato'];
    return $stato;
}

require_once 'argoapi.php';
if($update){
    $kbb[] = array(
        array(
            "text" => "\xf0\x9f\x94\x90 Effettua il login",
            "callback_data" => "Login"
        )
    );
    $q = $sql->prepare('SELECT * FROM Utenti WHERE ID = :id');
    $q->execute(array(':id' => $userID));
    $res = $q->fetch(PDO::FETCH_ASSOC);
    if($res['LoggedIn'] == "Si") {
        if ($res['AuthToken'] != "-") {
            try {
                $user = new argoUser($res['SchoolCode'], $res['Username'], $res['AuthToken'], 1);
            }
            catch (Exception $e) {
                sm($chatID, "\xe2\x8f\xb0 <b>Sessione scaduta</b>\nLa tua sessione è scaduta, potrebbe essere per un errore nelle credenziali. Ri-effettua il login.", $kbb);
            }

        }
    }
}


if($msg == "/start")
{
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
	sm($chatID, "\xf0\x9f\x93\x9a <b>Ciao!</b>\n<b>Benvenuto su PArgoBot</b>!\n\nQuesto bot ti permette di vedere i tuoi <b>voti</b>, <b>compiti</b> e tutto ciò che vedresti sul sito di Argo direttamente da <b>Telegram!</b>\n\nPer iniziare, clicca il pulsante qui sotto per <b>effetturare il login</b>.", $kb);
    $q = $sql->prepare('SELECT * FROM Utenti WHERE ID = :id');
    $q->execute(array(':id' => $userID));
    if($q->rowCount() == 0){
        $q = $sql->prepare('INSERT INTO Utenti(ID) VALUES(:id)');
        $q->execute(array(':id' => $userID));
    }

}