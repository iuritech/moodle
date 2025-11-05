<?php
// Página de visualização de distribuição de serviço ordenada por docente (DSD)

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: index.php");
}
include('ferramentas.php');
include('bd.h');
include('bd_final.php');

$id_utilizador_atual = $_SESSION["id"];
$semestre_atual = $_GET["sem"];

$statement = mysqli_prepare($conn, "SELECT id_utc, perm_horarios, is_admin FROM utilizador WHERE id_utilizador = $id_utilizador_atual;");
$statement->execute();
$resultado = $statement->get_result();
$linha = mysqli_fetch_assoc($resultado);
	$id_utc = $linha["id_utc"];
	$perm_horarios = $linha["perm_horarios"];
	$perm_admin = $linha["is_admin"];
	
$statement1 = mysqli_prepare($conn, "SELECT id_responsavel FROM utc WHERE id_utc = $id_utc;");
$statement1->execute();
$resultado1 = $statement1->get_result();
$linha1 = mysqli_fetch_assoc($resultado1);
	$id_responsavel_utc = $linha1["id_responsavel"];

if(($id_responsavel_utc != $id_utilizador_atual) && ($perm_horarios == 0) && ($perm_admin == 0)){
	header("Location: home.php");
}

?>
<?php gerarHome1() ?>
<main style="padding-top:15px; height:790px; width:1600px;">
<div class="container-fluid" style="height:750px;">
	<div class="card shadow mb-4" style="height:750px;">
		<div class="card-body">
			<a href="http://localhost/apoio_utc/home.php"><h6 style="margin-top:10px; margin-left:15px;">Painel do utilizador</a> / <a href="">Horários - <b>Configurar</b></a></h6>
			<h3 style="margin-left:15px; margin-top:20px; margin-bottom: 25px;"><b>Horários</b></h3>
			<br>			
			<br>
					<b style="margin-left:29px;">Docente</b><br>
					<select id="aulasPorAtribuirSelectDocente" onchange="atualizarAulasPorAtribuir(2)" style="width:97px;  margin-left:8px;">
					<?php
						
						echo "<option value=''></option>";
						
						$statement4 = mysqli_prepare($conn, "SELECT DISTINCT a.id_docente, u.nome FROM aula a INNER JOIN componente c ON a.id_componente = c.id_componente INNER JOIN disciplina d ON c.id_disciplina = d.id_disciplina INNER JOIN curso cu ON d.id_curso = cu.id_curso INNER JOIN utilizador u ON a.id_docente = u.id_utilizador WHERE d.semestre = $semestre_atual AND cu.id_utc = $id_utc AND a.id_docente IS NOT NULL AND a.id_horario IS NULL ORDER BY u.nome;");
						$statement4->execute();
						$resultado4 = $statement4->get_result();
						while($linha4 = mysqli_fetch_assoc($resultado4)){
							$id_docente = $linha4["id_docente"];
							$nome_docente = $linha4["nome"];
							
							echo "<option value='$id_docente'>$nome_docente</option>";
						}
					
					?>
					</select>
				</div>
				
				<div id="horarios_janela_por_atribuir_aulas" class="horarios_janela_por_atribuir_aulas">
				<?php
				
				$turmas_tratadas = array();
				
				$numero_horas_por_atribuir = 0;
				
				$statement = mysqli_prepare($conn, "SELECT DISTINCT a.*, t.semestre, c.id_curso FROM aula a INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE c.id_utc = $id_utc AND t.semestre = $semestre_atual AND a.id_docente IS NOT NULL AND a.id_horario IS NULL;");
				$statement->execute();
				$resultado = $statement->get_result();
				while($linha = mysqli_fetch_assoc($resultado)){
					$id_componente = $linha["id_componente"];
					$id_turma = $linha["id_turma"];
					$id_docente = $linha["id_docente"];
					$id_juncao = $linha["id_juncao"];
					$id_curso = $linha["id_curso"];
					
					$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$id_disciplina = $linha3["id_disciplina"];
						$numero_horas = $linha3["numero_horas"];
						$id_tipocomponente = $linha3["id_tipocomponente"];
						
						$numero_horas_por_atribuir += $numero_horas;
				}
				
				//echo $numero_horas_por_atribuir;
				
				$statement = mysqli_prepare($conn, "SELECT DISTINCT a.*, t.semestre, c.id_curso FROM aula a INNER JOIN turma t ON a.id_turma = t.id_turma INNER JOIN curso c ON t.id_curso = c.id_curso WHERE c.id_utc = $id_utc AND t.semestre = $semestre_atual AND a.id_docente IS NOT NULL AND a.id_horario IS NULL;");
				$statement->execute();
				$resultado = $statement->get_result();
				while($linha = mysqli_fetch_assoc($resultado)){
					$id_componente = $linha["id_componente"];
					$id_turma = $linha["id_turma"];
					$id_docente = $linha["id_docente"];
					$id_juncao = $linha["id_juncao"];
					$id_curso = $linha["id_curso"];
					
					$statement3 = mysqli_prepare($conn, "SELECT id_disciplina, numero_horas, id_tipocomponente FROM componente WHERE id_componente = $id_componente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$id_disciplina = $linha3["id_disciplina"];
						$numero_horas = $linha3["numero_horas"];
						$id_tipocomponente = $linha3["id_tipocomponente"];
						
					$statement3 = mysqli_prepare($conn, "SELECT sigla_tipocomponente FROM tipo_componente WHERE id_tipocomponente = $id_tipocomponente;");
					$statement3->execute();
					$resultado3 = $statement3->get_result();
					$linha3 = mysqli_fetch_assoc($resultado3);
						$sigla_tipocomponente = $linha3["sigla_tipocomponente"];
								
					$statement4 = mysqli_prepare($conn, "SELECT abreviacao_uc FROM disciplina WHERE id_disciplina = $id_disciplina;");
					$statement4->execute();
					$resultado4 = $statement4->get_result();
					$linha4 = mysqli_fetch_assoc($resultado4);
						$abreviacao_disciplina = $linha4["abreviacao_uc"];
							
					$statement5 = mysqli_prepare($conn, "SELECT sigla FROM curso WHERE id_curso = $id_curso;");
					$statement5->execute();
					$resultado5 = $statement5->get_result();
					$linha5 = mysqli_fetch_assoc($resultado5);
						$sigla_curso = $linha5["sigla"];
							
					$statement6 = mysqli_prepare($conn, "SELECT nome FROM turma WHERE id_turma = $id_turma;");
					$statement6->execute();
					$resultado6 = $statement6->get_result();
					$linha6 = mysqli_fetch_assoc($resultado6);
						$nome_turma = $linha6["nome"];
								
					$statement7 = mysqli_prepare($conn, "SELECT nome FROM utilizador WHERE id_utilizador = $id_docente;");
					$statement7->execute();
					$resultado7 = $statement7->get_result();
					$linha7 = mysqli_fetch_assoc($resultado7);
						$nome_docente = $linha7["nome"];
								
						$nome_docente_temp = explode(" ",$nome_docente);
						if((strlen($nome_docente) > 14) || (sizeof($nome_docente_temp) > 2)){							
							$nome_temp = substr($nome_docente_temp[0],0,1) . ". " . $nome_docente_temp[(sizeof($nome_docente_temp) - 1)];
							$nome_docente = $nome_temp;
						}
						
					if($id_juncao != null){
						$statement8 = mysqli_prepare($conn, "SELECT COUNT(DISTINCT id_componente) FROM aula WHERE id_juncao = $id_juncao;");
						$statement8->execute();
						$resultado8 = $statement8->get_result();
						$linha8 = mysqli_fetch_assoc($resultado8);
							$num_componentes_diferentes = $linha8["COUNT(DISTINCT id_componente)"];
							
							if($num_componentes_diferentes > 1){
								$tipo_juncao = 2;
							}
							else{
								$tipo_juncao = 1;
							}	
						
					}	
							
					if($numero_horas == 1){
						if($numero_horas_por_atribuir <= 13){
							echo "<div class='horario_por_atribuir_1_hora' style='margin-left:8px;'>";
						}
						else{
							echo "<div class='horario_por_atribuir_1_hora'>";
						}
					}
					if($numero_horas == 1.5){
						if($numero_horas_por_atribuir <= 13){
							echo "<div class='horario_por_atribuir_1_5_hora' style='margin-left:8px;'>";
						}
						else{
							echo "<div class='horario_por_atribuir_1_5_hora'>";
						}
					}
					if($numero_horas == 2){
						if($numero_horas_por_atribuir <= 13){
							echo "<div class='horario_por_atribuir_2_hora' draggable='true' ondragstart='dragAulaPorAtribuir(event,", $numero_horas, ",", $id_componente, ",", $id_docente, ",", $id_turma, ",", $id_juncao, ")' ondragend='dragEndAulaPorAtribuir(event)' style='margin-left:8px;'>";
						}
						else{
							echo "<div class='horario_por_atribuir_2_hora' draggable='true' ondragstart='dragAulaPorAtribuir(event,", $numero_horas, ",", $id_componente, ",", $id_docente, ",", $id_turma, ",", $id_juncao, ")' ondragend='dragEndAulaPorAtribuir(event)'>";
						}
					}
					if($numero_horas == 2.5){
						if($numero_horas_por_atribuir <= 13){
							echo "<div class='horario_por_atribuir_2_5_hora' style='margin-left:8px;'>";
						}
						else{
							echo "<div class='horario_por_atribuir_2_5_hora'>";
						}
					}
					if($numero_horas == 3){
						if($numero_horas_por_atribuir <= 13){
							echo "<div class='horario_por_atribuir_3_hora' draggable='true' ondragstart='dragAulaPorAtribuir(event,", $numero_horas, ",", $id_componente, ",", $id_docente, ",", $id_turma, ",", $id_juncao, ")' ondragend='dragEndAulaPorAtribuir(event)' style='margin-left:8px;'>";
						}
						else{
							echo "<div class='horario_por_atribuir_3_hora' draggable='true' ondragstart='dragAulaPorAtribuir(event,", $numero_horas, ",", $id_componente, ",", $id_docente, ",", $id_turma, ",", $id_juncao, ")' ondragend='dragEndAulaPorAtribuir(event)'>";
						}
					}
					if($numero_horas == 3_5){
						if($numero_horas_por_atribuir <= 13){
							echo "<div class='horario_por_atribuir_3_5_hora' style='margin-left:8px;'>";
						}
						else{
							echo "<div class='horario_por_atribuir_3_5_hora'>";
						}
					}
					if($numero_horas == 4){
						if($numero_horas_por_atribuir <= 13){
							echo "<div class='horario_por_atribuir_4_hora' style='margin-left:8px;'>";
						}
						else{
							echo "<div class='horario_por_atribuir_4_hora'>";
						}
					}
					if($numero_horas == 4.5){
						if($numero_horas_por_atribuir <= 13){
							echo "<div class='horario_por_atribuir_4_5_hora' style='margin-left:8px;'>";
						}
						else{
							echo "<div class='horario_por_atribuir_4_5_hora'>";
						}
					}
					if($numero_horas == 5){
						if($numero_horas_por_atribuir <= 13){
							echo "<div class='horario_por_atribuir_5_hora' style='margin-left:8px;'>";
						}
						else{
							echo "<div class='horario_por_atribuir_5_hora'>";
						}
					}
					
					echo "<div class='horario_por_atribuir_cover'>", $sigla_curso, "_", $abreviacao_disciplina, " - ", $sigla_tipocomponente, "<br>", $nome_docente, "<br>", $nome_turma;
							
					if($id_juncao != null){
						if($tipo_juncao == 1){
							echo "<img src='http://localhost/apoio_utc/images/join.png' style='width:10px; height:10px; margin-left:4px;'>";
						}
						else{
							echo "<img src='http://localhost/apoio_utc/images/join_laranja.png' style='width:10px; height:10px; margin-left:4px;'>";
						}
					}
					
					echo "</div></div>";
					
				}
				
				?>
				</div>
				
			</div>