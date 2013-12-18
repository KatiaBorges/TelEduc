<?php
/*
<!--
-------------------------------------------------------------------------------

    Arquivo : cursos/aplic/perfil/enviar_foto2.php

    TelEduc - Ambiente de Ensino-Aprendizagem a Dist�cia
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

    Nied - Ncleo de Inform�ica Aplicada �Educa�o
    Unicamp - Universidade Estadual de Campinas
    Cidade Universit�ia "Zeferino Vaz"
    Bloco V da Reitoria - 2o. Piso
    CEP:13083-970 Campinas - SP - Brasil

    http://www.nied.unicamp.br
    nied@unicamp.br

------------------------------------------------------------------------------
-->
*/
/*==========================================================
  ARQUIVO : cursos/aplic/perfil/enviar_foto2.php
  ========================================================== */

  $bibliotecas="../bibliotecas/";
  include($bibliotecas."geral.inc");
  include("perfil.inc");

  $cod_ferramenta=13;
  
  $cod_usuario_global=VerificaAutenticacao($cod_curso);

  $sock=Conectar("");
  $lista_frases=RetornaListaDeFrases($sock,13);
  $lista_frases_geral=RetornaListaDeFrases($sock,-1);

  $diretorio_temp=RetornaDiretorio($sock,"ArquivosWeb");

  Desconectar($sock);

  $sock=Conectar($cod_curso);

  $cod_usuario = RetornaCodigoUsuarioCurso($sock, $cod_usuario_global, $cod_curso);
  VerificaAcessoAoCurso($sock,$cod_curso,$cod_usuario);

  VerificaAcessoAFerramenta($sock,$cod_curso,$cod_usuario,13);

  $arquivo_name = $_FILES['input_files']['name'];

  $pieces=explode(".",$arquivo_name);

  $ext=$pieces[count($pieces)-1];
  $nomefinal="cod_usuario_".$cod_usuario.".".$ext;
  error_log("\$nomefinal = ".$nomefinal);
  if(!AtualizaArquivo($sock,$arquivo,$nomefinal,$diretorio_temp,$cod_curso)){

   if (isset($alunocod))
     $setalunocod="&alunocod=".$alunocod;
   if (isset($colaboradorcod))
     $setcolaboradorcod="&colaboradorcod".$colaboradorcod;
    if (isset($visitantecod))
      $setvisitantecod="&visitantecod=".$visitantecod;
   if (isset($formadorcod))
     $setformadorcod="&formadorcod=".$formadorcod;
   if (isset($coordenadorcod))
     $setcoordenadorcod="&coordenadorcod value=".$coordenadorcod;
    
    Desconectar($sock);
    $atualizacao="false";

  }
  else{

    Desconectar($sock);

    if (isset($alunocod))
      $setalunocod="&alunocod=".$alunocod;
    if (isset($colaboradorcod))
      $setcolaboradorcod="&colaboradorcod".$colaboradorcod;
    if (isset($visitantecod))
      $setvisitantecod="&visitantecod=".$visitantecod;
    if (isset($formadorcod))
      $setformadorcod="&formadorcod=".$formadorcod;
    if (isset($coordenadorcod))
      $setcoordenadorcod="&coordenadorcod value=".$coordenadorcod;
  
    $atualizacao="true";
  }


  header("location:exibir_perfis.php?".$setalunocod.$setcolaboradorcod.$setvisitantecod.$setformadorcod.$setcoordenadorcod."&cod_curso=".$cod_curso."&acao=enviarFoto&atualizacao=".$atualizacao);
?>
