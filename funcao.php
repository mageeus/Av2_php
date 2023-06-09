<?php
/*modificador tipo_de_retorno(parametros da função)
{
    returna algum objeto;    
}*/

$tamanhoMaxEmail = 300;
$tamanhoMaxUsuario = 10;
$tamanhoMinSenha = 5; //sem criptografia

/*
Parte nova
*/
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$user = 'root';
$password = '';
$database = 'aedb_quinto';

$hostname = "localhost";




function dumpF($string)
{
    echo "<pre>";
    var_dump($string);
    echo "</pre>";
}


function validarLogin($login, $senha)
{
    global $user, $password, $database, $hostname;

    $sqlLogin =   " SELECT * " .
        " FROM login l " .
        " WHERE l.dslogin = '@nome' " .
        " and l.dssenha = '@senha' ";


    $sql = str_replace("@nome", $login, $sqlLogin);
    $sql = str_replace("@senha", $senha, $sql);

    $con = mysqli_connect($hostname, $user, $password) or die('Erro na conexão');
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    # Seleciona o banco de dados 
    mysqli_select_db($con, $database) or die('Erro na seleção do banco');

    $resultado = mysqli_query($con, $sql);

    $registros = mysqli_num_rows($resultado);

    /*dumpF($registros);
    die($registros);*/

    if ($registros == 1) return true;

    return false;
}

function revalidarLogin()
{
    $token = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

    session_name($token);
    session_start();


    if (!isset($_SESSION["login"]) || !isset($_SESSION["senha"]) || !isset($_SESSION["token"])) {
        session_destroy();
        header("location:index.php?erro=SEMLOGIN");
    }

    if ($_SESSION["token"] != $token) {
        session_destroy();
        header("location:index.php?erro=INVASAO");
    }

    if (!validarLogin($_SESSION["login"], $_SESSION["senha"])) {
        session_destroy();
        header("location:index.php?erro=LOGININVALIDO");
    }
}

/*
Fim da parte nova
*/

function caracterInvalido($valor)
{
    if (strstr($valor, "'")) return true;
    if (strstr($valor, '"')) return true;
    if (strstr($valor, '<')) return true;
    if (strstr($valor, '>')) return true;
    if (strstr($valor, '--')) return true;

    return false;
}


function validar_nome($nome)
{
    global $tamanhoMaxUsuario;

    if (caracterInvalido($nome) == true) return "Erro: INVALIDO001";
    if (empty($nome) == true) return "Erro: EMBRANCO001";
    if (strlen($nome) > $tamanhoMaxUsuario) return "Erro: TAMANHO001";

    return "ok";
}


function validar_senha($senha)
{
    global $tamanhoMinSenha;

    if (caracterInvalido($senha) == true) return "Erro: INVALIDO001";
    if (empty($senha) == true) return "Erro: EMBRANCO001";
    if (strlen($senha) < $tamanhoMinSenha) return "Erro: TAMANHO002";

    return "ok";
}

function validar_email($email)
{
    global $tamanhoMaxEmail;

    if (caracterInvalido($email) == true) return "Erro: INVALIDO001";
    if (empty($email) == true) return "Erro: EMBRANCO001";
    if (strlen($email) > $tamanhoMaxEmail) return "Erro: TAMANHO003";

    if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) return "Erro: INVALIDO002";
    else return "ok";
}


//echo "Validar função: "  . validar_nome("bruno");
//echo "Validar senha: " . validar_senha("1234567890");
//echo "Validar email: " . validar_email("brunosal@danhagmail.com");

function listarAlunos()
{
    global $user, $password, $database, $hostname;

    $sqlAluno =   " SELECT * " .
        " FROM aluno ";

    $con = mysqli_connect($hostname, $user, $password) or die('Erro na conexão');
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    # Seleciona o banco de dados 
    mysqli_select_db($con, $database) or die('Erro na seleção do banco');

    $resultado = mysqli_query($con, $sqlAluno);

    $registros = mysqli_num_rows($resultado);

    $rows = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

    return $rows;
}


function listarAluno($idaluno)
{
    global $user, $password, $database, $hostname;

    $sqlAluno =   " SELECT * " .
        " FROM aluno 
          WHERE idaluno = @idaluno
        ";
    $sql = str_replace("@idaluno", $idaluno, $sqlAluno);

    $con = mysqli_connect($hostname, $user, $password) or die('Erro na conexão');
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    # Seleciona o banco de dados 
    mysqli_select_db($con, $database) or die('Erro na seleção do banco');

    $resultado = mysqli_query($con, $sql);

    $registros = mysqli_num_rows($resultado);

    $rows = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

    return $rows;
}


