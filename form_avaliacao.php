<?php
require_once("class/class.Login.php");
require_once("class/class.Avaliacao.php");
require_once("header.php");
$obj_login->revalidarLogin();
?>

<body>

    <?php require_once("menu.php") ?>

    <div class="content">
        <br />
        <table>
            <tr>
                <td>idavalicao</td>
                <td>nmaluno</td>
                <td>dsdisciplina</td>
                <td>nota</td>
            </tr>
            <?php

            $registros = $avaliacao->listarAvaliacoes();

            foreach ($registros as $linha) {
                echo '<tr>';
                echo '    <td>' . $linha['idavaliacao'] . '</td>';
                echo '    <td>' . $linha['nmaluno'] . '</td>';
                echo '    <td>' . $linha['dsdisciplina'] . '</td>';
                echo '    <td>' . '<a href=form_avaliacao.php?alterar=' . $linha['nota'] . '>' . $linha['nota'] . '</a> </td>';
                echo '</tr>';
            }

            ?>
        </table>
        <?php
        if(isset($_GET['alterar'])){
        ?>
    <hr>
    <form action="form_avaliacao.php" method="POST">
        aluno: <input name="nmaluno" type="text" readonly value="<?php echo $_GET['alterar'] ?>">
        disciplina: 
        }

</body>