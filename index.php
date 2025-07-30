<html>
<head>
<title>Sanoma MyPlace eBook Ripper</title>
<script type="text/javascript" src="./jquery/jquery-3.7.0.js"></script>

<script>
// Esegue al caricamento della pagina (onload)
$(document).ready(function(){
   // NULLA
});
</script>

<script language="javascript">

function controlla()
{
   var base_url=$("#base_url").val();
   var product_path=$("#product_path").val();
   var username=$("#username").val();
   var password=$("#password").val();
   var cookie=$("#cookie").val();
   var start_page=$("#start_page").val();
   var end_page=$("#end_page").val();
   //var nome_cartella=$("#nome_cartella").val();
   if($.trim(base_url)==""){
      alert("BASE URL NON VALIDO!");
      $("#base_url").val('');
      $("#base_url").focus();
   }
   else if($.trim(product_path)==""){
      alert("PRODUCT PATH NON VALIDO!");
      $("#product_path").val('');
      $("#product_path").focus();
   }
   else if($.trim(username)==""){
      alert("USERNAME NON VALIDO!");
      $("#username").val('');
      $("#username").focus();
   }
   else if($.trim(password)==""){
      alert("PASSWORD NON VALIDA!");
      $("#password").val('');
      $("#password").focus();
   }
   else if($.trim(cookie)==""){
      alert("COOKIE NON VALIDO!");
      $("#cookie").val('');
      $("#cookie").focus();
   }
   else if(($.trim(start_page)=="") || (isNaN(parseInt(start_page)))){
      alert("PAGINA INIZIALE NON VALIDA!");
      $("#start_page").val('');
      $("#start_page").focus();
   }
   else if(($.trim(end_page)=="") || (isNaN(parseInt(end_page)))){
      alert("PAGINA FINALE NON VALIDA!");
      $("#end_page").val('');
      $("#end_page").focus();
   }
   else if(parseInt(end_page)<parseInt(start_page)){
      alert("LA PAGINA FINALE NON PUO' ESSERE INFERIORE ALLA PAGINA INIZIALE!");
      $("#end_page").val('');
      $("#end_page").focus();
   }
   else{
      document.ripper.submit();
   }
}

</script>

<style type="text/css">
.auto-style1 {
	text-align: center;
}
</style>

</head>
<body bgcolor="#91d8e3">
<center>
<br>
<h1>Sanoma My Place eBook Ripper</h1>
<img border="0" src="imgs/0a633855c250798d3ab1b2dbfe28f18e-historieta-divertida-profesi-oacute-n-de-ladr-oacute-n-by-vexels.png" height="200">
<img border="0" src="imgs/Thief-Robber-PNG-Transparent-Image.png" height="200">
<img border="0" src="imgs/Thief-No-Background.png" height="200">
<img height="82" src="imgs/dog_02.png" width="160"><br><br>
<form method="POST" id="ripper" name="ripper" action="download.php">
<br><br>
<table>
<tr>
<td class="auto-style1" colspan="3"><strong>Dati da inserire</strong></td>
</tr>
<tr>
<td>Base URL:</td>
<td>&nbsp;</td>
<td><input type="text" id="base_url" name="base_url" size="60" placeholder="https://npmitaly-pro-apidistribucion.sanoma.it"></td>
</tr>
<tr>
<td>Product path:</td>
<td>&nbsp;</td>
<td><input type="text" id="product_path" name="product_path" size="60" placeholder="/product/1126602/54001/ONLINE/assets/book/pages/"></td>
</tr>
<tr>
<td>Sanoma MyPlace Username:</td>
<td>&nbsp;</td>
<td><input type="text" id="username" name="username" size="30"></td>
</tr>
<tr>
<td>Sanoma MyPlace Password:</td>
<td>&nbsp;</td>
<td><input type="password" id="password" name="password" size="30"></td>
</tr>
<tr>
<td>Sanoma MyPlace Session Cookie:</td>
<td>&nbsp;</td>
<td><textarea id="cookie" name="cookie" rows="4" cols="50"></textarea></td>
</tr>
<tr>
<td>Pagina iniziale:</td>
<td>&nbsp;</td>
<td><input type="text" id="start_page" name="start_page" size="10"></td>
</tr>
<tr>
<td>Pagina finale:</td>
<td>&nbsp;</td>
<td><input type="text" id="end_page" name="end_page" size="10"></td>
</tr>
<tr>
<td class="auto-style1" colspan="3"><br><input type="button" id="Invia" name="Invia" value="  Procedi  " OnClick="javascript:controlla()"></td>
</tr>
</table><br><br>
</form>
</center>
</body>
</html>