<?php 
require "conexao.php";
session_start();
$consulta_atividades = mysql_query("SELECT * FROM atividades");
if(isset($_SESSION['status'])){
	unset($_SESSION['status']);
}
if(isset($_SESSION['situacao'])){
	unset($_SESSION['situacao']);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Controle de atividades</title>
	<meta charset="utf-8">
</head>
<body>
	<form method="post" action="?acao=cadastrar">
		<table>
			<tr>
				<td style="text-align: center" colspan="8"> <h3>Incluir nova atividade</h3></td>
			</tr>
			<tr>
				<td style="float: right;">Nome:</td>
				<td><input type="text" name="nome_atv" maxlength="255" required></td>
			</tr>
			<tr>
				<td style="float: right;">Descrição:</td>
				<td><input type="text" name="descricao_atv" maxlength="600" required></td>
			</tr>
			<tr>
				<td style="float: right;">Data Início:</td>
				<td><input type="date" name="data_inicio" required></td>
			</tr>
			<tr>
				<td style="float: right;"> Data Final: </td>
				<td><input type="date" name="data_final"></td>
			</tr>
			<tr>
				<td style="float: right;"> Status:</td>
				<td>
					<select name="status">
						<?php
						$consulta_status = mysql_query("SELECT * FROM status ");
						while($array_status = mysql_fetch_array($consulta_status)){
							?>
							<option value="<?php echo $array_status['idstatus'];?>"><?php echo $array_status['descricao']; ?></option>
							<?php
						}
						?>	
					</select>
				</td>
			</tr>
			<tr>
				<td style="float: right;">Situação:
				<td>
					<select name="situacao">
						<option value="A">Ativo</option>		
						<option value="I">Inativo</option>		
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" value="Cadastrar" >
	</form>
</body>
</html>
<?php 
if(@$_GET['acao'] == 'cadastrar'){
	$nome_atv = $_POST['nome_atv'];
	$nome_atv = $_POST['nome_atv'];
	$descricao_atv = $_POST['descricao_atv'];
	$data_inicio = date('Y/m/d', strtotime($_POST['data_inicio']));
	if($_POST['data_final'] != ''){
		$data_final = date('Y/m/d', strtotime($_POST['data_final']));	
	}else{
		$data_final = '';
	}

	$status = $_POST['status'];
	$situacao = $_POST['situacao'];

	$consulta_concluido = mysql_query("SELECT * FROM status WHERE descricao = 'Concluído'");
	$array_concluido = mysql_fetch_array($consulta_concluido);
	if($array_concluido['idstatus'] == $status && $data_final == ''){
		echo "<script>alert('É necessário inserir uma data final quando a atividade é concluída!'); history.back();</script>";	
		exit();
	}
	if($data_final != ''){
		if(strtotime($data_inicio) > strtotime($data_final)){
			echo "<script>alert('A data final não pode ser maior que a data inicial!'); history.back();</script>";	
			exit();
		}
	}
	$insere_atividade = mysql_query(" INSERT INTO atividades (nome, descricao, datainicio, datafinal, idstatus, situacao) 
									VALUES ('$nome_atv', '$descricao_atv', '$data_inicio', '$data_final', '$status', '$situacao')");
	if($insere_atividade){
			?>
			<script type="text/javascript">
				alert('Atividade cadastrada com sucesso!');
				var resultado = confirm('Deseja cadastrar outra atividade?');
				if(resultado){
					location.href = 'cadastro.php'
				}else{
					location.href = 'index.php';
				}
			</script>
			<?php
		}else{
			echo "<script>alert('Erro ao cadastrar. Tente novamente.'); history.back();</script>";
		}
	
}
?>