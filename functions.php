<?php header('Content-Type: text/html; charset=windows-1250');

error_reporting(0);
//require_once dirname(__FILE__).'/fce.php';
$vlozeno=0;

function prevod($cast1,$cast2,$cast3,$cast8){
	$cast3_o = ereg_replace('[^a-zA-Z0-9\/+_-]*$','',$cast3);
	if(strlen($cast3_o) < strlen($cast3)){
		$zbytek = substr($cast3,strlen($cast3_o));
		$cast3 = $cast3_o;
		unset($cast3_o);
	}
	$ret = '<a href="';
	if($cast1 == 'www.'){
		$ret .= 'http://';
	} else {
		$ret .= $cast1;
	}
	if($cast8 != ''){
		$ret .= 'mailto:'.str_replace('@',' [zavinac] ',$cast8);
		$title = str_replace('@',' [zavinac] ',$cast8);
	} else {
		$ret .= $cast2.$cast3;
		$title = $cast2.$cast3;
	}
	if(strlen($cast2.$cast3.$cast8) > 23){
		$text_odkazu = substr($cast2.$cast3.$cast8,0,20).'...';
	} else {
		$text_odkazu = $cast2.$cast3.$cast8;
	}
	$ret .= '" title="'.$title.'">'.str_replace('@',' [zavinac] ',$text_odkazu).'</a>'.$zbytek;
	return $ret;
}


function preved($text) {
    // Debugging: vypiš pùvodní text
   // Check current encoding using different methods
   
    echo  $text;
    
    // Pøevod formátovacích znaèek
    $text = preg_replace("'\[b\]([^\[\]<>]+)\[\/b\]'si", "<strong>$1</strong>", $text);
    $text = preg_replace("'\[i\]([^\[\]<>]+)\[\/i\]'si", "<cite>$1</cite>", $text);
    $text = preg_replace("'\[u\]([^\[\]<>]+)\[\/u\]'si", "<ins>$1</ins>", $text);

    // Debugging: vypiš text po pøevodu formátovacích znaèek
  

    // Pøevod odkazù
    $text = preg_replace_callback('#(https?://|ftp://|(www\.))([\w\-]*\.[\w\-\.]*([/?][^\s]*)?([ \n\r\t\.,/\(\):;\-\\\/\?!\"\\\'\[\]\{\}=]*))|((mailto:)?([^\s]+@[\w\-]+\.[\w]+))#', function ($matches) {
        $prefix = isset($matches[1]) ? $matches[1] : '';
        $url = isset($matches[3]) ? $matches[3] : '';
        $path = isset($matches[4]) ? $matches[4] : '';
        $email = isset($matches[8]) ? $matches[8] : '';
        return prevod2($prefix, $url, $path, $email);
    }, $text);

    // Debugging: vypiš text po pøevodu odkazù
  

    // Nahrazení nových øádkù a ampersandù
    $text = str_replace(array("\n", "&"), array("<br />", "&amp;"), $text);

    // Debugging: vypiš text po nahrazení nových øádkù a ampersandù
  

    // Pøevod smajlíkù
    //$text = preg_replace("(\*\*)+([0-9]{1,2})", "<img src='akce/kniha/files/images/x$1.gif' width='15' height='15' alt='$1' />", $text);
    $text = preg_replace("/(\*\*)+([0-9]{1,2})/", "<img src='akce/kniha/files/images/x$2.gif' width='15' height='15' alt='$2' />", $text);
    // Debugging: vypiš text po pøevodu smajlíkù
  

    // Povolení nìkterých entit
    $text = preg_replace("/&amp;(lt|gt|amp|quot);/", "&$1;", $text);

    // Debugging: vypiš koneèný text
 

    return $text;
}