function alterarAluno($idaluno, $nmaluno)
{
    global $user, $password, $database, $hostname;

    $sqlUpdate = "update aluno
                     set nmaluno = '@nmaluno'
                   where idaluno = @idaluno";

    $sql = str_replace("@idaluno", $idaluno, $sqlUpdate);
    $sql = str_replace("@nmaluno", $nmaluno, $sql);

    echo $sql;

    $con = mysqli_connect($hostname, $user, $password) or die('Erro na conexão');
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    # Seleciona o banco de dados 
    mysqli_select_db($con, $database) or die('Erro na seleção do banco');

    $resultado = mysqli_query($con, $sql);

    return (mysqli_affected_rows($con) == 1);
}


function excluirAluno($idaluno)
{
    global $user, $password, $database, $hostname;

    $sqlUpdate = "delete from aluno
                   where idaluno = @idaluno";

    $sql = str_replace("@idaluno", $idaluno, $sqlUpdate);

    echo $sql;

    $con = mysqli_connect($hostname, $user, $password) or die('Erro na conexão');
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    # Seleciona o banco de dados 
    mysqli_select_db($con, $database) or die('Erro na seleção do banco');

    $resultado = mysqli_query($con, $sql);

    return (mysqli_affected_rows($con) == 1);
}




function incluirAluno($nmaluno)
{
    global $user, $password, $database, $hostname;

    $sqlInsert = "insert into aluno(nmaluno) values ('@nmaluno')";

    $sql = str_replace("@nmaluno", $nmaluno, $sqlInsert);

    echo $sql;

    $con = mysqli_connect($hostname, $user, $password) or die('Erro na conexão');
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    # Seleciona o banco de dados 
    mysqli_select_db($con, $database) or die('Erro na seleção do banco');

    $resultado = mysqli_query($con, $sql);

    return (mysqli_affected_rows($con) == 1);
}


/* grupo de funções para manipulação dos logins */

function listarLogins()
{
    $sqlListagem =  'SELECT * ' .
        ' FROM login l' .
        ' left outer join aluno a' .
        ' on l.idaluno = a.idaluno';

    global $user, $password, $database, $hostname;

    $con = mysqli_connect($hostname, $user, $password) or die('Erro na conexão');
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    # Seleciona o banco de dados 
    mysqli_select_db($con, $database) or die('Erro na seleção do banco');

    $resultado = mysqli_query($con, $sqlListagem);

    $registros = mysqli_num_rows($resultado);

    $rows = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

    return $rows;
}

function listarAlunosNaoRelacionados()
{
    $sqlNaoUtilizados = 'select * ' .
        ' from aluno a ' .
        ' where a.idaluno not in (select l.idaluno from login l where l.idaluno = a.idaluno) ';


    global $user, $password, $database, $hostname;

    $con = mysqli_connect($hostname, $user, $password) or die('Erro na conexão');
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    # Seleciona o banco de dados 
    mysqli_select_db($con, $database) or die('Erro na seleção do banco');

    $resultado = mysqli_query($con, $sqlNaoUtilizados);

    $registros = mysqli_num_rows($resultado);

    $rows = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

    return $rows;
}

function incluirLogin($dslogin, $dssenha, $idaluno)
{
    global $user, $password, $database, $hostname;

    $sqlInsert = "insert into login(dslogin,dssenha,idaluno) values ('@dslogin','@dssenha','@idaluno')";

    $sql = str_replace("@dslogin", $dslogin, $sqlInsert);
    $sql = str_replace("@dssenha", $dssenha, $sql);
    $sql = str_replace("@idaluno", $idaluno, $sql);

    echo $sql;

    $con = mysqli_connect($hostname, $user, $password) or die('Erro na conexão');
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    # Seleciona o banco de dados 
    mysqli_select_db($con, $database) or die('Erro na seleção do banco');

    $resultado = mysqli_query($con, $sql);

    return (mysqli_affected_rows($con) == 1);
}

function alterarSenha($dslogin, $dssenha)
{
    global $user, $password, $database, $hostname;

    $sqlUpdate = "update login
                     set dssenha = '@dssenha'
                   where dslogin = '@dslogin'";

    $sql = str_replace("@dslogin", $dslogin, $sqlUpdate);
    $sql = str_replace("@dssenha", $dssenha, $sql);

    echo $sql;

    $con = mysqli_connect($hostname, $user, $password) or die('Erro na conexão');
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    # Seleciona o banco de dados 
    mysqli_select_db($con, $database) or die('Erro na seleção do banco');

    $resultado = mysqli_query($con, $sql);

    return (mysqli_affected_rows($con) == 1);
}


function excluirAcesso($dslogin)
{
    global $user, $password, $database, $hostname;

    $sqlUpdate = "delete from login
                   where dslogin = '@dslogin'";

    $sql = str_replace("@dslogin", $dslogin, $sqlUpdate);

    echo $sql;

    $con = mysqli_connect($hostname, $user, $password) or die('Erro na conexão');
    if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());
    # Seleciona o banco de dados 
    mysqli_select_db($con, $database) or die('Erro na seleção do banco');

    $resultado = mysqli_query($con, $sql);

    return (mysqli_affected_rows($con) == 1);
}
