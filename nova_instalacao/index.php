<?php

include 'instalacao.inc';

/*
1a Etapa:

Pr�-Req: php-mysql
Executar: Informar Nome do Banco, Usu�rio, Senha e Host tentar criar o banco se n�o existir e importar o conte�do para o BD.
Criar o teleduc.conf com as informa��es acima. Se n�o for poss�vel pedir ao usu�rio e dar o conte�do. */

if (!VerificaRegisterGlobals()){
	die("� necess�rio habilitar o register_globals.");
}

if (!VerificaPHPMysql()){
	die("N�o foi encontrado o modulo php-mysql.");
}

/* USER INPUT */
$dbname = "TelEduc";
$dbnamecurso = "TelEducCurso_";
$dbuser = "root";
$dbpwd = "Myqui80n";
$dbhost = "localhost";
$dbport = "3306";

if (!$sock = VerificaExistenciaBD($dbname, $dbuser, $dbpwd, $dbhost, $dbport)){
	if (!CriaBasePrincipal($sock)){
		die("N�o foi poss�vel criar o BD, crie manualmente.");
	}
} 

InicializaBD($sock);

if (!CriaTelEducConf($dbname, $dbnamecurso, $dbuser, $dbpwd, $dbhost)){
	die("N�o foi poss�vel criar o arquivo teleduc.conf, crie manualmente.");
}

/*
2a Etapa:
Pr�-Req: 1a Etapa
Executar: Escolher pasta para arquivos (?), adivinhar host e caminho pela url.
Configurar os demais diretorios, (rever necessidade de alguns deles). */

/* USER INPUT - Pr�-Preenchidas */
$host = "quimera.nied.unicamp.br";
$www = "/~bruno/teleduc4";
$arquivos = "/home/bruno/arquivos";
$sendmail = "/usr/bin/sendmail";

RegistraConfigura��es($host, $www, $arquivos, $sendmail);

/*
3a Etapa:
Pr�-Req: 2a Etapa
Executar: Pedir ao admin colocar as tarefas do cron, perguntar o email do admtele e a senha para admtele. */

/* USER INPUT */
$admtele_email = "admtele@gmail.com";
$admtele_senha = "AAf2dfh9";

RegistraDadosAdmtele($admtele_email, $admtele_senha);

echo("N�o esque�a de configurar o cron.");

/*
4a Etapa:
Pr�-Req: 3a Etapa
Executar: Fim? Feedback e bot�o de entrar. */



?>