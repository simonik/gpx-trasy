<?php
error_reporting(0);
require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/functions.php';

uloz_prispevek($blacklist, $spamwords);

if( !isset($_GET['start']) )
    $_GET['start'] = 0;

$pok=  "SELECT * FROM `knizka_2` WHERE r = 0 AND ukazat = 1 order by `id` DESC limit ".intval($_GET['start']).", ".intval(P)."";
$vypis = mysql_query($pok);


//$pocet = mysql_num_rows(mysql_query('SELECT `id` from `knizka_2` WHERE `r` = 0'));
$pocet = mysql_num_rows(mysql_query('SELECT `id` from `knizka_2` where r=0'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs"> 
<head>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
  <title>Kniha n�v�t�v</title>
  <link rel="stylesheet" type="text/css" href="akce/kniha/files/style.css" media="screen" />
  <link rel="alternate" type="application/rss+xml" href="./rss-guestbook.php" title="RSS N�v�t�vn� knihy" />
  <script type="text/javascript">
    function ct(text){ 
      if (p=document.getElementById('text')){
        p.focus(); 
        p.value+=" "+text+" ";
      }
    }
  </script>
  
</head>
<body>

<div id="hlavni">

  <h2>Kniha n�v�t�v</h2>
  Tato n�v�t�vn� kniha je p��stupn� pouze pro zaregistrovan� u�ivatele. <br>
  Pro registracei klikn�te na n�sleduj�c� odkaz <a href="/index.php?lm=registrace" target="_blank">Registrace</a><br>
  <p><font color="#FF0000">Pro anonymn� hosty jsou z��zeny p��stupov� �daje. Jmeno :<font color="green"> host</font>  Heslo: <font color="green"> host</font> </font></p>
  <p><font color="#FF0000">P��SP�VEK <b>HOSTA</b> BUDE ZOBRAZEN A� PO SCHV�LEN�.</font></p>
  V p��pad� pot�� n�s kontaktujte na e-mail: kolo-bezky@kolo-bezky.cz anebo na admin@kolo-bezky.cz<br><br>
 
 Pros�m pi�te jenom p��sp�vky, kter� se t�kaj� zam��en� tohoto webu.
  <br /><br />
  <form action="index.php?lm=kniha" method="post">   
    <fieldset id="guestbook">
      <legend><strong><?php if(isset($message)) { echo "<span class='red'>$message</span>"; } else { ?> P�idat p��sp�vek <?php } ?></strong></legend>
<?php if (isset($_GET['reaguj'])) { ?>
      <p><b>&bull; Regujete na p��sp�vek ��slo <span class="red"><?php echo intval($_GET['reaguj']) ?></span> :</b></p>
<?php } ?> 
      <p>
<?
      if (!$_SESSION["prihlasen"]) {
?>
			  <input tabindex="1" name="jmeno" id="jmeno" type="text" value="<?php if (isset($_POST['jmeno'])) { echo $_POST['jmeno']; } ?>" class="inputbook" size="30" maxlength="60" />&nbsp;
        <label for="jmeno"> Jm�no<span class="red">*</span> </label>
      </p>
      <p>
        <input tabindex="2" name="heslo" id="heslo" type="text" value="" class="inputbook" size="30" maxlength="60" />&nbsp;
        <label for="heslo"> Heslo<span class="red">*</span> </label>
      </p>
<? }?>
      <p>
        <input tabindex="2" name="web" id="web" type="text" value="<?php if (isset($_POST['web'])) { echo $_POST['web']; } ?>" class="inputbook" size="30" maxlength="60" />&nbsp;
        <label for="web"> E-mail <small>(nen� povinn�)</small> </label>
      </p>
      <p>
        <input tabindex="3" id="email" name="email" type="text" value="<?php if (isset($_POST['email'])) { echo $_POST['email']; } else { echo "http://"; } ?>" class="inputbook" size="30" maxlength="60" />&nbsp;
        <label for="email"> Web <small>(nen� povinn�)</small></label>
      </p>
      <p>
        <textarea tabindex="4" id="text" name="zprava" cols="70" rows="8"><?php if (isset($_POST['zprava'])) { echo $_POST['zprava']; } ?></textarea>
      </p>
      <p>
        <input  tabindex="5" id="icq" name="icq" type="text" class="inputbook" value="<?php if (isset($_POST['icq'])) { echo $_POST['icq']; } ?>" />&nbsp;
        <label for="icq"> Kolik je jedna plus jedna<span class="red">*</span> <small>(antispam)</small></label>
      </p>
      <p class="homepage">
        <input id="homepage" name="homepage" type="text" />&nbsp;
        <label for="homepage">Toto pol��ko pros�m ponechte pr�zdn� <small>(antispam)</small></label>
      </p>
      <p>
        <span class="formatovani"><a title="tu�n� text" href="javascript:ct('[b][/b]');">b</a> <a title="kurz�va" href="javascript:ct('[i][/i]');">i</a> <a title="podtr�eno" href="javascript:ct('[u][/u]');">u</a></span>
        <a href="javascript:ct('**1');"><img class="smajl" src="akce/kniha/files/images/x1.gif" alt=":)" /></a>
        <a href="javascript:ct('**2');"><img class="smajl" src="akce/kniha/files/images/x2.gif" alt=":D" /></a>
        <a href="javascript:ct('**3');"><img class="smajl" src="akce/kniha/files/images/x3.gif" alt=":(" /></a>
        <a href="javascript:ct('**4');"><img class="smajl" src="akce/kniha/files/images/x4.gif" alt=":o)" /></a>
        <a href="javascript:ct('**5');"><img class="smajl" src="akce/kniha/files/images/x5.gif" alt=";)" /></a>
        <a href="javascript:ct('**6');"><img class="smajl" src="akce/kniha/files/images/x6.gif" alt=":'(" /></a>
        <a href="javascript:ct('**7');"><img class="smajl" src="akce/kniha/files/images/x7.gif" alt=">:)" /></a>
        <a href="javascript:ct('**8');"><img class="smajl" src="akce/kniha/files/images/x8.gif" alt=":B-)" /></a>
        <a href="javascript:ct('**9');"><img class="smajl" src="akce/kniha/files/images/x9.gif" alt="8-)" /></a>
        <a href="javascript:ct('**10');"><img class="smajl" src="akce/kniha/files/images/x10.gif" alt=":-{" /></a>
        <a href="javascript:ct('**11');"><img class="smajl" src="akce/kniha/files/images/x11.gif" alt="=)" /></a>
        <a href="javascript:ct('**12');"><img class="smajl" src="akce/kniha/files/images/x12.gif" alt=">:(" /></a>
        <a href="javascript:ct('**13');"><img class="smajl" src="akce/kniha/files/images/x13.gif" alt="?" /></a>
        <a href="javascript:ct('**14');"><img class="smajl" src="akce/kniha/files/images/x14.gif" alt="!" /></a>
        <a href="javascript:ct('**15');"><img class="smajl" src="akce/kniha/files/images/x15.gif" alt="{}" /></a>     
      </p>
      <p class="cleaner">
        <input type="hidden" name="reaguj" value="<?echo intval($_GET['reaguj'])?> ">
				<input tabindex="6" type="submit" class="submitbutton" name="submit" id="submit" value=" &nbsp; odeslat &nbsp; " />  
        <input tabindex="7" type="reset" class="submitbutton" value=" &nbsp; vymazat &nbsp; " />
      </p>
    </fieldset>
  </form>

  <ol id="prispevky">

<?php 

while($row = mysql_fetch_array($vypis)) {
             zobraz_prispevek($row['id'], $row['jmeno'],$row['text'], $row['datum'], $row['email'], $row['web'], $row['addr']);
} 
?>

  </ol><!-- prispevky -->

  <p class='strankovani'>
    <strong>Str�nkov�n�:</strong> <br /><?php strankovani($pocet) ?>
  </p>

</div><!--hlavni-->

<!-- vytvoril Mike: http://mike.webzdarma.cz -->


<?  
if ($vlozeno ==1) { //jestli�e byl p��sp�vek vlo�en do datab�ze, tak vyma�eme formul��
?>  

 <script>

  $(document).ready(function(){
if(($("#jmeno").val()=='host'))
{
  alert("P��sp�vky hosta jsou zobrazeny a� po schv�len�.");
 }
 	$("#text").val("");
  $("#jmeno").val("");
  $("#icq").val("");  
    
});

    
   </script>
<?
}
echo $�vlozeno;
?>  
  

</body>
</html>
<?php mysql_close(); ?>