<?php

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../../bd.h');

$query = mysqli_select_db($conn,"apoioutc_ano_atual");

$statement0 = mysqli_prepare($conn, "SELECT ano_atual FROM ano_atual;");
$statement0->execute();
$resultado0 = $statement0->get_result();
$linha0 = mysqli_fetch_assoc($resultado0);
	$nome_bd_atual = $linha0["ano_atual"];
	$ano_atual_temp = explode("_",$linha0["ano_atual"]);
	$ano_atual_bd = $ano_atual_temp[2] . "/" . $ano_atual_temp[3];

include('../../bd_final.php');

$id_utilizador = $_SESSION["id"];

$is_admin = false;

$statement = mysqli_prepare($conn, "SELECT is_admin FROM utilizador WHERE id_utilizador = $id_utilizador;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$is_admin_utilizador = $linha["is_admin"];
	
	if ($is_admin_utilizador == 0) {
		header("Location: ../index.php");
	}
	
$maior_ano_letivo_atual = "";
$maior_ano_letivo = "";
$anos_letivos = array();
	
$loop_1 = 0;	
	
$result = mysqli_query($conn,"SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA 
WHERE SCHEMA_NAME LIKE 'apoio_utc%' ORDER BY SCHEMA_NAME DESC;"); 
while ($row = mysqli_fetch_array($result)) { 
	$nome_bd = $row[0];
	
	$tmp = explode("_",$nome_bd);
	
	$nome_ano = $tmp[2] . "/" . $tmp[3];
	
	array_push($anos_letivos,$nome_bd);
	array_push($anos_letivos,$nome_ano);
	
	if($loop_1 == 0){
		$maior_ano_letivo_atual = $nome_ano;
		$maior_ano_letivo = $nome_ano;
	}
	
	$loop_1 += 1;
}

$tmp1 = explode("/",$maior_ano_letivo);
$tmp2 = $tmp1[0] + 1;
$tmp3 = $tmp1[1] + 1;

$maior_ano_letivo = $tmp2 . "/" . $tmp3;
	
?>
<div id="ano_letivo" class="modal-body" style="height:260px;">
	<text style="font-weight:500; font-size:16px; margin-right:15px;">Ano letivo atual: </text><select id="select_ano_letivo" style="width:115px;">
	<?php
		echo "<option value='$nome_bd_atual'>$ano_atual_bd</option>";
		
		$loop = 0;
		while($loop < sizeof($anos_letivos)){
			
			$nome_bd = $anos_letivos[$loop];
			$ano_letivo = $anos_letivos[$loop + 1];
			
			if($nome_bd != $nome_bd_atual){
				echo "<option value='$nome_bd'>$ano_letivo</option>";
			}
			
			$loop += 2;
		}
	?>
	</select>
	<br>
	 <button onclick="atualizarAnoLetivo('<?php echo $ano_atual_bd; ?>')" class="btn btn-primary" style="border-radius:50px; margin-top:10px; margin-left:15px;">
        <b>Atualizar</b>
    </button>
	
	<br>
	<br>
	<br>
	
	<text style="font-weight:500; font-size:18px; margin-right:15px;">Criar ano letivo: <b><?php echo $maior_ano_letivo; ?></b></text>
	<br>
	<input type="checkbox" id="copiar_dados_anteriores" onchange="mostrarDadosCopiarDadosAnteriores()" style="margin-right:5px;"><text style="font-weight:400; font-size:14px; margin-right:15px;">Copiar dados <?php echo $maior_ano_letivo_atual; ?></text>
	
	<div id="dados_anteriores_container" class="dados_anteriores_container">
		<input type="checkbox" id="dados_anteriores_utcs" onchange="atualizarOpcoesDadosAnteriores_UTC()" style="margin-left:15px; margin-right:5px;"><text style="font-weight:400; font-size:12px; margin-right:15px;">UTCs</text>
		<br>
		<input type="checkbox" id="dados_anteriores_cursos" onchange="atualizarOpcoesDadosAnteriores_cursos()" style="margin-left:15px; margin-right:5px;"><text style="font-weight:400; font-size:12px; margin-right:15px;">Cursos</text>
		<br>
		<input type="checkbox" id="dados_anteriores_areas" onchange="atualizarOpcoesDadosAnteriores_areas()" style="margin-left:15px; margin-right:5px;"><text style="font-weight:400; font-size:12px; margin-right:15px;">Áreas</text>
		<br>
		<input type="checkbox" id="dados_anteriores_docentes" onchange="atualizarOpcoesDadosAnteriores_docentes()" style="margin-left:15px; margin-right:5px;"><text style="font-weight:400; font-size:12px; margin-right:15px;">Docentes</text>
		<br>
		<input type="checkbox" id="dados_anteriores_ucs" onchange="atualizarOpcoesDadosAnteriores_ucs()" style="margin-left:15px; margin-right:5px;"><text style="font-weight:400; font-size:12px; margin-right:15px;">Unidades curriculares</text>
		<br>
		<input type="checkbox" id="dados_anteriores_turmas" onchange="atualizarOpcoesDadosAnteriores_turmas()" style="margin-left:15px; margin-right:5px;"><text style="font-weight:400; font-size:12px; margin-right:15px;">Turmas</text>
		<br>
		<input type="checkbox" id="dados_anteriores_dsd" onchange="atualizarOpcoesDadosAnteriores_dsd()" style="margin-left:15px; margin-right:5px;"><text style="font-weight:400; font-size:12px; margin-right:15px;">Distribuição de serviço docente</text>
		<br>
		<input type="checkbox" id="dados_anteriores_horarios" onchange="atualizarOpcoesDadosAnteriores_horarios()" style="margin-left:15px; margin-right:5px;"><text style="font-weight:400; font-size:12px; margin-right:15px;">Horários</text>
		
	</div>
	
	<button onclick="criarAnoLetivo()" class="btn btn-light btn-lg" style="border-radius:50px; margin-top:10px; margin-left:95px;">
        <b>Criar</b>
    </button>
	
</div>