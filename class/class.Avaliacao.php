<?php
require_once("class.BancoDeDados.php");

class Avaliacao extends BancoDeDados
{

    public function listarAvaliacoes()
    {
        $listar = $this->executarConsulta("select * from avaliacao av left outer join aluno a on av.idavaliacao = a.idaluno");
        //"select * from disciplina d left outer join aluno a on d.iddisciplina = a.idaluno");
        //"select * from avaliacao av left outer join aluno a on av.idavaliacao = a.idaluno"
        return $listar;
    }

}

$avaliacao = new Avaliacao();