function prevod2($prefix, $url, $path, $email) {
    if (!empty($email)) {
        return '<a href="mailto:' . htmlspecialchars($email) . '">' . htmlspecialchars($email) . '</a>';
    }

    $fullUrl = ($prefix === 'www.') ? 'http://' . $url . $path : $prefix . $url . $path;
    $displayUrl = (strlen($url . $path) > 23) ? substr($url . $path, 0, 20) . '...' : $url . $path;

    return '<a rel="nofollow" href="' . htmlspecialchars($fullUrl) . '">' . htmlspecialchars($displayUrl) . '</a>';
}

function preved_web($web) {
    // Kontrola, zda $web odpovídá formátu www.nìco.nìco (napø. www.example.com)
    if (preg_match("/^(www\..+\..{2,3})$/i", $web)) {
        // Nahrazení 'www.' s 'http://'
        return preg_replace("/^(www\..+\..{2,3})$/i", "http://$1", $web);
    } else {
        return $web;
    }
}

function klikaci_jmeno($jmeno, $web, $ip) { # pokud byl vyplnen web, jmeno bude klikaci. u administratora bude zobrazeno cervenou barvou
    $ip == MYIP ? $trida = "red" : $trida = "other";
    return empty( $web ) ? $k_jmeno = "<cite><strong class='$trida'>$jmeno</strong></cite>" : $k_jmeno = "<cite><strong><a class='$trida' href='".preved_web($web)."'>$jmeno</a></strong></cite>";     
}

function ukaz_mail($mail) { # email prevedeme do antispam podoby, a vytiskneme pouze pokud je uveden.
    $mail = str_replace(array("@","."), array("(uzenáè)","(teèka)"), $mail);
    return empty( $mail ) ? '' : " <small><a href='mailto:".$mail."'>[mail]</a></small>";
}

