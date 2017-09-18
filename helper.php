<?php
defined('_JEXEC') or die;

class modwizardHelper {
  private static $_attestati;
  private static $_corsi;

  public function getAttestati() {
    try {

      $db = JFactory::getDbo();

      $user = JFactory::getUser();
      $userid = $user->get('id');


      $query = "SELECT DISTINCT   
      c.corso, e.id
      FROM
                  #__quiz_r_student_quiz AS q
      Inner Join  
                 #__gg_elementi AS e ON q.c_quiz_id = e.path
      Inner Join 
                   #__gg_moduli AS m ON m.id = e.id_modulo
      Inner Join  
                   #__gg_corsi AS c ON c.id = m.id_corso
      WHERE
      q.c_passed = 1 
      AND
      e.elemento LIKE '%attestato%'
      AND
      c_student_id =".$userid;



      $db->setQuery($query);
      if (false === ($results = $db->loadAssocList()))

       throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);


    //  if(empty($results[1]))
    //   $out[]=$results;
    // else
     $out=$results;

     self::$_attestati = $out;

     return self::$_attestati;

   } catch (Exception $e) {
    return array();
  }
}


public function getCorsi() {
  try {           

    $db = JFactory::getDbo();

    $user = JFactory::getUser();
    $userid = $user->get('id');               


    $query = "SELECT 

    corsi_abilitati

    FROM (
      SELECT
      c.corsi_abilitati, DATE_ADD(data_utilizzo,INTERVAL durata DAY) < NOW() AS scaduto
                      FROM #__gg_coupon as c
                      WHERE id_utente =".$userid." 
                       AND c.abilitato = 1

                      ) as CP
where scaduto = 0 or scaduto is null";


$db->setQuery($query);
if (false === ($results = $db->loadAssocList()))

 throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

  // if(!$results[1])
  //  $out[]=$results;
  // else
$out=$results;

 // in questa parte concateno in un unica stringa, affinchè possa poi passarla alla successiva query, tutti gli elemnti dell'array         

  $abilitati='';       // dichiaro una stringa
  if($out[0])           //controllo la stringa non sia vuota

  {
    foreach($out as $listaidcorsi)
        $abilitati.=",".$listaidcorsi['corsi_abilitati'];   //concateno la virgola con la stringa lista corsi
    }

$abilitati=substr($abilitati, 1);  

// Associo l'ID corso al nome per esteso
$query = "SELECT 
            *
          FROM 
            #__gg_corsi as c
           where c.id in ($abilitati)";
          

     $db->setQuery($query);
     if (false === ($risultato = $db->loadAssocList()))
      throw new RuntimeException($this->_db->getErrorMsg(), E_USER_ERROR);

    self::$_corsi = $risultato;  
    return self::$_corsi;

  }  catch (Exception $e) {
    return array();
  }
}
}

?>
