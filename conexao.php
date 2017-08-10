<meta  http-equiv = "Content-Type" content = "text/html"; charset = "UTF-8" />  
<?php
	$hostname =  "localhost";
	$usuario = "root"; 
	$senha = "";
	$dbname = "controle_atividades";
	$conexao = @mysql_connect($hostname, $usuario, $senha) or die("Não foi possível conectar com o servidor de dados");
	mysql_select_db($dbname, $conexao) or die("Banco de dados não localizado");
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
?>