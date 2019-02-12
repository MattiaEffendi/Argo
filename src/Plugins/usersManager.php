
<?php

global $sql;

function createKeyboard($msg){
    $reega = explode(' | ', $msg);
    foreach ($reega as $riga) {
        $ex = explode(' - ', $riga);
        $menu[] = array(
            array(
                "text" => $ex[0],
                "url" => $ex[1]
            )
        );
    }
    return $menu;
}

if($msg == "/admin" && $userID == 135094094){
    $menu[] = array(
        array(
            "text" => "\xf0\x9f\x91\xa4 Iscritti",
            "callback_data" => "Utenti"
        )
    );
    $menu[] = array(
        array(
            "text" => "\xf0\x9f\x93\xa3 Post Globale",
            "callback_data" => "InviaGPost"
        )
    );
    sm($chatID, "\xf0\x9f\xa4\x96 <b>Admin menu</b>\nBenvenuto nel menu admin del bot.\n\n<i>Seleziona un'opzione dal menu.</i>", $menu);
}

if($cbdata == "AdminMenu" && $userID == 135094094){
    $menu[] = array(
        array(
            "text" => "\xf0\x9f\x91\xa4 Iscritti",
            "callback_data" => "Utenti"
        )
    );
    $menu[] = array(
        array(
            "text" => "\xf0\x9f\x93\xa3 Post Globale",
            "callback_data" => "InviaGPost"
        )
    );
    cb_reply($cbid, $cbtext, false, $cbmid, "\xf0\x9f\xa4\x96 <b>Menu Admin</b>\nBenvenuto nel menu admin del bot.\n\n<i>Seleziona un'opzione dal menu.</i>", $menu);
}

if($cbdata == "Utenti" && $userID == 135094094){
    $q = $sql->prepare('SELECT * FROM Utenti WHERE GPost = \'Si\'');
    $q->execute();
    $vivi = $q->rowCount();
    $qq = $sql->prepare('SELECT * FROM Utenti WHERE GPost = \'No\'');
    $qq->execute();
    $morti = $qq->rowCount();
    $qq = $sql->prepare('SELECT * FROM Utenti WHERE GPost = \'Ri\'');
    $qq->execute();
    $block = $qq->rowCount();
    $totale = $vivi+$morti+$block;
    $menu[] = array(
        array(
            "text" => "\xf0\x9f\x94\x99 Torna Indietro",
            "callback_data" => "AdminMenu"
        )
    );
    cb_reply($cbid, $cbtext, false, $cbmid, "\xf0\x9f\x91\xa4 <b>Iscritti al bot</b>\n\n\xf0\x9f\x91\xa5 <b>Iscritti totali:</b> $totale\n\n\xf0\x9f\x94\x88 <b>Iscritti con post globali attivi:</b> $vivi\n\xf0\x9f\x94\x87 <b>Iscritti con post globali disattivati:</b> $morti\n\n\xf0\x9f\x9a\xab<b>Utenti che hanno bloccato il bot:</b> $block", $menu);
}

if($cbdata == "AnnullaGPost" && $userID == 135094094){
    $menu[] = array(
        array(
            "text" => "\xf0\x9f\x91\xa4 Iscritti",
            "callback_data" => "Utenti"
        )
    );
    $menu[] = array(
        array(
            "text" => "\xf0\x9f\x93\xa3 Post Globale",
            "callback_data" => "InviaGPost"
        )
    );
    setPage($userID);
    cb_reply($cbid, 'Annullato!', false, $cbmid, "\xf0\x9f\xa4\x96 <b>Menu Admin</b>\nBenvenuto nel menu admin del bot.\n\n<i>Seleziona un'opzione dal menu.</i>", $menu);
}

if($cbdata == "InviaGPost"){
    $menu[] = array(
        array(
            "text" => "Annulla",
            "callback_data" => "AnnullaGPost"
        )
    );
    setPage($userID, 'SendingGPost');
    cb_reply($cbid, $cbtext, false, $cbmid, "\xf0\x9f\x93\xa3 <b>Invia post globale</b>\nInvia ora il messaggio.", $menu);
}

if($msg && getStatus($userID) == "SendingGPost"){
    $menu[] = array(
        array(
            "text" => "Annulla",
            "callback_data" => "AnnullaGPost"
        )
    );
    unlink('Plugins/GPost.json');
    file_put_contents('Plugins/GPost.json', $msg);
    sm($chatID, "\xf0\x9f\x92\xa1 <b>Ricevuto!</b>\nInvia ora la tastiera inline del messaggio. Se non vuoi inserirla, scrivi \"NO\".", $menu);
    setPage($userID, 'InviaTastieraInline');
    exit;
}

