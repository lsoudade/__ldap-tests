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
      
      $conn = $this->connect();
      
      $this->set_protocol($conn);
      
      $this->bind($conn);
      
      $rdn = $this->add($conn); // Add Lorraine Soudade
      
      $this->search($conn); // Search Lorraine Soudade
      
      $this->modify($conn, $rdn); // Modify Lorraine Soudade
      
      $this->search($conn); // Search Lorraine Soudade
      
      $this->delete($conn, $rdn); // Delete Lorraine Soudade
      
      $this->search($conn); // Search Lorraine Soudade
      
      $nb = 5000;
      $this->mass_add($conn, $nb);
      
      $this->mass_modify($conn, $nb);
      
      $rdn = $this->add($conn); // Add Lorraine Soudade
      
      $this->search($conn); // Search Lorraine Soudade
      
      $this->modify($conn, $rdn); // Modify Lorraine Soudade
      
      $this->search($conn); // Search Lorraine Soudade
      
      $this->delete($conn, $rdn); // Delete Lorraine Soudade
      
      $this->mass_delete($conn, $nb);
      
      $this->close($conn);
      
      
      /*///////////////////////////////////////////////
      SAMPLE
      
      echo 'Test d\'écritures multiples (3200)<br />
      Executé en 56.757442951202 secondes<br />
      Suppression des 3200 entrées en 59.831696033478 secondes - DESACTIVE<br /><br />';
      
      ///////////////////////////////////////////////*/
  }
  
  public function search($conn)
  {
      echo '<b>Recherche sur CN = lorraine*</b><br />';
      $time_start = microtime(true); 
      $search = ldap_search($conn, "OU=people,DC=example,DC=com", "cn=lorraine*");  
      echo ldap_count_entries($conn, $search) . ' entrée(s) trouvée(s)<br />';
      
      foreach ( ldap_get_entries($conn, $search) as $key => $entry ) :
         if ( is_array($entry) ) : 
             echo '<em>dn =</em> ' . $entry['dn'] . '<br />';
             echo '<em>cn =</em> ' . $entry['cn'][0] . '<br />';
             echo '<em>sn =</em> ' . $entry['sn'][0] . '<br />';
         endif;
      endforeach;
      $time_end = microtime(true);
      $time = $time_end - $time_start;
      echo 'Executé en ' . $time . ' secondes<br /><br />';
  }
  
  public function mass_add($conn, $nb)
  {
      echo '<b>Création de ' . $nb . ' entrées</b><br />';
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
      echo 'Executé en ' . $time . ' secondes<br /><br />';    
  }
  
  public function mass_modify($conn, $nb)
  {
      echo '<b>Modification des ' . $nb . ' entrées</b><br />';
      $time_start = microtime(true); 
      for ( $i=1 ; $i <=$nb ; $i++ ) 
      {
          $modify_entry                 = array();
          $modify_entry['SN']           = 'Stress';
          $modify_entry['objectClass'][0]  = 'person';
          $modify_entry['objectClass'][1]  = 'top';
          $rdn = "CN=Test " . $i . ",OU=people,DC=example,DC=com";

          ldap_modify($conn, $rdn, $modify_entry);
      }
      $time_end = microtime(true);
      $time = $time_end - $time_start;
      echo 'Executé en ' . $time . ' secondes<br /><br />';    
  }
  
  public function mass_delete($conn, $nb)
  {
      echo '<b>Suppression des ' . $nb . ' entrées</b><br />';
      $time_start = microtime(true); 
      for ( $i=1 ; $i <=$nb ; $i++ ) 
      {
          $new_entry = 'Test ' . $i;
          $rdn = "CN=" . $new_entry . ",OU=people,DC=example,DC=com";
          ldap_delete($conn, $rdn);
      }
      $time_end = microtime(true);
      $time = $time_end - $time_start;
      echo 'Executé en ' . $time . ' secondes<br /><br />';
  }
  
  public function close($conn)
  {
      echo '<b>Fermeture de la connexion</b>';
      ldap_close($conn); 
      echo ' - OK<br /><br />';
  }
  
  public function connect()
  {
      echo '<b>Connexion au serveur</b>';
      //$conn = ldap_connect('example.com');
      $conn = ldap_connect('ldap://localhost:389');
      echo ' - OK<br /><br />';
      
      return $conn;
  }
  
  public function set_protocol($conn)
  {
      echo '<b>Paramètrage de la version</b>';
      ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
      echo ' - OK<br /><br />';
  }
  
  public function bind($conn)
  {
      echo '<b>Bind</b>';
      ldap_bind($conn, 'cn=admin,dc=example,dc=com', 'admin');
      echo ' - OK<br /><br />';
  }
  
  public function add($conn)
  {
      echo '<b>Création d\'une entrée CN = Lorraine Soudade</b><br />';
      // Prépare les données
      $new_entry                 = array();
      $new_entry['CN']           = 'Lorraine Soudade';
      $new_entry['SN']           = 'Soudade';
      $new_entry['objectClass'][0]  = 'person';
      $new_entry['objectClass'][1]  = 'top';
      $rdn = "CN=".$new_entry ["CN"].",OU=people,DC=example,DC=com";
      
      // Ajoute les données au dossier
      $time_start = microtime(true);
      
      ldap_add($conn, $rdn, $new_entry);
      
      $time_end = microtime(true);
      $time = $time_end - $time_start;
      echo 'Executé en ' . $time . ' secondes<br /><br />';
      
      return $rdn;
  }
  
  public function modify($conn, $rdn)
  {
      echo '<b>Modification de l\'entrée SN = Soudade => SN = Test</b><br />';
      // Prépare les données
      $modify_entry                 = array();
      $modify_entry['SN']           = 'Test';
      $modify_entry['objectClass'][0]  = 'person';
      $modify_entry['objectClass'][1]  = 'top';
      $rdn = "CN=Lorraine Soudade,OU=people,DC=example,DC=com";
      
      // Ajoute les données au dossier
      $time_start = microtime(true);
      ldap_modify($conn, $rdn, $modify_entry);
      
      $time_end = microtime(true);
      $time = $time_end - $time_start;
      echo 'Executé en ' . $time . ' secondes<br /><br />';
  }
  
  public function delete($conn, $rdn)
  {
      echo '<b>Suppression de lorraine</b><br />';
      $time_start = microtime(true);
      ldap_delete($conn, $rdn);
      $time_end = microtime(true);
      $time = $time_end - $time_start;
      echo 'Executé en ' . $time . ' secondes<br /><br />';
  }
}
