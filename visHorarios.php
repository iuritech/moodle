<?php
session_start();
include('ferramentas.php');
include('bd.h');
include('bd_final.php');
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <style>
        .image-container {
            display: flex;
            gap: 40px;
            justify-content: center;
            margin-top: 30px;
        }
        .choice-img {
            width: 440px;
            height: 440px;
            object-fit: cover;
            border-radius: 16px;
            
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            cursor: pointer;
            transition: border 0.2s, box-shadow 0.2s;
        }
        .choice-img:hover {
            border: 4px solid #3498db;
            box-shadow: 0 8px 24px rgba(52,152,219,0.18);
        }
         table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 7px; text-align: center; min-width:120px; }
        select { border: 2px solid #ddd; background: #eee; padding: 10px; transition: 0.4s; }
        select:hover, select:focus { background: #ddd; }
        .h3 { background: #ddd; }
        .ocupado { background:hsl(212,35%,33%); color: #fff; vertical-align: top; cursor: move; }
        .disponivel { background: #f9f9f9; cursor: pointer; }
        .hora-coluna { background: #e6e6e6; font-weight: bold; }
        .panel { margin-bottom: 30px; }
        .ui-draggable-dragging { opacity: 0.8; z-index: 9999; }
        .ui-state-hover { background: #d4edff !important; }
    </style>
</head>
<body>
<?php gerarHome1(); ?>
<main style="padding-top:15px; height:790px; width:1600px;">
                <h3 style="margin-left:15px; margin-top:20px; margin-bottom: 25px;"><b>Escolhe Qual o Tipo de Horario:</b></h3>

    <div class="image-container">
        <!-- Imagem 1 -->
        <a href="gerirHorariosTurma.php">
            <img src="imagem1.jpg" alt="Opção 1" class="choice-img">
            <br>
            <h3>Horarios de Turmas</h3>
        </a>
        <!-- Imagem 2 -->
        <a href="gerirHorariosDocente.php">
            <img src="imagem2.jpg" alt="Opção 2" class="choice-img">
            <br>
            <h3>Horarios de Docentes</h3>
        </a>
    </div>
</main>
</body>
</html>