if($msg && getStatus($userID) == "InviaTastieraInline") {
    if ($msg == "NO") {
        $menu[] = array(
            array(
                "text" => "Sì!",
                "callback_data" => "SendGPost"
            ),
            array(
                "text" => "No.",
                "callback_data" => "AnnullaGPost"
            )
        );
        unlink('Plugins/KBInline.json');
        file_put_contents('Plugins/KBInline.json', '-');
        setPage($userID);
        sm($chatID, "\xf0\x9f\x92\xa1 Ok!\nIl messaggio inviato sarà questo:");
        sm($chatID, file_get_contents('Plugins/GPost.json'));
        sm($chatID, '<b>Invio?</b>', $menu);
    } else {
        $menu[] = array(
            array(
                "text" => "Sì!",
                "callback_data" => "SendGPost"
            ),
            array(
                "text" => "No.",
                "callback_data" => "AnnullaGPost"
            )
        );
        unlink('Plugins/KBInline.json');
        file_put_contents('Plugins/KBInline.json', $msg);
        setPage($userID);
        $kb = createKeyboard($msg);
        sm($chatID, "\xf0\x9f\x92\xa1 Ok!\nIl messaggio inviato sarà questo:");
        sm($chatID, file_get_contents('Plugins/GPost.json'), $kb);
        sm($chatID, '<b>Invio?</b>', $menu);
    }
}

/*function sendGPost($text, $keyboard){
    global $sql;
    $q = $sql->prepare("SELECT * FROM Utenti WHERE GPostStatus = 'Sending'");
    $q->execute();
    $num = $q->rowCount();
    $div = intval($num)/intval(20);
    $i = round($div, 0, PHP_ROUND_HALF_UP);
    $n = 0;
    $done = 0;
    while($done < $num){
        $start = intval($n)*20;
        $qq = $sql->prepare("SELECT * FROM Utenti WHERE GPostStatus = 'Sending' LIMIT :n,:loop");
        $qq->execute(array(':n' => $start, ':loop' => 20));
        while($res = $qq->fetch(PDO::FETCH_ASSOC)){
            $chatID = $res['ID'];
            sm($chatID, $text/*, $keyboard*//*);
            $done++;
        }
        $n++;

    }
}*/

$tabella = 'Utenti';

function invia_post($user, $msg, $kb, $messageID)
{
    global $sql;
    global $tabella;
    global $cbid;
    global $cbtext;
    $s = $sql->query("SELECT * FROM Utenti WHERE GPostStatus = 'Sending'");
    if($kb == "-") $tastiera = null;
    else $tastiera = createKeyboard($kb);
    $i = 0;
    $t = $s->rowCount();
    while ($b = $s->fetch(PDO::FETCH_ASSOC)) {
        if (sm($b['ID'], $msg, $tastiera)) {
            $sql->exec('UPDATE ' . $tabella . " SET GPostStatus = '-' WHERE ID = " . $b['ID']);
            $i++;
            sleep(0.5);
            $percentage = (intval($i)/intval($t))*100;
            cb_reply($cbid, $cbtext, false, $messageID, "\xf0\x9f\x92\xa1 <b>Ok!</b>\nInvio il post...\n\n<b>Inviato</b>: ".$i."/".$t." (<b>".$percentage."%</b>)");
        } else {
            $sql->exec('UPDATE ' . $tabella . " SET GPost = 'Ri' WHERE ID = " . $b['ID']);
            sleep(0.5);
            $i++;
            $percentage = (intval($i)/intval($t))*100;
            cb_reply($cbid, $cbtext, false, $messageID, "\xf0\x9f\x92\xa1 <b>Ok!</b>\nInvio il post...\n\n<b>Inviato</b>: ".$i."/".$t." (<b>".$percentage."%</b>)");
        }
    }
    delete($user, $messageID);
    $kbbbbb[] = array(
        array(
            "text" => "Indietro",
            "callback_data" => "AdminMenu"
        )
    );
    sm($user, "\xe2\x98\x91\xef\xb8\x8f <b>Post inviato a tutti gli utenti!</b>", $kbbbbb);
}

if($cbdata == "SendGPost"){
    $q = $sql->prepare("UPDATE Utenti SET GPostStatus = 'Sending' WHERE GPost = 'Si'");
    $q->execute();
    $qq = $sql->prepare("SELECT * FROM Utenti WHERE GPostStatus = 'Sending'");
    $qq->execute();
    $tot = $qq->rowCount();
    cb_reply($cbid, 'Ok!', false, $cbmid, "\xf0\x9f\x92\xa1 <b>Ok!</b>\nInvio il post...\n\n<b>Inviato:</b> 0/".$tot." (<b>0%</b>)");
    invia_post($userID, file_get_contents('Plugins/GPost.json'), file_get_contents('Plugins/KBInline.json'), $cbmid);


}


