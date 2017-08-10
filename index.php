<?php 
require "conexao.php";
session_start();
if(isset($_SESSION['status']) && isset($_SESSION['situacao'])){
	$status = $_SESSION['status'];
	$situacao = $_SESSION['situacao'];
	$consulta_atividades = mysql_query("SELECT * FROM atividades WHERE idstatus = '$status' AND situacao = '$situacao'");
}else if(isset($_SESSION['status'])){
	$status = $_SESSION['status'];
	$consulta_atividades = mysql_query("SELECT * FROM atividades WHERE idstatus = '$status' ");
}else if(isset($_SESSION['situacao'])){
	$situacao = $_SESSION['situacao'];
	$consulta_atividades = mysql_query("SELECT * FROM atividades WHERE situacao = '$situacao'");
}else{
	$consulta_atividades = mysql_query("SELECT * FROM atividades");
}
$consulta_concluido = mysql_query("SELECT * FROM status WHERE descricao = 'Concluído'");
$array_concluido = mysql_fetch_array($consulta_concluido);
$consulta_status = mysql_query("SELECT * FROM status");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Controle de atividades</title>
	<meta charset="utf-8">
</head>
<body>
	<form method="post" action="?acao=filtrar">
		<h4>Filtrar por (escolher pelo menos um filtro ou os dois): </h4>
		<select name="status">
			<option value="S">STATUS</option>
			<?php 
			while ($array_status =mysql_fetch_array($consulta_status)) {
				?>
				<option value="<?php echo $array_status['idstatus'];?>"><?php echo $array_status['descricao'];?></option>
				<?php
			}
			?>
		</select>
		<select name="situacao">
			<option value="S">SITUACAO</option>
			<option value="A">Ativo</option>
			<option value="I">Inativo</option>
		</select>
		<input type="submit" name="" value="Filtrar">
	</form>
	<table id="tableatv" class="display">
		<thead>
		<tr>
			<td style="text-align: center" colspan="8"> <h3>Controle de Atividades</h3></td>
		</tr>
			<th>Nome</th>
			<th>Descrição</th>
			<th>Data Início</th>
			<th>Data Final</th>
			<th>Status</th>
			<th>Situação</th>
		</thead>
		<tbody>
		<?php 
		while($array_atividades = mysql_fetch_array($consulta_atividades)){
			?>
			<form method="post" action="?acao=alterar">
			<?php 
			if($array_atividades['idstatus'] == $array_concluido['idstatus']){
				$disabled = 'disabled';
				$style = "background-color:green";
			}else{
				$disabled = '';
				$style = '';
			}
			?>
			<tr style="<?php echo $style;?>">
				<td><input type="text" name="nome_atv" value="<?php echo $array_atividades['nome'];?>" maxlength='255' required <?php echo $disabled;?>></td>
				<td><input type="text" name="descricao_atv" value="<?php echo $array_atividades['descricao'];?>" maxlength='600' required <?php echo $disabled;?>></td>
				<td><input type="date" name="data_inicio" value="<?php echo $array_atividades['datainicio'];?>" required <?php echo $disabled;?>></td>
				<td><input type="date" name="data_final" value="<?php echo $array_atividades['datafinal'];?>" <?php echo $disabled;?>></td>
				<td><?php 
					$idstatus = $array_atividades['idstatus'];
					?>
					<select name ="status" <?php echo $disabled;?>>
					<?php
					$consulta_status = mysql_query("SELECT * FROM status");
					while($array_status = mysql_fetch_array($consulta_status)){
						if($array_status['idstatus'] == $idstatus){
							$selected = 'selected';
						}else{
							$selected = '';
						}
						?>	
						<option value="<?php echo $array_status['idstatus'];?>" <?php echo $selected;?>><?php echo $array_status['descricao'];?></option>
						<?php
					}
					?>
					</select>
				</td>
				<td><?php 
					if($array_atividades['situacao'] == 'A'){
						$situacao = 'Ativo';
						$selected_ativo = 'selected';
						$selected_inativo = '';
					}else{
						$situacao = 'Inativo';
						$selected_ativo = '';
						$selected_inativo = 'selected';
					}
					?>
						<select name="situacao" <?php echo $disabled;?>>
							<option value="A" <?php echo $selected_ativo;?>>Ativo</option>
							<option value="I" <?php echo $selected_inativo?>>Inativo</option>
						</select>
					</td>
					<td><input type="text" name="idatividade" value="<?php echo $array_atividades['idatividade'];?>" style="display: none"></td>
					<td>
						<input type="submit" name="Alterar" value="Editar" <?php echo $disabled;?>></td>
			</tr>
			</form>
			<?php
		}
		?>
		</tbody>
	</table>
	<br>
	<button onclick='location.href="cadastro.php"'>Incluir</button>
	
