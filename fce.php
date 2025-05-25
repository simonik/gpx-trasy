<?
define('m',"&nbsp;");
define('r', "<BR>");
include_once('fix_mysql.inc.php');
function spojeni()
{
  if (!@$spojeni = MySQL_Connect("localhost", "root", "baraba")){
		
	if (!@$spojeni = MySQL_Connect("localhost", "clanky-bezky", "fatra")){
    	  echo "Nepodaøilo se spojit s databází. Chvíli vyèkejte a zkuste to znovu.<br>Pokud ani pøi opakovaném pokusu neuspìjete, kontaktujte webmastera.";
		    die;
    }
	}
	
  if (!@$select = MySQL_Select_Db("clanky-bezky")){
		echo "Chyba pøi výbìru databáze! Chvíli vyèkejte a zkuste to znovu.<br>Pokud ani pøi opakovaném pokusu neuspìjete, kontaktujte webmastera.";
		die;
}
MYSQL_QUERY(" SET NAMES 'cp1250'");
}

?>
