<?
define('m',"&nbsp;");
define('r', "<BR>");
include_once('fix_mysql.inc.php');
function spojeni()
{
  if (!@$spojeni = MySQL_Connect("localhost", "root", "baraba")){
		
	if (!@$spojeni = MySQL_Connect("localhost", "clanky-bezky", "fatra")){
    	  echo "Nepoda�ilo se spojit s datab�z�. Chv�li vy�kejte a zkuste to znovu.<br>Pokud ani p�i opakovan�m pokusu neusp�jete, kontaktujte webmastera.";
		    die;
    }
	}
	
  if (!@$select = MySQL_Select_Db("clanky-bezky")){
		echo "Chyba p�i v�b�ru datab�ze! Chv�li vy�kejte a zkuste to znovu.<br>Pokud ani p�i opakovan�m pokusu neusp�jete, kontaktujte webmastera.";
		die;
}
MYSQL_QUERY(" SET NAMES 'cp1250'");
}

?>