</body>
</html>
<?php 
if(@$_GET['acao'] == 'alterar'){
	$id_atv = $_POST['idatividade'];
	$consulta_atividade = mysql_query("SELECT * FROM atividades WHERE idatividade = '$id_atv'");
	$array_atividades = mysql_fetch_array($consulta_atividade);
	$nome_atv = $_POST['nome_atv'];
	$descricao_atv = $_POST['descricao_atv'];
	$data_inicio = $_POST['data_inicio'];
	if($_POST['data_final'] == ""){
		$data_final = "0000-00-00";
	}else{
		$data_final = $_POST['data_final'];	
	}
	$status = $_POST['status'];
	$situacao = $_POST['situacao'];

	if($array_atividades['nome'] == $nome_atv && $array_atividades['descricao'] == $descricao_atv && $array_atividades['datainicio'] == $data_inicio && $array_atividades['datafinal'] == $data_final && $array_atividades['idstatus'] == $status && $array_atividades['situacao'] == $situacao){
		echo "<script>alert('Nenhum campo foi alterado.'); history.back();</script>";
	}else{
		if($data_final != '0000-00-00'){
			if(strtotime($data_inicio) > strtotime($data_final)){
				echo "<script>alert('A data final não pode ser maior que a data inicial!'); history.back();</script>";	
				exit();
			}
		}
		$consulta_concluido = mysql_query("SELECT * FROM status WHERE descricao = 'Concluído'");
		$array_concluido = mysql_fetch_array($consulta_concluido);
		if($array_concluido['idstatus'] == $status && $data_final == '0000-00-00'){
			echo "<script>alert('É necessário inserir uma data final quando a atividade é concluída!'); history.back();</script>";
			exit();
		}
		$atualiza_atividade = mysql_query("UPDATE atividades SET nome ='$nome_atv', descricao = '$descricao_atv', datainicio = '$data_inicio', datafinal = '$data_final', idstatus = '$status', situacao = '$situacao' WHERE idatividade = '$id_atv'");
		if($atualiza_atividade){
			echo "<script>alert('Atualização feita com sucesso!'); location.href = 'index.php';</script>";	
			if(isset($_SESSION['status'])){
			unset($_SESSION['status']);
			}
			if(isset($_SESSION['situacao'])){
				unset($_SESSION['situacao']);
			}
		}else{
			echo "<script>alert('Erro ao atualizar. Tente novamente.'); history.back();</script>";
		}
	}
}

if(@$_GET['acao'] == 'filtrar'){
	if($_POST['status'] == 'S' && $_POST['situacao'] == 'S'){
		if(isset($_SESSION['status'])){ 
			unset($_SESSION['status']);
		}
		if(isset($_SESSION['situacao'])){
			unset($_SESSION['situacao']);
		}
		echo "<script>alert('Para filtrar é necessário escolher pelo menos um tipo de filtro.'); location.href ='index.php';</script>";
	}else{
		if($_POST['status'] != 'S'){
			$_SESSION['status'] = $_POST['status'];	
		}else{
			if(isset($_SESSION['status'])){
				unset($_SESSION['status']);
			}
		}
		if($_POST['situacao'] != 'S'){
			$_SESSION['situacao'] = $_POST['situacao'];
		}else{
			if(isset($_SESSION['situacao'])){
				unset($_SESSION['situacao']);
			}
		}
		echo "<script>location.href = 'index.php';</script>";
	}
}
?>