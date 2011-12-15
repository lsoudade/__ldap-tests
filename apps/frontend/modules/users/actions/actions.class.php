<?php

/**
 * users actions.
 *
 * @package    GL-Events-tests
 * @subpackage users
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class usersActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Tools');
      
      echo '<b>Connexion au serveur</b>';
      //$conn = ldap_connect('example.com');
      $conn = ldap_connect('ldap://localhost:389');
      echo ' - OK<br /><br />';
      
      ///////////////////////////////////////////////
      
      echo '<b>Paramètrage de la version</b>';
      ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
      echo ' - OK<br /><br />';
      
      ///////////////////////////////////////////////
      
      echo '<b>Bind</b>';
      ldap_bind($conn, 'cn=admin,dc=example,dc=com', 'admin');
      echo ' - OK<br /><br />';
      
      ///////////////////////////////////////////////
      
      echo '<b>Création d\'une entrée CN = Lorraine Soudade</b>';
      // Prépare les données
      $new_entry                 = array();
      $new_entry['CN']           = 'Lorraine Soudade';
      $new_entry['SN']           = 'Soudade';
      $new_entry['objectClass'][0]  = 'person';
      $new_entry['objectClass'][1]  = 'top';
      $rdn = "CN=".$new_entry ["CN"].",OU=people,DC=example,DC=com";
      
      // Ajoute les données au dossier
      ldap_add($conn, $rdn, $new_entry);
      echo ' - OK<br /><br />';
      
      ///////////////////////////////////////////////
      
      $this->search($conn);
      
      ///////////////////////////////////////////////
     
      echo '<b>Modification de l\'entrée SN = Soudade => SN = Test</b>';
      // Prépare les données
      $modify_entry                 = array();
      $modify_entry['SN']           = 'Test';
      $modify_entry['objectClass'][0]  = 'person';
      $modify_entry['objectClass'][1]  = 'top';
      $rdn = "CN=".$new_entry["CN"].",OU=people,DC=example,DC=com";
      
      // Ajoute les données au dossier
      ldap_modify($conn, $rdn, $modify_entry);
      echo ' - OK<br /><br />';
      
      ///////////////////////////////////////////////
      
      $this->search($conn);
      
      ///////////////////////////////////////////////
      
      echo '<b>Suppression de lorraine</b>';
      ldap_delete($conn, $rdn);
      echo ' - OK<br /><br />';
      
      ///////////////////////////////////////////////
      
      $this->search($conn);
      
      ///////////////////////////////////////////////
      
      $this->mass_add($conn, 10);
      
      $this->mass_add($conn, 100);
      
      $this->mass_add($conn, 1000);
      
      ///////////////////////////////////////////////
      
      echo '<b>Fermeture de la connexion</b>';
      ldap_close($conn); 
      echo ' - OK<br /><br />';
      
  }
  
  public function search($conn)
  {
      echo '<b>Recherche sur CN = lorraine*</b><br />';
      $search = ldap_search($conn, "OU=people,DC=example,DC=com", "cn=lorraine*");  
      echo ldap_count_entries($conn, $search) . ' entrée(s) trouvée(s)<br />';
      
      foreach ( ldap_get_entries($conn, $search) as $key => $entry ) :
         if ( is_array($entry) ) : 
             echo '<em>dn =</em> ' . $entry['dn'] . '<br />';
             echo '<em>cn =</em> ' . $entry['cn'][0] . '<br />';
             echo '<em>sn =</em> ' . $entry['sn'][0] . '<br />';
         endif;
      endforeach;
      echo '<br />';
  }
  
  public function mass_add($conn, $nb)
  {
      echo '<b>Test d\'écritures multiples (' . $nb . ')</b><br />';
      $time_start = microtime(true); 
      for ( $i=1 ; $i <=$nb ; $i++ ) 
      {
          $new_entry                 = array();
          $new_entry['CN']           = 'Test ' . $i;
          $new_entry['SN']           = 'Test ' . $i;
          $new_entry['objectClass'][0]  = 'person';
          $new_entry['objectClass'][1]  = 'top';
          $rdn = "CN=".$new_entry["CN"].",OU=people,DC=example,DC=com";

          // Ajoute les données au dossier
          ldap_add($conn, $rdn, $new_entry);
      }
      $time_end = microtime(true);
      $time = $time_end - $time_start;
      echo 'Executé en ' . $time . ' secondes<br />';     
      
      echo 'Suppression des ' . $nb . ' entrées en ';
      $time_start = microtime(true); 
      for ( $i=1 ; $i <=$nb ; $i++ ) 
      {
          $new_entry = 'Test ' . $i;
          $rdn = "CN=" . $new_entry . ",OU=people,DC=example,DC=com";
          ldap_delete($conn, $rdn);
      }
      $time_end = microtime(true);
      $time = $time_end - $time_start;
      echo $time . ' secondes - OK<br /><br />';
  }
}
