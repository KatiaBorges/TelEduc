<?
/*
<!--
-------------------------------------------------------------------------------

    Arquivo : cursos/aplic/material/importar_perguntas2.php

    TelEduc - Ambiente de Ensino-Aprendizagem a Dist�ncia
    Copyright (C) 2001  NIED - Unicamp

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2 as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

    You could contact us through the following addresses:

    Nied - N�cleo de Inform�tica Aplicada � Educa��o
    Unicamp - Universidade Estadual de Campinas
    Cidade Universit�ria "Zeferino Vaz"
    Bloco V da Reitoria - 2o. Piso
    CEP:13083-970 Campinas - SP - Brasil

    http://www.nied.unicamp.br
    nied@unicamp.br

------------------------------------------------------------------------------
-->
*/


/*==========================================================
  ARQUIVO : cursos/aplic/material/importar_perguntas2.php
  ========================================================== */
  $bibliotecas="../bibliotecas/";
  include($bibliotecas."geral.inc");
  include($bibliotecas."importar.inc");
  include("perguntas.inc");

  // **************** VARI�VEIS DE ENTRADA ****************
  // Recebe de 'importar_perguntas.php'
  //    c�digo do curso
  $cod_curso = $_POST['cod_curso'];
  //    c�digo da categoria que estava sendo listada.
  $cod_categoria = $_POST['cod_categoria'];
  //    c�digo do t�pico, que estava visualizando antes da importa��o,
  //  para o qual ir� importar os itens
  $cod_topico_raiz = $_POST['cod_topico_raiz'];
  //    c�digo do curso do qual itens ser�o importados
  $cod_curso_import = $_POST['cod_curso_import'];
  //    c�digo da ferramenta cujos itens ser�o importados
  $cod_ferramenta = $_POST['cod_ferramenta'];
  //    tipo do curso: A(ndamento), I(nscri��es abertas), L(atentes),
  //  E(ncerrados)
  $tipo_curso = $_POST['tipo_curso'];
  if ('E' == $tipo_curso)
  {
    //    per�odo especificado para listar os cursos
    //  encerrados.
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
  }
  //    booleano, se o curso, cujos itens ser�o importados, foi
  //  escolhido na lista de cursos compartilhados.
  $curso_compartilhado = $_POST['curso_compartilhado'];
  //    booleando, se o curso, cujos itens ser�o importados, � um
  //  curso extra�do.
  $curso_extraido = $_POST['curso_extraido'];
  //    c�digo do t�pico do curso do qual itens ser�o importados.
  $cod_topico_raiz_import = $_POST['cod_topico_raiz_import'];
  //    arrays de itens e t�picos que ser�o importados
  $cod_itens_import = $_POST['cod_itens_import'];
  $cod_topicos_import = $_POST['cod_topicos_import'];

  // ******************************************************


  /* Registrando c�digo da ferramenta nas vari�veis de sess�o.
     � necess�rio para saber qual ferramenta est� sendo
     utilizada, j� que este arquivo faz parte de quatro
     ferramentas quase distintas.
   */
  session_register("cod_ferramenta_s");
  if (isset($cod_ferramenta))
    $cod_ferramenta_s = $cod_ferramenta;
  else
    $cod_ferramenta = $cod_ferramenta_s;

  /* Necess�rio para a lixeira. */
  session_register("cod_topico_s");
  unset($cod_topico_s);

  session_register("login_import_s");
  if (isset($login_import))
    $login_import_s = $login_import;
  else
    $login_import = $_SESSION['login_import_s'];

  // Se n�o foi definido um curso do qual ser�o importados
  // itens, emite uma mensagem de erro.
  if (!isset($cod_curso_import))
  {
    // ?? - Erro ! Nenhum c�digo de curso para importa��o foi recebido !
    echo ("Erro ! Nenhum c�digo de curso para importa��o foi recebido !");
    die();
  }

  // Se o curso DO QUAL ser�o importados itens foi montado
  // na base tempor�ria, ent�o define o par�metro $opt para
  // conex�o a ela.
  if ($curso_extraido)
    $opt = TMPDB;
  else
    $opt = "";

  // Autentica no curso PARA O QUAL ser�o importados os itens.
  $cod_usuario = VerificaAutenticacao($cod_curso);

  // Se o curso foi selecionado na lista de todos cursos e
  // a autentica��o do usu�rio nesse curso n�o � v�lida ent�o
  // encerra a execu��o do script.
  if ((!$curso_compartilhado) &&
      (false === ($cod_usuario_import = UsuarioEstaAutenticadoImportacao($cod_curso, $cod_usuario, $cod_curso_import, $opt))))
  {
    // Testar se � identicamente falso,
    // pois 0 pode ser um valor v�lido para cod_usuario
    echo("<html>\n");
    echo("  <script language=javascript type=text/javascript defer>\n\n");

    echo("    function ReLogar()\n");
    echo("    {\n");
   // ?? - Login ou senha inv�lidos
    echo("      alert('[Login ou senha inv�lidos]');\n");
    echo("      document.frmRedir.submit();\n");
    echo("    }\n\n");

    echo("  </script>\n\n");
    echo("  <body onLoad='ReLogar();'>\n");
    echo("    <form method=post name=frmRedir action=importar_curso.php>\n");
    echo(RetornaSessionIDInput());
    echo("      <input type=hidden name=cod_curso value=".$cod_curso.">\n");
    echo("      <input type=hidden name=cod_categoria value=".$cod_categoria.">\n");
    echo("      <input type=hidden name=cod_topico_raiz value=".$cod_topico_raiz.">\n");
    echo("      <input type=hidden name=cod_ferramenta value=".$cod_ferramenta.">\n");
    echo("    </form>\n");

    echo("  </body>\n");
    echo("</html>\n");
    exit();
  }

  $sock = Conectar("");

  // Marca data de �ltimo acesso ao curso tempor�rio. Esse recurso � importante
  // para elimina��o das bases tempor�rias, mediante compara��o dessa data adicionado
  // um per�odo de folga com a data em que o script para elimina��o estiver rodando.
  MarcarAcessoCursoExtraidoTemporario($sock, $cod_curso_import);

  $lista_frases = RetornaListaDeFrases($sock, $cod_ferramenta);
  $lista_frases_geral = RetornaListaDeFrases($sock,-1);

  // Se o curso foi montado (extra�do) lista os arquivos do caminho
  // tempor�rio.
  if ($curso_extraido)
    $diretorio_arquivos_origem = RetornaDiretorio($sock, 'Montagem');
  else
    $diretorio_arquivos_origem = RetornaDiretorio($sock, 'Arquivos');

  // Raiz do diret�rio de arquivos do curso PARA O QUAL ser�o importados
  // os itens.
  $diretorio_arquivos_destino = RetornaDiretorio($sock, 'Arquivos');
  $diretorio_temp = RetornaDiretorio($sock, 'ArquivosWeb');

  Desconectar($sock);

  // Conecta-se � base do curso.
  $sock = Conectar($cod_curso_import, $opt);

  // Obt�m o nome do curso.
  $nome_curso_import = NomeCurso($sock, $cod_curso_import);

  // Se o curso n�o foi selecionado na lista de todos cursos,
  // verifica as permiss�es de acesso ao curso e �s ferramentas.
  if (!$curso_compartilhado)
  {
    VerificaAcessoAoCurso($sock, $cod_curso_import, $cod_usuario_import);
    VerificaAcessoAFerramenta($sock, $cod_curso_import, $cod_usuario_import, $cod_ferramenta);
  }

  Desconectar($sock);

  echo("<html>\n");
  /* 1 - : Perguntas Freq�entes*/
  echo("  <head><title>TelEduc - ".RetornaFraseDaLista($lista_frases,1)."</title></head>\n");
  echo("  <link rel=stylesheet TYPE=text/css href=../teleduc.css>\n");
  
  echo("  <link rel=stylesheet TYPE=text/css href=perguntas.css>\n");
  $tabela="Pergunta";
  $dir="perguntas";
  
  echo("<body link=#0000ff vlink=#0000ff bgcolor=white onLoad='document.frmImportar.submit()'>\n");

  if (ImportarMateriais($cod_curso, $cod_topico_raiz, $cod_usuario,
                        $cod_curso_import, $curso_extraido, $curso_compartilhado,
                        $cod_topicos_import, $cod_itens_import, $tabela,
                        $dir, $diretorio_arquivos_destino, $diretorio_arquivos_origem))
    $sucesso = true;
  else
    $sucesso = false;


  echo("  <form method=post name=frmImportar action=importar_perguntas3.php>\n");
  echo(RetornaSessionIDInput()."\n");
  echo("    <input type=hidden name=cod_curso value=".$cod_curso.">\n");
  echo("    <input type=hidden name=cod_categoria value=".$cod_categoria.">\n");
  echo("    <input type=hidden name=cod_topico_raiz value=".$cod_topico_raiz.">\n");

  echo("    <input type=hidden name=cod_curso_import value=".$cod_curso_import.">\n");
  echo("    <input type=hidden name=cod_topico_raiz_import value=".$cod_topico_raiz_import.">\n");

  echo("    <input type=hidden name=curso_extraido value=".$curso_extraido.">\n");
  echo("    <input type=hidden name=curso_compartilhado value=".$curso_compartilhado.">\n");

  echo("    <input type=hidden name=tipo_curso value='".$tipo_curso."'>\n");

  if ('E' == $tipo_curso)
  {
    echo("    <input type=hidden name=data_inicio value='".$data_inicio."'>\n");
    echo("    <input type=hidden name=data_fim value='".$data_fim."'>\n");
  }

  echo("    <input type=hidden name=cod_ferramenta value=".$cod_ferramenta.">\n");
  echo("    <input type=hidden name=sucesso value=".$sucesso.">\n");
  echo("  </form>\n");

  echo("</body>\n");
  echo("</html>\n");

?>
