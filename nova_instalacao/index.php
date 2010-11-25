<?php

include 'instalacao.inc';

/* Para simplificar, a instalacao possui um template, para
 * o qual mandaremos o resultado da etapa atual, o controle de
 * etapas sera feito atraves de uma variavel e posts para a mesma
 * pagina. */



$console = "";
$content = "";
if (isset($_GET['etapa'])){
	$etapa = $_GET['etapa'];
} else if (isset($_POST['etapa'])){
	$etapa = $_POST['etapa'];
} else {
	$etapa = 0;
}

/* Caso a instala��o seja interrompida por fechar o browser ou algo
 * do genero, destruimos a sessao para evitar que o cache atrapalhe.
 */
if ($etapa > 0){
	session_start("instalacao_teleduc4");
} else {
	session_destroy;
}

/* Monta o console com as mensagens de acordo com as etapas concluidas */
if ($etapa > 1){
	$console .= "<p class=feedbackp>A diretiva register_globals est� habilitada. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";
	$console .= "<p class=feedbackp>O m�dulo php-mysql est� instalado. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";
}
if ($etapa > 2){
	$console .= "<p class=feedbackp>O banco de dados foi criado com sucesso. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";
	$console .= "<p class=feedbackp>O banco de dados foi inicializado com sucesso. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";
	$console .= "<p class=feedbackp>O arquivo de configura��o teleduc.inc foi criado. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";
}
	
if ($etapa > 3){
	$console .= "<p class=feedbackp>As configura��es de diretorio foram salvas. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";
}	
	

