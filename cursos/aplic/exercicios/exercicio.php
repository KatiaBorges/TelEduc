<?php
/*
<!--
-------------------------------------------------------------------------------

    Arquivo : cursos/aplic/exercicios/exercicio.php

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
  ARQUIVO : cursos/aplic/exercicios/exercicio.php
  ========================================================== */

  $bibliotecas="../bibliotecas/";
  include($bibliotecas."geral.inc");
  include("exercicios.inc");

  $cod_ferramenta = 23;
  $agrupamento = $_GET['agrupar'];
  $visualizar = $_GET['visualizar'];
  
  if($visualizar != "I" && $visualizar != "G")
    $visualizar = "I";
    
  if($visualizar == "I")
  {
    if($agrupamento != "A" && $agrupamento != "T")
    {
      $agrupamento = "A";
    }
  }
  else
  {
    if($agrupamento != "G" && $agrupamento != "T")
    {
      $agrupamento = "G";
    }
  }
  

  include("../topo_tela.php");
  
  // aplica ao usuario exercicios aplicados pelos formadores e que ainda nao constam para o mesmo.
  AplicaExerciciosAoUsuario($sock,$cod_curso,$cod_usuario);
  
  $titulos = RetornaListaNomesOuTitulos($sock,$cod_curso,$cod_usuario,$tela_formador,$agrupamento,$visualizar);
  $ex_num = RetornaNumExercicios($sock, $visualizar);
  $ex_entregues = RetornaNumExerciciosEntregues($sock,$cod_usuario,$tela_formador,$agrupamento,$visualizar);
  $ex_corrigidos = RetornaNumExerciciosCorrigidos($sock,$cod_usuario,$tela_formador,$agrupamento,$visualizar);
	
  $total_ex_entregues = count($ex_entregues);
  $total_ex_corrigidos = count($ex_corrigidos);
  $total_titulos = count($titulos);
  
  	
  /* Coloca o numero de exercicios entregues e tambem corridos
   * No array titulos, que contem o nome dos usuarios/ grupos.
   * Assim na hora de exibicao podemos fazer a conta com o 
   * numero total de exercicios.
   */
  $i_titulos = 0;
  $i_ex_entregues = 0;
  $i_ex_corrigidos = 0;
  	
  while ($i_titulos < $total_titulos){
  
  	if ($titulos[$i_titulos]['cod'] != $ex_entregues[$i_ex_entregues]['cod']){
  		$titulos[$i_titulos]['entregues'] = 0;
  	} else {
  		$titulos[$i_titulos]['entregues'] = $ex_entregues[$i_ex_entregues]['num'];
  		$i_ex_entregues++;
  	}
  	
  	if ($titulos[$i_titulos]['cod'] != $ex_corrigidos[$i_ex_corrigidos]['cod']){
  		$titulos[$i_titulos]['corrigidos'] = 0;
  	} else {
  		$titulos[$i_titulos]['corrigidos'] = $ex_corrigidos[$i_ex_corrigidos]['num'];
  		$i_ex_corrigidos++;
  	}
  	
  	$i_titulos++;
  }
  
  /*********************************************************/
  /* in�io - JavaScript */
  echo("  <script  type=\"text/javascript\" language=\"JavaScript\" src=\"../bibliotecas/dhtmllib.js\"></script>\n");
  echo("  <script  type=\"text/javascript\" src=\"jscriptlib.js\"> </script>\n");
  echo("  <script  type=\"text/javascript\" language=\"JavaScript\">\n\n");
  
  echo("    var lay_agrupar;");
  
  /* Iniciliza os layers. */
  echo("    function Iniciar()\n");
  echo("    {\n");
  echo("      lay_agrupar = getLayer('layer_agrupar');\n");
  echo("      startList();\n");
  echo("    }\n\n");
  
  echo("    function EscondeLayers()\n");
  echo("    {\n");
  echo("      hideLayer(lay_agrupar);\n");
  echo("    }\n");

  echo("    function MostraLayer(cod_layer, ajuste)\n");
  echo("    {\n");
  echo("      EscondeLayers();\n");
  echo("      moveLayerTo(cod_layer,Xpos-ajuste,Ypos+AjustePosMenuIE());\n");
  echo("      showLayer(cod_layer);\n");
  echo("    }\n");

  echo("    function EscondeLayer(cod_layer)\n");
  echo("    {\n");
  echo("      hideLayer(cod_layer);\n");
  echo("    }\n");
  
  echo("    function MudaAgrupamento(novo_agrupamento)\n");
  echo("    {\n");
  echo("      window.location='exercicio.php?cod_curso=".$cod_curso."&visualizar=".$visualizar."&agrupar='+novo_agrupamento;\n");
  echo("    }\n");
  
  echo("  </script>\n\n");
  /* fim - JavaScript */
  /*********************************************************/

  include("../menu_principal.php");

  echo("        <td width=\"100%\" valign=\"top\" id=\"conteudo\">\n");

  if ($tela_formador)
  {
    if($visualizar == "I")
    {
	  	/* Frase #1 - Exercicios */
    	/* Frase #107 - Exercicios Individuais Disponiveis */
  	  $frase = RetornaFraseDaLista($lista_frases, 1)." - ".RetornaFraseDaLista($lista_frases, 107);
    }
    else if($visualizar == "G")
    {
	  	/* Frase #1 - Exercicios */ 
	  	/* Frase #108 -  Exercicios em Grupo Disponiveis */
  	  $frase = RetornaFraseDaLista($lista_frases, 1)." - ".RetornaFraseDaLista($lista_frases, 108);
    }
   
	echo("          <h4>".$frase."</h4>\n");
	
  	/* Frase #5 - Voltar */
  	echo("          <span class=\"btsNav\" onclick=\"javascript:history.back(-1);\"><img src=\"../imgs/btVoltar.gif\" border=\"0\" alt=\"".RetornaFraseDaLista($lista_frases, 5)."\" /></span>\n");

  	echo("          <div id=\"mudarFonte\">\n");
  	echo("            <a onclick=\"mudafonte(2)\" href=\"#\"><img width=\"17\" height=\"15\" border=\"0\" align=\"right\" alt=\"Letra tamanho 3\" src=\"../imgs/btFont1.gif\"/></a>\n");
  	echo("            <a onclick=\"mudafonte(1)\" href=\"#\"><img width=\"15\" height=\"15\" border=\"0\" align=\"right\" alt=\"Letra tamanho 2\" src=\"../imgs/btFont2.gif\"/></a>\n");
  	echo("            <a onclick=\"mudafonte(0)\" href=\"#\"><img width=\"14\" height=\"15\" border=\"0\" align=\"right\" alt=\"Letra tamanho 1\" src=\"../imgs/btFont3.gif\"/></a>\n");
  	echo("          </div>\n");
  	
	echo("          <table cellpadding=\"0\" cellspacing=\"0\" id=\"tabelaExterna\" class=\"tabExterna\">\n");
	echo("            <tr>\n");
	echo("              <td valign=\"top\">\n");

  	echo("                <ul class=\"btAuxTabs\">\n");
  	
  	/* Frase #109 - Exercicios Individuais */
    echo("                  <li><a href='exercicio.php?cod_curso=".$cod_curso."&visualizar=I&agrupar=A'>".RetornaFraseDaLista($lista_frases, 109)."</a></li>\n");
    
    /* Frase #110 - Exercicios em Grupo */
    echo("                  <li><a href='exercicio.php?cod_curso=".$cod_curso."&visualizar=G&agrupar=G'>".RetornaFraseDaLista($lista_frases, 110)."</a></li>\n");

  	/* Frase #111 - Biblioteca de Exercicios */
    echo("                  <li><a href='exercicios.php?cod_curso=".$cod_curso."&visualizar=E'>".RetornaFraseDaLista($lista_frases, 111)."</a></li>\n");
  	
    /* Frase #112 - Biblioteca de Questoes */
    echo("                  <li><a href='questoes.php?cod_curso=".$cod_curso."&visualizar=Q'>".RetornaFraseDaLista($lista_frases, 112)."</a></li>\n");

  	echo("                </ul>\n");
  	echo("              </td>\n");
  	echo("            </tr>\n");
  	echo("            <tr>\n");
    echo("              <td>\n");
    echo("                <ul class=\"btAuxTabs03\">\n");
    /* Frase #113 - Agrupar */
    echo("                  <li><span onclick=\"MostraLayer(lay_agrupar, 0);\">".RetornaFraseDaLista($lista_frases, 113)."</span></li>\n");
    echo("                </ul>\n");
    echo("              </td>\n");
    echo("            </tr>\n");
  	echo("            <tr>\n");
  	echo("              <td valign=\"top\">\n");
	echo("                <table border=0 width=\"100%\" cellspacing=0 id=\"tabelaInterna\" class=\"tabInterna\">\n");
	echo("                  <tr class=\"head\">\n");
	
	$aux="";
	
    if($agrupamento != "T")	
    {
      if($visualizar == "I")
      {
        /* Frase #114 - de */
        $aux = RetornaFraseDaLista($lista_frases, 114);
      }
      else if($visualizar == "G")
      {
        // ? - do grupo
        /* Frase #115 - do grupo */
        $aux = RetornaFraseDaLista($lista_frases, 115);
      }
    }
    
	/* Frase #1 - Exercicios */
	echo("                    <td class=\"alLeft\">".RetornaFraseDaLista($lista_frases, 1).$aux."</td>\n");
  /* Frase #116 - Exercicios nao entregues */
	echo("                    <td width=\"15%\">".RetornaFraseDaLista($lista_frases, 116)."</td>\n");
  /* Frase #117 - Exercicios nao corrigidos */
	echo("                    <td width=\"15%\">".RetornaFraseDaLista($lista_frases, 117)."</td>\n");
	echo("                  </tr>\n");

	/* Monta a tabela:
	 * Usuario | Exercicios nao entregues | Exercicios nao corrigidos
	 */
	
	$icone = "<img src=\"../imgs/arqp.gif\" alt=\"\" border=\"0\" /> ";
	
	if ($total_titulos){
  	foreach($titulos as $cod => $linha){
  		 
        echo("                  <tr id=\"tr_".$linha['cod']."\">\n");
        echo("                    <td align=\"left\">".$icone."<a href=\"ver_exercicios.php?cod_curso=".$cod_curso."&visualizar=".$visualizar."&cod=".$linha['cod']."\">".$linha['titulo']."</a></td>\n");
        echo("                    <td>".($ex_num - $linha['entregues'])."</td>\n");
        echo("                    <td>".($linha['entregues'] - $linha['corrigidos'])."</td>\n");
        echo("                  </tr>\n");
        
    }
  }
    else
  {
      echo("                  <tr>\n");
      /* Frase #118 - Nao ha nenhum exericio */
      echo("                    <td colspan=\"7\">".RetornaFraseDaLista($lista_frases, 118)."</td>\n");
      echo("                  </tr>\n");
  }

	echo("                </table>\n");
	echo("              </td>\n");
  	echo("            </tr>\n");
  	echo("          </table>\n");
  	/* Frase #5 - Voltar */
    echo("          <span class=\"btsNavBottom\"><a href=\"javascript:history.back(-1);\"><img src=\"../imgs/btVoltar.gif\" border=\"0\" alt=\"".RetornaFraseDaLista($lista_frases, 5)."\" /></a> <a href=\"#topo\"><img src=\"../imgs/btTopo.gif\" border=\"0\" alt=\"Topo\" /></a></span>\n");
  }
  else
  {
    /* Nao Formador */
		/* Frase #1 - Exercicios */
  	/* Frase #74 - Area restrita ao formador. */
  	echo("          <h4>".RetornaFraseDaLista($lista_frases,1)." - ".RetornaFraseDaLista($lista_frases,74)."</h4>\n");
	
        /* Frase #5 - Voltar */
        echo("          <span class=\"btsNav\" onclick=\"javascript:history.back(-1);\"><img src=\"../imgs/btVoltar.gif\" border=\"0\" alt=\"".RetornaFraseDaLista($lista_frases, 5)."\" /></span><br /><br />\n");

        echo("          <div id=\"mudarFonte\">\n");
        echo("            <a onclick=\"mudafonte(2)\" href=\"#\"><img width=\"17\" height=\"15\" border=\"0\" align=\"right\" alt=\"Letra tamanho 3\" src=\"../imgs/btFont1.gif\"/></a>\n");
        echo("            <a onclick=\"mudafonte(1)\" href=\"#\"><img width=\"15\" height=\"15\" border=\"0\" align=\"right\" alt=\"Letra tamanho 2\" src=\"../imgs/btFont2.gif\"/></a>\n");
        echo("            <a onclick=\"mudafonte(0)\" href=\"#\"><img width=\"14\" height=\"15\" border=\"0\" align=\"right\" alt=\"Letra tamanho 1\" src=\"../imgs/btFont3.gif\"/></a>\n");
        echo("          </div>\n");

    	/* 23 - Voltar (gen) */
    	echo("<form><input class=\"input\" type=button value=\"".RetornaFraseDaLista($lista_frases_geral,23)."\" onclick=\"history.go(-1);\" /></form>\n");
  }

  echo("        </td>\n");
  echo("      </tr>\n"); 

  include("../tela2.php");
  
  if($tela_formador)
  {
  	/* Agrupar */
  	echo("    <div id=\"layer_agrupar\" class=popup>\n");
  	echo("      <div class=\"posX\"><span onclick=\"EscondeLayer(lay_agrupar);\"><img src=\"../imgs/btClose.gif\" alt=\"Fechar\" border=\"0\" /></span></div>\n");
  	echo("      <div class=int_popup>\n");
  	echo("        <div class=ulPopup>\n");    
  	/* Frase #119 - Agrupar por: */
  	echo("            Agrupar por:<br />\n");
  	echo("            <select class=\"input\" id=\"agrupamento\">");
  	if($visualizar == "I")
  	  echo("              <option value=\"A\" selected>Aluno</option>");
  	else if($visualizar == "G")
  	  echo("              <option value=\"G\" selected>Grupo</option>");
  	echo("              <option value=\"T\">Titulo do Exercicio</option>");
  	echo("            </select><br /><br />");
  	/* 18 - Ok (gen) */
  	echo("            <input type=\"button\" id=\"ok_agrupar\" class=\"input\" onClick=\"MudaAgrupamento(document.getElementById('agrupamento').value);\" value=\"".RetornaFraseDaLista($lista_frases_geral,18)."\" />\n");
  	/* 2 - Cancelar (gen) */
  	echo("            &nbsp; &nbsp;<input type=\"button\" class=\"input\" onClick=\"EscondeLayer(lay_agrupar);\" value=\"".RetornaFraseDaLista($lista_frases_geral,2)."\" />\n");
  	echo("      </div>\n");
  	echo("    </div>\n");
  	echo("    </div>\n\n");
  }

  echo("  </body>\n");
  echo("</html>\n");

  Desconectar($sock);

?>