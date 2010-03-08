<?php

include 'instalacao.inc';

/*
1a Etapa:

Pr�-Req: php-mysql
Executar: Informar Nome do Banco, Usu�rio, Senha e Host tentar criar o banco se n�o existir e importar o conte�do para o BD.
Criar o teleduc.conf com as informa��es acima. Se n�o for poss�vel pedir ao usu�rio e dar o conte�do. */
if (!isset($_POST['etapa'])){
	$etapa = 1;
}


if (!VerificaRegisterGlobals()){
	die("� necess�rio habilitar o register_globals.");
}

if (!VerificaPHPMysql()){
	die("N�o foi encontrado o modulo php-mysql.");
}

/* USER INPUT */
$dbname = "TelEducNova";
$dbnamecurso = "TelEducNova";
$dbuser = "root";
$dbpwd = "root";
$dbhost = "localhost";
$dbport = "3306";

if (!$sock = VerificaExistenciaBD($dbname, $dbuser, $dbpwd, $dbhost, $dbport)){
	if (!CriaBasePrincipal($dbname, $dbuser, $dbpwd, $dbhost, $dbport)){
		die("N�o foi poss�vel criar o Banco de Dados: ".mysql_error());
	} else {
		$sock = mysql_connect($dbhost.":".$dbport, $dbuser, $dbpwd);
		mysql_select_db($dbname, $sock);
				
	}
} 

InicializaBD($sock);

if (!VerificaExistenciaArq("../cursos/aplic/bibliotecas/teleduc.inc")){
	$conteudo = CriaTelEducInc($dbname, $dbnamecurso, $dbuser, $dbpwd, $dbhost, $dbport);
	if ($conteudo !== true){
		die("N�o foi poss�vel criar o arquivo teleduc.conf, crie manualmente.<br /> Com o conteudo: <br /><br />".str_replace(";", ";<br/>",$conteudo));
		var_dump($conteudo);
	}
}


/*
2a Etapa:
Pr�-Req: 1a Etapa
Executar: Escolher pasta para arquivos (?), adivinhar host e caminho pela url.
Configurar os demais diretorios, (rever necessidade de alguns deles). */

/* USER INPUT - Pr�-Preenchidas */
$host = "localhost";
$www = "/teleduc4";
$arquivos = "/Users/brunobuccolo/Sites/teleduc4/arquivos";
$sendmail = "/usr/bin/sendmail";

RegistraConfiguracoes($sock, $host, $www, $arquivos, $sendmail);

/*
3a Etapa:
Pr�-Req: 2a Etapa
Executar: Pedir ao admin colocar as tarefas do cron, perguntar o email do admtele e a senha para admtele. */

/* USER INPUT */
$admtele_nome = "Bruno Buccolo";
$admtele_email = "admtele@gmail.com";
$admtele_senha = "AA2.FEIabj1C6";

RegistraDadosAdmtele($sock, $admtele_nome, $admtele_email, $admtele_senha);

echo("N�o esque�a de configurar o cron.");

/*
4a Etapa:
Pr�-Req: 3a Etapa
Executar: Fim? Feedback e bot�o de entrar. */



?>