if ($etapa == 0){
	$content_header = "Bem-Vindo � Instala��o do TelEduc4!";

	$content .= "<p>Leia atentamente as instru��es contidas em cada passo da instala��o.</p>";
	$content .= "<p>Em caso de d�vida, consulte o nosso <a href=#>Guia de Instala��o.</a></p>";
	
	$content .= "<br />";
	$content .= "<div class=formulario>";
	$content .= "<form method='POST' action='index.php'>";
	$content .= "<input class='form' type=hidden name=etapa value='1'/><br />";
	$content .= "<input type=submit value='Instalar o TelEduc' class='form'/><br />";
	$content .= "</div>";

} else if ($etapa == 1){

	/* Erro na Instalacao */
	if (!VerificaRegisterGlobals()){

		$content_header = "N�o foi poss�vel continuar com a instala��o.";
		
		$console .= "<p class=feedbackp>A diretiva register_globals est� desativada. <img src='../cursos/aplic/imgs/errado.png'></p>";

		$content .= "<p>A diretiva register_globals deveria estar ligada.</p><br />";
		$content .= "<p>Para isso, edite o arquivo de configura��o do PHP conforme a seguir:</p>";
		$content .= "<pre>/etc/php.ini\n\nregister_globals = On</pre><br />";

		$content .= "<p>Se voc� est� instalando o TelEduc em uma hospedagem compartilhada, entre em 
						contato com a sua hospedagem para pedir altera��o da diretiva register_globals.</p><br/>";
		
		$content .= "<div class=formulario>";
		$content .= "<input type='button' value='Voltar' class='form' onClick='history.go(-1)'>";
		$content .= "<input type='button' value='Tentar Novamente' class='form' onClick='history.go(0)'>";
		$content .= "</div>";
		include 'template_instalacao.php';
		exit();

	} else {
		
		$console .= "<p class=feedbackp>A diretiva register_globals est� habilitada. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";
		
	}

	/* Erro na Instalacao */
	if (!VerificaPHPMysql()){

		$content_header = "N�o foi poss�vel continuar com a instala��o.";
		
		$console .= "<p class=feedbackp>O m�dulo php-mysql n�o foi encontrado. <img src='../cursos/aplic/imgs/errado.png'></p>";
		
		$content .= "<p>O m�dulo php-mysql n�o foi encontrado.</p><br />";

		$content .= "<p>A instala��o desse m�dulo deve ser feita de acordo com a distribui��o utilizada.</p> 
					 <p>Nas distribui��es mais populares, � poss�vel utilizar os seguintes comandos: </p><br />";

		$content .= "<pre style='margin-bottom: 8px'># Fedora e CentOS\nyum install php-mysql</pre>";
		$content .= "<pre># Debian e Ubuntu\napt-get install php5-mysql</pre><br/>";

		
		$content .= "<div class=formulario>";
		$content .= "<input type='button' value='Voltar' class='form' onClick='history.go(-1)'><br />";
		$content .= "<input type='button' value='Tentar Novamente' class='form' onClick='history.go(0)'>";
		$content .= "</div>";
		include 'template_instalacao.php';
		exit();

	} else {
		
		$console .= "<p class=feedbackp>O m�dulo php-mysql est� instalado. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";
	
	}
	
	$content_header = "Banco de Dados e Arquivo de Configura��o <span class=etapa>Etapa 1 de 4</span>";

	$content .= "<p>O TelEduc utiliza os seguintes bancos de dados:</p>";
	$content .= "<p style='margin-top:10px'>&nbsp;&nbsp;<b>Banco Geral</b> que cont�m configura��es e informa��es do ambiente.</p>";
	$content .= "<p>&nbsp;&nbsp;<b>Banco do Curso X</b> que cont�m as informa��es do curso com o c�digo X.</p>";
	$content .= "<br />";

	$content .= "<p>As informa��es para acesso aos bancos de dados ser�o gravadas a seguir em:</p>";
	$content .= "<pre>teleduc4/cursos/aplic/bibliotecas/teleduc.inc</pre>";
	
	$content .= "<br /><br />";
	$content .= "<div class=formulario>";
	$content .= "<form method='POST' action='index.php'>";
	$content .= "<label class='form' for=dbname>Banco Geral</label>";
	$content .= "<input class='form' size=25 type=text name=dbname value='".(isset($_SESSION['dbname']) ? $_SESSION['dbname'] : 'TelEduc4')."'/><br />";
	$content .= "<label class='form' for=dbnamecurso>Prefixo do Banco do Curso</label>";
	$content .= "<input class='form' size=25 type=text name=dbnamecurso value='".(isset($_SESSION['dbnamecurso']) ? $_SESSION['dbnamecurso'] : 'TelEduc4Curso_')."'/><br />";
	$content .= "<label class='form' for=dbuser>Usuario do MySQL</label>";
	$content .= "<input class='form' size=25 type=text name=dbuser value='".(isset($_SESSION['dbuser']) ? $_SESSION['dbuser'] : 'usuario')."'/><br />";
	$content .= "<label class='form' for=dbpwd>Senha do MySQL</label>";
	$content .= "<input class='form' size=25 type=password name=dbpwd value='".(isset($_SESSION['dbpwd']) ? $_SESSION['dbpwd'] : 'senha')."'/><br />";
	$content .= "<label class='form' for=dbhost>Servidor do MySQL</label>";
	$content .= "<input class='form' size=25 type=text name=dbhost value='".(isset($_SESSION['dbhost']) ? $_SESSION['dbhost'] : 'localhost')."'/><br />";
	$content .= "<label class='form' for=dbport>Porta do MySQL</label>";
	$content .= "<input class='form' size=25 type=text name=dbport value='".(isset($_SESSION['dbport']) ? $_SESSION['dbport'] : '3306')."'/><br />";
	$content .= "<input class='form' type=hidden name=etapa value='2'/><br />";
	$content .= "<input type=submit value='Prosseguir' class='form'/><br />";
	$content .= "</form>";
	$content .= "</div>";

} else if ($etapa == 2){
	
	/* Salva na sessao a informacao do banco de dados */
	$_SESSION['dbname'] = $_POST['dbname'];
	$_SESSION['dbnamecurso'] = $_POST['dbnamecurso'];
	$_SESSION['dbuser']  = $_POST['dbuser'];
	$_SESSION['dbpwd'] = $_POST['dbpwd'];
	$_SESSION['dbhost'] = $_POST['dbhost'];
	$_SESSION['dbport'] = $_POST['dbport'];

	if (!$sock = VerificaExistenciaBD($dbname, $dbuser, $dbpwd, $dbhost, $dbport)){

		/* Erro na Instalacao */
		if (!CriaBasePrincipal($dbname, $dbuser, $dbpwd, $dbhost, $dbport)){
			
			$content_header = "N�o foi poss�vel continuar com a instala��o.";
		
			$console .= "<p class=feedbackp>N�o foi poss�vel criar o banco de dados principal. <img src='../cursos/aplic/imgs/errado.png'></p>";
			
			$content .= "<p>N�o foi poss�vel criar o banco de dados principal. </p><br /><p>Verifique o nome de usu�rio e senha do banco de dados.</p><br />";
			$content .= "<p>Em caso de instala��o em banco de dados remoto, verifique se n�o h� algum firewall impedindo a conex�o ";
			$content .= "e se foi dada a permiss�o para a conex�o remota. </p><br /><p>O erro exibido pelo MySQL foi:</p>";
			$content .= "<pre>".mysql_error()."</pre>";
			
			$content .= "<div class=formulario>";
			$content .= "<input type='button' value='Voltar' class='form' onClick='history.go(-1)'>";
			$content .= "<input type='button' value='Tentar Novamente' class='formtn' onClick='history.go(0)'>";
			$content .= "</div>";
			
			include 'template_instalacao.php';
			exit();
		} else {
			$sock = mysql_connect($dbhost.":".$dbport, $dbuser, $dbpwd);
			mysql_select_db($dbname, $sock);

		}
	}
	
	$console .= "<p class=feedbackp>O banco de dados foi criado com sucesso. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";

	InicializaBD($sock);
	
	$console .= "<p class=feedbackp>O banco de dados foi inicializado com sucesso. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";
	

	if (!VerificaExistenciaArq("../cursos/aplic/bibliotecas/teleduc.inc")){
		$conteudo = CriaTelEducInc($dbname, $dbnamecurso, $dbuser, $dbpwd, $dbhost, $dbport);

		/* Erro na Instalacao */
		if ($conteudo !== true){
			
			$content_header = "N�o foi poss�vel continuar com a instala��o.";
		
			$console .= "<p class=feedbackp>N�o foi poss�vel criar o arquivo de configura��o. <img src='../cursos/aplic/imgs/errado.png'></p>";
			
			$content .= "<p>O usu�rio n�o possui as permiss�es para criar o arquivo de configura��o.</p><br/>";
			$content .= "<p>Crie o arquivo <b>teleduc.inc</b> em <b>../cursos/aplic/bibliotecas/</b> com o conte�do:</p>";
			
			$content .= "<div class=formulario>";
			$content .= "<pre>".htmlentities(str_replace(";",";\n",$conteudo))."</pre><br />";
			$content .= "<input type='button' value='Voltar' class='form' onClick='history.go(-1)'>";
			$content .= "<input type='button' value='Tentar Novamente' class='formtn' onClick='history.go(0)'>";
			$content .= "</div>";
			include 'template_instalacao.php';
			exit();
		}
	}
	
	
	
	$console .= "<p class=feedbackp>O arquivo de configura��o teleduc.inc foi criado. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";

	$content_header = "Servidor e Diret�rios <span class=etapa>Etapa 2 de 4</span>";

	$content .= "<p>Nesta etapa ser� necess�rio informar o nome do servidor e o caminho do TelEduc,</p>";
	$content .= "<p>que podem ser verificados dentro do navegador utilizando sua barra de endere�os: </p><pre>http://nome-do-servidor/caminho/do/teleduc/instalacao</pre><br/>";
	$content .= "<p>� necess�rio uma pasta para armazenar os arquivos dos usu�rios, certifique-se de";
	$content .= " que o servidor web tem as permiss�es necess�rias para escrever nessa pasta.</p><br />";
	$content .= "<p>O caminho para o execut�vel do sendmail � necess�rio para o envio de emails.</p>";

	$content .= "<br /><br />";
	$content .= "<div class=formulario>";
	$content .= "<form method='POST' action='index.php'>";
	$content .= "<label class='form' for=host>Nome do Servidor</label>";
	$content .= "<input type=text size=25 class='form' name=host value='".$_SERVER['SERVER_NAME']."'/><br />";
	$content .= "<label class='form' for=www>Caminho do TelEduc</label>";
	$content .= "<input type=text size=25 class='form' name=www value='".str_replace("nova_instalacao/index.php", "", $_SERVER['PHP_SELF'])."'/><br />";
	$content .= "<label class='form' for=arquivos>Arquivos de Usu�rio</label>";
	$content .= "<input type=text size=25 class='form' name=arquivos value='/home/teleduc4/arquivos'/><br />";
	$content .= "<label class='form' for=sendmail>Caminho do Sendmail</label>";
	$content .= "<input type=text size=25 class='form' name=sendmail value='/usr/sbin/sendmail'/><br />";
	$content .= "<input type=hidden name=etapa value='3'/><br />";
	$content .= "<input type=submit value='Prosseguir' class='form'/><br />";
	$content .= "</form>";
	$content .= "</div>";

} else if ($etapa == 3){

	$content_header = "Administrador do Ambiente <span class=etapa>Etapa 3 de 4</span>";

	/*
	 2a Etapa:
	 Pr�-Req: 1a Etapa
	 Executar: Escolher pasta para arquivos (?), adivinhar host e caminho pela url.
	 Configurar os demais diretorios, (rever necessidade de alguns deles). */

	if (!TestaAnexoArquivos($arquivos) && $bypass_anexo != 1){
			
		$content_header = "N�o foi poss�vel continuar com a instala��o.";
	
		$console .= "<p class=feedbackp>N�o foi poss�vel escrever na pasta de arquivos. <img src='../cursos/aplic/imgs/errado.png'></p>";
		
		$content .= "<p>O servidor n�o tem permiss�o para escrever na pasta de arquivos de usu�rio.</p><br />";
		$content .= "<p>Isso impedir� o ambiente de fazer o upload de arquivos.</p><br />";
		$content .= "<p>Verifique se a pasta existe e se as permiss�es est�o corretas: <pre>".$arquivos."</pre></p><br />";
		
		$content .= "<div class=formulario>";
		$content .= "<input type='button' value='Voltar' style='margin-left: 130px;' class='form' onClick='history.go(-1)'>";
		$content .= "<input type='button' value='Tentar Novamente' class='formtn' onClick=document.location='index.php?etapa=2'>";
		$content .= "<input type='button' value='Pular Verifica��o' class='formtn' onClick=document.location='index.php?etapa=3&bypass_anexo=1'>";
		$content .= "</div>";
		include 'template_instalacao.php';
		exit();
	} else {
		$console .= "<p class=feedbackp>� poss�vel escrever na pasta de arquivos. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";
	}
	
	$sock = mysql_connect($dbhost.":".$dbport, $dbuser, $dbpwd);
	mysql_select_db($dbname, $sock);

	RegistraConfiguracoes($sock, $host, $www, $arquivos, $sendmail);
	
	$console .= "<p class=feedbackp>As configura��es de diretorio foram salvas. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";

	$content .= "<p>O TelEduc utiliza o usu�rio <b>admtele</b> para acesso � administra��o do ambiente.</p>";
	$content .= "<p>Preencha os campos abaixo com os dados do administrador do ambiente.</p>";

	$content .= "<br /><br />";
	$content .= "<div class=formulario>";
	$content .= "<form method='POST' action='index.php'>";
	$content .= "<label class='form' for=admtele_nome>Nome</label>";
	$content .= "<input type=text size=25 class='form' name=admtele_nome value='Nome Sobrenome'/><br />";
	$content .= "<label class='form' for=admtele_email>E-Mail</label>";
	$content .= "<input type=text size=25 class='form' name=admtele_email value='nome@email.com'/><br />";
	$content .= "<label class='form' for=admtele_senha>Senha</label>";
	$content .= "<input type=password size=25 class='form' name=admtele_senha value='AA2.FEIabj1C6'/><br />";
	$content .= "<input type=hidden name=etapa value='4'/><br />";
	$content .= "<input type=submit value='Prosseguir' class='form'/><br />";
	$content .= "</form>";
	$content .= "</div>"; 



} else if ($etapa == 4){

	/*
	 3a Etapa:
	 Pr�-Req: 2a Etapa
	 Executar: Pedir ao admin colocar as tarefas do cron, perguntar o email do admtele e a senha para admtele. */
	
	$sock = mysql_connect($dbhost.":".$dbport, $dbuser, $dbpwd);
	mysql_select_db($dbname, $sock);

	RegistraDadosAdmtele($sock, $admtele_nome, $admtele_email, $admtele_senha);
	
	$console .= "<p class=feedbackp>As dados do administrador do ambiente foram salvos. <img src='../cursos/aplic/imgs/certo.png' alt='com sucesso'></p>";

	
	$content_header = "Fim da Instala��o <span class=etapa>Etapa 4 de 4</span>";
	
	$content .= "<b><p style='text-align: center; font-size: 20px;'>O TelEduc foi instalado e est� pronto para uso!</p></b><br />";
	$content .= "<p>Recomendamos a remo��o da pasta de instala��o por quest�es de seguran�a.</p><br/>";
	$content .= "<p>Habilite a notifica��o de novidades via email adicionando ao crontab:</p>";
	$content .= "<pre style='overflow: scroll'>0 17 * * * /usr/bin/lynx -dump http://hera.nied.unicamp.br/~teleduc4/scripts/notificar.php?notificar_email=1
0 9 * * * /usr/bin/lynx -dump http://hera.nied.unicamp.br/~teleduc4/scripts/notificar.php?notificar_email=2
0 18 * * * /usr/bin/lynx -dump http://hera.nied.unicamp.br/~teleduc4/scripts/notificar.php?notificar_email=2</pre><br />";
	
	$content .= "<input type=submit value='Entrar' onClick=\"document.location='../'\" class='form'/><br />";


}

include 'template_instalacao.php';

exit();


?>