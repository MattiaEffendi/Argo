<?php

function getExtendedMonthName($month){
    switch ($month){
        case '01':
        case 'january':
            return "Gennaio";
            break;
        case '02':
        case 'february':
            return "Febbraio";
            break;
        case '03':
        case 'march':
            return "Marzo";
            break;
        case '04':
        case 'april':
            return "Aprile";
            break;
        case '05':
        case 'may':
            return "Maggio";
            break;
        case '06':
        case 'june':
            return "Giugno";
            break;
        case '07':
        case 'july':
            return "Luglio";
            break;
        case '08':
        case 'august':
            return "Agosto";
            break;
        case '09':
        case 'september':
            return "Settembre";
            break;
        case '10':
        case 'october':
            return "Ottobre";
            break;
        case '11':
        case 'november':
            return "Novembre";
            break;
        case '12':
        case 'december':
            return "Dicembre";
            break;
    }
}

function convertDayNumber($day){
  switch ($day){
      case 'Mon':
          return 1;
          break;
      case 'Tue':
          return 2;
          break;
      case 'Wed':
          return 3;
          break;
      case 'Thu':
          return 4;
          break;
      case 'Fri':
          return 5;
          break;
      case 'Sat':
          return 6;
          break;
      case 'Sun':
          return 7;
          break;
  }
}

function calendar($callbackPrefix, $monthcal, $yearcal){
  $day = date("D", strtotime("first day of ". $monthcal ." ". $yearcal));
  $month = getExtendedMonthName(date("m", strtotime($monthcal . " " . $yearcal)));
  $day = convertDayNumber($day);
  $week1 = array();
  if($monthcal == strtolower(date('F')) && $yearcal == strtolower(date('Y'))){
    $today = date('j');
  }
  else {
    $today = "-------";
  }
  for($i = 1; $i<$day; $i++){
    array_push($week1, array("text" => "-", "callback_data" => "Placeholder"));
  }
  $ii = 8 - $day;
  for($i = 1; $i<=$ii; $i++){
    if($i == $today){
      array_push($week1, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
    }
    else{
      array_push($week1, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
    }
    $lastDay = $i;
    global $lastDay;
  }

  $daysLeft = date("d", strtotime("last day of ". $monthcal ." ". $yearcal)) - $lastDay;
  $weeks = ceil($daysLeft / 7);
  if($weeks == 3){
    $week2 = array();
    $week3 = array();
    $week4 = array();
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i == $today){
        array_push($week2, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }
      else{
        array_push($week2, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }    }
    $lastDay = $lastDay + 7;
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i == $today){
        array_push($week3, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }
      else{
        array_push($week3, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }    }
    $lastDay = $lastDay + 7;
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i > date("d", strtotime("last day of ". $monthcal ." ". $yearcal))){
        array_push($week4, array("text" => "-", "callback_data" => "Placeholder"));
      }else{
        if($i == $today){
          array_push($week4, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
        }
        else{
          array_push($week4, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
        }
      }
    }
  }

  elseif($weeks == 4){
    $week2 = array();
    $week3 = array();
    $week4 = array();
    $week5 = array();
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i == $today){
        array_push($week2, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }
      else{
        array_push($week2, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }    }
    $lastDay = $lastDay + 7;
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i == $today){
        array_push($week3, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }
      else{
        array_push($week3, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }    }
    $lastDay = $lastDay + 7;
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i == $today){
        array_push($week4, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }
      else{
        array_push($week4, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }    }
    $lastDay = $lastDay + 7;
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i > date("d", strtotime("last day of ". $monthcal ." ". $yearcal))){
        array_push($week5, array("text" => "-", "callback_data" => "Placeholder"));
      }else{
        if($i == $today){
          array_push($week5, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
        }
        else{
          array_push($week5, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
        }      }
    }
  }
  elseif($weeks == 5){
    $week2 = array();
    $week3 = array();
    $week4 = array();
    $week5 = array();
    $week6 = array();
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i == $today){
        array_push($week2, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }
      else{
        array_push($week2, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }
    }
    $lastDay = $lastDay + 7;
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i == $today){
        array_push($week3, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }
      else{
        array_push($week3, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }    }
    $lastDay = $lastDay + 7;
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i == $today){
        array_push($week4, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }
      else{
        array_push($week4, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }    }
    $lastDay = $lastDay + 7;
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i == $today){
        array_push($week5, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }
      else{
        array_push($week5, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
      }    }

    $lastDay = $lastDay + 7;
    for($i = $lastDay+1; $i <= $lastDay + 7; $i++){
      if($i > date("d", strtotime("last day of ". $monthcal ." ". $yearcal))){
        array_push($week6, array("text" => "-", "callback_data" => "Placeholder"));
      }else{
        if($i == $today){
          array_push($week6, array("text" => "\xe2\x8f\xba ".$i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
        }
        else{
          array_push($week6, array("text" => $i, "callback_data" => $callbackPrefix."|".$i."-$monthcal-$yearcal"));
        }
      }
    }
  }

  $kb[] = array(
    array(
      "text" => "Lun",
      "callback_data" => "Placeholder"
    ),
    array(
      "text" => "Mar",
      "callback_data" => "Placeholder"
    ),
    array(
      "text" => "Mer",
      "callback_data" => "Placeholder"
    ),
    array(
      "text" => "Gio",
      "callback_data" => "Placeholder"
    ),
    array(
      "text" => "Ven",
      "callback_data" => "Placeholder"
    ),
    array(
      "text" => "Sab",
      "callback_data" => "Placeholder"
    ),
    array(
      "text" => "Dom",
      "callback_data" => "Placeholder"
    ),
  );
  $kb[] = $week1;
  $kb[] = $week2;
  $kb[] = $week3;
  $kb[] = $week4;
  if($weeks == 4 || $weeks == 5){
    $kb[] = $week5;
  }
  if($weeks == 5){
    $kb[] = $week6;
  }
  return $kb;
}