function uloz_prispevek($blacklist, $spamwords) { # odesilame prispevek
    global $message;
    
		if ( isset($_POST['submit']) ) {
		
		  $_POST['jmeno'] = htmlspecialchars($_POST['jmeno']); # osetrime nebezpecne znaky
      $sql=mysql_query("Select * from uzivatele where jmeno='".$_POST["jmeno"]."'");		
      $vysledek=mysql_fetch_array($sql);		
      if (!(($_POST["heslo"]==$vysledek["heslo"] && ($_POST["heslo"]<>"")) || $_SESSION["prihlasen"])) { // jestliže jsme pøihlášení
      
			?>
			<script type="text/javascript">
     <!--
     alert("Špatné jméno anebo heslo");
   //-->
   </script>
			<?
			 return;
			}

    if ($_POST['jmeno'] == "") {  // jestliže jsme pøihlášeni, tak se ve formuláøi pro komentáø nwezobrazuje jméno a proto ho doplníme
    $_POST['jmeno']=$_SESSION["jmeno"];
     }
     
        $_POST['zprava'] = ($_POST['zprava']);
        $_POST['web'] = ($_POST['web']);
        $_POST['email'] = ($_POST['email']);
       
			  $_POST['jmeno'] = trim($_POST['jmeno']); # smazeme bile znaky ze zacatku a konce retezce
        $_POST['zprava'] = trim($_POST['zprava']);
        $_POST['web'] = trim($_POST['web']);
        $_POST['email'] = trim($_POST['email']);
        
				$_POST['zprava'] = mb_substr($_POST['zprava'], 0, 1500, 'UTF-8'); # bereme pouze prvnich 1500 znaku
              
        if ($_POST['icq'] != "2")  # antispam policko, kontroluje se jako prvni
            return $message = "Buïto jste spam, nebo se nauète sèítat.";
            
        if (!empty($_POST['$homepage'])) # dalsi antispam policko, musi zustat prazdne
            return $message = "Poslední políèko prosím nevyplòujte."; 
            
        if (strpos($_POST['email'], "@")) # opet antispam, v poli email (ve skutecnosti web) nesmi byt zavinac
            return $message = "Jste spam. Prosím, nebuïte spam. Dìkuji.";     
               
        if (empty($_POST['jmeno']) or empty($_POST['zprava'])) # jmeno a text jsou povinne
            return $message = "Nezadali jste jméno, nebo text zprávy.";
  
        if (in_array($_SERVER['REMOTE_ADDR'], $blacklist, true) ) # nema nahodou uzivatel ban?
            return $message = "Je mi líto, ale zasílání pøíspìvkù z Vaší IP adresy bylo zakázáno.";         
  
        if (substr_count($_POST['zprava'], 'http://') > 10) # pri vice jak 10ti odkazech komentar nepovolime
            return $message = "Bakanej spam, bakanej. Ale na mì si nepøijdeš :)"; 

        if (substr_count($_POST['zprava'], '**') > 10) # vice jak 10 smajlu nepovolime
            return $message = "Maximum je 10 smajlù."; 

        if ($_POST['email'] == 'http://' or $_POST['email'] == 'http:/')
            $_POST['email'] = "";

        $zprava_array = explode(" ", $_POST['zprava']); # rozdelime retezec na jednotliva slova
        $n = count($zprava_array); # zjistime pocet slov
        $zprava_return = ""; # inicializace vystupni promenne
        
        $spamwords_array = explode(" ", $spamwords); # spamwords ulozime do pole
        
       //* $datum = Date("d. " . "+m+ " . "Y, " . "H:i" ); # datum bude mit pekny format
      //  $datum = strtr($datum, array('+01+' => 'Ledna', '+02+' => 'Února', '+03+' => 'Bøezna', '+04+' => 'Dubna', '+05+' => 'Kvìtna', '+06+' => 'Èervna', '+07+' => 'Èervence', '+08+' => 'Srpna', '+09+' => 'Záøí', '+10+' => 'Øíjna', '+11+' => 'Listopadu', '+12+' => 'Prosince'));
       $datum = Date("Y-m-d H:i" ); 
        if (isset($_POST['reaguj'])) { # je prispevek reakci?
            $kontrola = mysql_query("SELECT `id` FROM `knizka_2` WHERE `id` = ".intval($_POST['reaguj'])." AND `r`=0"); # reagujeme na skutecny prispevek?
            mysql_num_rows($kontrola)>0 ? $reaguj = $_POST['reaguj'] : $reaguj = 0;
        } else {
            $reaguj = 0;
        }

        for ($i = 0; $i <= $n-1; $i++) { # zkontrolujeme kazde slovo
            $zprava_array_lower = strtolower($zprava_array[$i]);
            if ( in_array($zprava_array_lower, $spamwords_array, true) ) # zkontrolujeme spamwords
                return $message = "Komentáø nebyl uložen. Použili jste nìkteré z nepovolených slov: $spamwords";
            //$zprava_array[$i] = wordwrap($zprava_array[$i], 50, "-", 1); # slova delsi nez 40 znaku rozdelime
            $zprava_return .= $zprava_array[$i] . " ";
        } 
     
$ukazat=0;        
$ip=$_SERVER['REMOTE_ADDR'];
spojeni();
$sql=mysql_query("Select * from zakazane_ip WHERE IP='$ip'");		
if (!$sql) die("Chyba pøi ètení dat z databáze podmínka IP,kuki. Kontaktujte prosím webmastera ".mysql_error());
if (!mysql_num_rows($sql)) {



if ($_POST['jmeno']=="host") {
      $ukazat=0;	
   	
}
else
 {
$ukazat=1;
 }
        mysql_query("INSERT into knizka_2 (ukazat,jmeno,text,datum,email,web,addr,r) VALUES('$ukazat','{$_POST['jmeno']}', '$zprava_return', '$datum', '{$_POST['web']}', '{$_POST['email']}', '{$_SERVER['REMOTE_ADDR']}', '$reaguj')"); # ulozime do databaze
        global $vlozeno;
        $vlozeno=1;

  if ($_POST['jmeno']=="host") {

	  $a="SELECT MAX(id) as maxid from knizka_2";
       $sql=MySQL_Query($a);
  if (!$sql){
	 echo "Chyba pøi ètení max id".mysql_error();
	 die;	
   }
    $zaznam=mysql_fetch_array($sql);
   		$predmet="Host vlozil prispevek do kniky navstev";
			$hlavicka="From: test@test.cz\nX-Mailer: TEST\nContent-Type: text/html";
			$headers .= "From: kolo-bezky<admin@kolo-bezky.cz>\n"; 
			$headers .= "X-Sender: <admin@kolo-bezky.cz>\n"; 
			$headers .= "X-Mailer: PHP\n"; 
			$headers .= "X-Priority: 1\n"; 
			$headers .= "Return-Path: <admin@kolo-bezky.cz>\n"; 
			$headers .= "Content-Type: text/html; charset=windows-1250\n"; 
      $zprava=$zprava_return;
		  $zprava=$zprava.'<a href="https://www.kolo-bezky.cz/akce/host.php?volba=kniha&id='.$zaznam["maxid"].'"> Povol zobrazit prispevek v knize</a>';
      
      mail("simonik@seznam.cz","$predmet","$zprava","$headers");
}




}        


       
       // header("Location: ./guestbook.php"); # obnovime stranku, osetreni pred duplicitnim odeslanim prispevku
    }
    return true;
}

