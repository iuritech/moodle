<?php
// Formulário de criação de utilizadores

session_start(); 

if (!isset($_SESSION["sessao"])) {
    header("Location: ../index.php");
}
include('../bd.h');
include('../bd_final.php');
?>
<div class="modal-body"> 
	<div class="form-group row">
			<ul id="menu">
				<li class="parent"><a href="#">Docentes:</a>
				<ul class="child">			
				<?php 
					$area = $_GET["idArea"];
					echo "<script type='text/javascript'>teste123();</script>";
					$ids_ja_na_tabela = array(16,17);
					$ids_final = implode(",",$ids_ja_na_tabela);
						$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_area = $area AND id_utilizador NOT IN ($ids_final) ORDER BY nome;");
						$statement->execute();
						$resultado = $statement->get_result();
						while($linha = mysqli_fetch_assoc($resultado)){
							$id = $linha["id_utilizador"];
							$nome = $linha["nome"];
							array_push($ids_ja_na_tabela,$id);
							echo "<li class='lista'><a href='#' value='$id' onclick='adicionarDocenteFinal($id)'>$nome</a></li>";	
						}
					?>
				<li class="parent"><a href="#">Mais docentes...<span class="expand">»</span></a>
					<ul class="child">
					<?php 
					$ids_final = implode(",",$ids_ja_na_tabela);
						$statement = mysqli_prepare($conn, "SELECT * FROM utilizador WHERE id_utilizador NOT IN ($ids_final) AND id_area IS NOT NULL ORDER BY nome;");
						$statement->execute();
						$resultado = $statement->get_result();
						while($linha = mysqli_fetch_assoc($resultado)){
							$id = $linha["id_utilizador"];
							$nome = $linha["nome"];
							echo "<li class='lista'><a href='#' value='$id' onclick='adicionarDocenteFinal($id)'>$nome</a></li>";	
						}
					?>
			</ul>
		</li>
	</ul>
	</li>
</ul>
		</div>
	</div>
</div>