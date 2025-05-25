<?php    
 include_once('fix_mysql.inc.php');         
define('DBHOST', 'localhost');                    # databazovy server
define('DBNAME', 'clanky-bezky');                        # nazev databaze
define('DBUSER', 'bezky');                         # uzivatelske jmeno
define('DBPASS', 'fatra');                             # heslo


define('MYIP', '127.0.0.1');                      # vase IP adresa, pro odliseni komentaru (jmeno bude jinou barvou)
define('P', 10);                                  # po kolika budeme strankovat (reakce nejsou zapocitany) 
define('AP', 15);                                 # po kolika budeme strankovat v administraci

define('URL', 'https://www.kolo-bezky.cz/akce/kniha/guestbook.php');  # absolutni adresa pro RSS

$pass = 'vrsateca';                                    # heslo do administrace
$blacklist = array(                               # IP adresy, z nichz nebude povoleno zanechavat vzkazy
    '127.0.0.2',
    '127.0.0.3',
    );
$spamwords = 'anatrim website [url] [/url]';      # dalsi ochrana proti spamu - pokud bude v poli zprava nektere ze zadanych slov, prispevek se neodesle. (piste malymi pismeny)

function my_mysql_error() {                       # vlastni funkce pro vypis chyby, kdyz se nepovede pripojit k databazi
return $error = '<!DOCTYPE html SYSTEM> 
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" type="text/css" media="screen" href="./files/style.css">
  <title>Chyba spojení s databází</title>
</head>
<body id="error">
  <h2>Chyba</h2>
  <p><strong>Nepodařilo se navázat spojení s databází.</strong></p>
  <p>Možné příčiny mohou být <em>chybné údaje</em> v <em>konfiguračním souboru</em>, nebo <em>výpadek databázového serveru</em>.</p>
  <p>Vzniklá chyba : <strong style="color:red">'.mysql_error().'</strong> </p>
</body>
</html>';
}

@mysql_connect(DBHOST, DBUSER, DBPASS) or die ( my_mysql_error() );
mysql_select_db(DBNAME);
//mysql_query("SET NAMES UTF-8");
mysql_query("SET NAMES cp1250");

?>