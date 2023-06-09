<?php
require_once("funcao.php");
require_once("header.php");
revalidarLogin();
?>

<body>
    <?php require_once("menu.php"); ?>
    <div class="content">
        <h2>Manutenção de Disciplinas</h2>
        <div>
            <tr>
                <td>IDDISCLIPLINA</td>
                <td>DSDISCIPLINA</td>
            </tr>
            <?php
            $rows = listarDisciplina();

            foreach ($rows as $registro) {
                echo "<tr>";
                echo "<td><a href=form_materia.php?alteraid=" . $registro['iddisciplina'] . ">" . $registro['dsdisciplina'] . ">";
                echo "<tr>";
            }
            ?>
        </div>

    </div>



</body>