function reakce($id, $jmeno, $text, $datum, $email, $web, $addr) {
?>
   <div class="prispevek reakce" id="prispevek-<?php echo $id; ?>">
    <p class="horni"><small class='tajm'>
     
      	<a class="black" href="akce/kniha/admin/index2.php?idd=<? echo $id ?>&jmeno=<? echo $jmeno ?>">Editace</a> 
       	<a class="black" href="akce/kniha/admin/index2.php?del=<? echo $id ?>&jmeno=<? echo $jmeno ?>">Smaž</a> 
    <?php echo $datum; ?></small> <?php echo klikaci_jmeno($jmeno, $web, $addr) . " <small>reaguje</small>: " . ukaz_mail($email) ?></p>
    <blockquote cite="<?php echo $jmeno; ?>">
   
     <p><?php echo preved($text); ?></p>
    </blockquote>
   </div>
<?php
}

function zobraz_prispevek($id, $jmeno, $text, $datum, $email, $web, $addr) {
      
   
    $reakce = mysql_query("SELECT * FROM `knizka_2` WHERE (`r`=$id AND ukazat=1)");
?>
   <li class="prispevek" id="prispevek-<?php echo $id; ?>">
   <p class="horni"><small class='tajm'><a class="black" href="?lm=kniha&reaguj=<?php echo $id ?>">reaguj</a> 
  
    
      	<a class="black" href="akce/kniha/admin/index2.php?idd=<? echo $id ?>&jmeno=<? echo $jmeno ?>">Editace</a> 
       <a class="black" href="akce/kniha/admin/index2.php?del=<? echo $id ?>&jmeno=<? echo $jmeno ?>">Smaž</a>
     <?php echo $datum; ?></small>
      <?php echo "".klikaci_jmeno($jmeno, $web, $addr) . " <small>píše</small>: " . ukaz_mail($email) ?></p>
    <blockquote cite="<?php echo $jmeno; ?>">
     <p><?php preved($text); ?></p>
    </blockquote>
<?php 
    while ($vyber = mysql_fetch_array($reakce)) {

        reakce($vyber['id'], $vyber['jmeno'], $vyber['text'], $vyber['datum'], $vyber['email'], $vyber['web'], $vyber['addr']);
    }
    echo "  </li>\n";
}

function strankovani($pocet) {
    $smitec = ceil($pocet/P); # delime beze zbytku
    for ($i = 1; $i <= $smitec; $i++) {
        if (($i*P)<$pocet) {
            $o = ($i*P);
            $s = " | ";
        } else {
            $o = $pocet;
            $s = "";
        }
        $i == 1 ? $link = "" : $link = "&start=".($i*P-P);
        echo "<a href='index.php?lm=kniha$link'>".($i*P-(P-1))."-$o</a>$s";
    }
}
?>