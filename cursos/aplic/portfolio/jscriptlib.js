var isNav = (navigator.appName.indexOf("Netscape") !=-1);
var isMinNS6 = ((navigator.userAgent.indexOf("Gecko") != -1) && (isNav));
var isIE = (navigator.appName.indexOf("Microsoft") !=-1);
var Xpos, Ypos;
var js_cod_item=cod_item, js_cod_topico;
var js_nome_topico;
var js_tipo_item;
var js_conta_arq=0;
var mostrando=0;
var editando=0;
var js_comp = new Array();
var editaTitulo=0;
var editaTexto=0;
var conteudo="";
var input=0;
var cancelarElemento=null;
var cancelarTodos=0;
var lista_frases=new Array();
var lista_frases_geral=new Array();
var cod_avaliacao="";
var valor_radios = new Array();

function OpenWindowPerfil(id)
{
  window.open("../perfil/exibir_perfis.php?cod_curso="+cod_curso+"&cod_aluno[]="+id,"PerfilDisplay","width=600,height=400,top=120,left=120,scrollbars=yes,status=yes,toolbar=no,menubar=no,resizable=yes");
  return(false);
 }

function AbreJanelaComponentes(id)
{
  window.open("../grupos/exibir_grupo.php?cod_curso="+cod_curso+"&cod_grupo="+id,"GruposDisplay","width=700,height=400,top=120,left=120,scrollbars=yes,status=yes,toolbar=no,menubar=no,resizable=yes");
  return(false);
}


if (isNav)
{
  document.captureEvents(Event.MOUSEMOVE);
}
document.onmousemove = TrataMouse;

function TrataMouse(e)
{
  Ypos = (isMinNS4) ? e.pageY : event.clientY;
  Xpos = (isMinNS4) ? e.pageX : event.clientX;
}

function getPageScrollY()
{
  if (isNav)
    return(window.pageYOffset);
  if (isIE)
    return(document.body.scrollTop);
}

function AjustePosMenuIE()
{
  if (isIE)
    return(getPageScrollY());
  else
    return(0);
}

function EscondeLayers()
{
  hideLayer(cod_comp);
  hideLayer(cod_topicos);
  hideLayer(cod_mover);
  hideLayer(cod_novapasta);
  hideLayer(cod_mover_arquivo);
}

function MostraLayer(cod_layer, ajuste)
{
  CancelaTodos();
  EscondeLayers();
  moveLayerTo(cod_layer,Xpos-ajuste,Ypos+AjustePosMenuIE());
  mostrando=1;
  showLayer(cod_layer);
}

function EscondeLayer(cod_layer)
{
  hideLayer(cod_layer);
  mostrando=0;
}

function AtualizaComp(js_tipo_comp)
{
  if ((isNav) && (!isMinNS6)) {
    document.comp.document.form_comp.tipo_comp.value=js_tipo_comp;
    document.comp.document.form_comp.cod_item.value=js_cod_item;
    var tipo_comp = new Array(document.comp.document.getElementById('tipo_comp_T'), document.comp.document.getElementById('tipo_comp_F'), document.comp.document.getElementById('tipo_comp_P'));
  } else {
    document.form_comp.tipo_comp.value=js_tipo_comp;
    document.form_comp.cod_item.value=js_cod_item;
    var tipo_comp = new Array(document.getElementById('tipo_comp_T'), document.getElementById('tipo_comp_F'), document.getElementById('tipo_comp_P'));
  }
  var imagem="<img src='../imgs/checkmark_blue.gif'>"
  if (js_tipo_comp=='T') {
    tipo_comp[0].innerHTML=imagem;
    tipo_comp[1].innerHTML="&nbsp;";
    tipo_comp[2].innerHTML="&nbsp;";
  } else if (js_tipo_comp=='F') {
    tipo_comp[0].innerHTML="&nbsp;";
    tipo_comp[1].innerHTML=imagem;
    tipo_comp[2].innerHTML="&nbsp;";
  } else{
    tipo_comp[0].innerHTML="&nbsp;";
    tipo_comp[1].innerHTML="&nbsp;";
    tipo_comp[2].innerHTML=imagem;
  }

  xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_ant);
}

function MoverItem(link,cod_destino)
{ 
  xajax_MoverItensDinamic(cod_curso, cod_usuario, cod_topico_ant, cod_destino, null, cod_item);
  xajax_AcabaEdicaoDinamic(cod_curso, cod_item, cod_usuario, 1);

  if (js_tipo_item=='item')
  {
    window.location='ver.php?cod_curso='+cod_curso+'&cod_item='+cod_item+'&cod_topico_raiz='+cod_destino+'&cod_topico_ant='+cod_topico_ant+'&cod_usuario_portfolio='+cod_usuario_portfolio+'&cod_grupo_portfolio='+cod_grupo_portfolio+'&acao=mover&atualizacao=true';
    return true;
  }
  else
  {

    return false;

  }
}


function WindowOpenVerURL(end)
{
  window.open(end,'PortfolioURL','top=50,left=100,width=600,height=400,menubar=yes,status=yes,toolbar=yes,scrollbars=yes,resizable=yes');
}

function EdicaoTexto(codigo, id, valor){

  if (valor=='ok'){
	  eval('conteudo = CKEDITOR.instances.'+id+'_text'+'.getData();');
      xajax_EditarTexto(cod_curso, codigo, conteudo, cod_usuario, lista_frases.msg49);
    }
  else{
      //Cancela Edi�o
      if (!cancelarTodos)
        xajax_AcabaEdicaoDinamic(cod_curso, cod_item, cod_usuario, 0);
  }
  document.getElementById(id).innerHTML=conteudo;
  editaTexto=0;
  cancelarElemento=null;
}

var controle=0;

function EdicaoTitulo(codigo, id, valor){
  if ((valor=='ok')&&(document.getElementById(id+'_text').value!="")){
    conteudo = document.getElementById(id+'_text').value;
    xajax_EditarTitulo(cod_curso, codigo, conteudo, cod_usuario, lista_frases.msg196);
  }else{
    /* 36 - O titulo nao pode ser vazio. */
    if ((valor=='ok')&&(document.getElementById(id+'_text').value==""))
      alert(lista_frases.msg36);

    document.getElementById(id).innerHTML=conteudo;

    if(navigator.appName.match("Opera")){
      document.getElementById('renomear_'+codigo).onclick = AlteraTitulo(codigo);
    }else{
      document.getElementById('renomear_'+codigo).onclick = function(){ AlteraTitulo(codigo); };
    }

    //Cancela Edição
    if (!cancelarTodos)
      xajax_AcabaEdicaoDinamic(cod_curso, cod_item, cod_usuario, 0);
  }
  editaTitulo=0;
  cancelarElemento=null;
}


function LimparTexto(id)
{
  if (confirm(lista_frases.msg188))
    {
       xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_ant);
       document.getElementById('text_'+id).innerHTML='';
       xajax_EditarTexto(cod_curso,cod_item,'',cod_usuario, lista_frases.msg208);
    }
}

function AlteraTexto(id){

  if (editaTexto==0){
    CancelaTodos();

    xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_ant);
    conteudo = document.getElementById('text_'+id).innerHTML;
    writeRichTextOnJS('text_'+id+'_text', conteudo, 520, 200, true, false, id);
    startList();
    //document.getElementById('text_'+id+'_text').focus();
    cancelarElemento=document.getElementById('CancelaEdita');
    editaTexto++;
  }
}

function ArquivoValido(path)
{
	var file = getfilename(path);
	var vet  = file.match(/^[A-Za-z0-9-\.\_\ ]+/);

	// Usando expressão regular para identificar caracteres inválidos
	if ((file.length == 0) || (vet == null) || (file.length != vet[0].length))
		return false;
	return true;
}

function getfilename(path)
{
  pieces=path.split('\\');
  n=pieces.length;
  file=pieces[n-1];
  pieces=file.split('/');
  n=pieces.length;
  file=pieces[n-1];
  return(file);
}

function EdicaoArq(i, msg){
  var filename = document.getElementById('input_files').value;
  filename = filename.replace("C:\\fakepath\\", "");
  if ((i==1) && ArquivoValido(filename)) { //OK
    document.formFiles.submit();
  }
  else {
    alert(lista_frases.msg216);
    document.getElementById('input_files').style.visibility='hidden';
    document.getElementById('input_files').value='';
    document.getElementById('divArquivo').className='';
    document.getElementById('divArquivoEdit').className='divHidden';
    //Cancela Edição
    if (!cancelarTodos)
      xajax_AcabaEdicaoDinamic(cod_curso, cod_item, cod_usuario, 0);
    input=0;
    cancelarElemento=null;
  }
}

function AcrescentarBarraFile(apaga){
    if (input==1) return;
    CancelaTodos();
    document.getElementById('input_files').style.visibility='visible';
    document.getElementById('divArquivoEdit').className='';
    document.getElementById('divArquivo').className='divHidden';
    xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_ant);

    cancelarElemento=document.getElementById('cancFile');
}

  function AdicionaInputEndereco(){
    CancelaTodos();
    document.getElementById('novoEnd').style.visibility='visible';
    document.getElementById('novoNomeEnd').style.visibility='visible';
    document.getElementById('divEndereco').className='divHidden';
    document.getElementById('divEnderecoEdit').className='';
    xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_ant);
    cancelarElemento=document.getElementById('cancelaEnd');
  }

function EditaEndereco(opt){
    if (opt){
      if (document.getElementById('novoEnd').value==''){
        xajax_AcabaEdicaoDinamic(cod_curso, cod_item, cod_usuario, 0);
        alert(lista_frases.msg64);
        return false;
      }
      xajax_InsereEnderecoDinamic(document.getElementById('novoNomeEnd').value, document.getElementById('novoEnd').value, cod_item, cod_curso, cod_usuario, lista_frases.msg67);
    }else{
      if (!cancelarTodos)
        xajax_AcabaEdicaoDinamic(cod_curso, cod_item, cod_usuario, 0);
    }

    document.getElementById('novoEnd').style.visibility='hidden';
    document.getElementById('novoNomeEnd').style.visibility='hidden';
    document.getElementById('novoEnd').value='';
    document.getElementById('novoNomeEnd').value='';
    document.getElementById('divEnderecoEdit').className='divHidden';
    document.getElementById('divEndereco').className='';

    cancelarElemento=null;
  }

function CancelaTodos(){
    EscondeLayers();
    cancelarTodos=1;
    if(cancelarElemento) { 
      cancelarElemento.onclick(); 
      xajax_AcabaEdicaoDinamic(cod_curso, cod_item, cod_usuario, 0);
    }
    cancelarTodos=0;
  }

function ApagarEndereco(cod_curso, cod_endereco){
  CancelaTodos();
  if (confirm(lista_frases.msg32)){
    xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_ant);
    xajax_ExcluirEndereco(cod_curso, cod_endereco, cod_item, cod_usuario, lista_frases.msg197);
  }
}

function Descompactar(){
  checks = document.getElementsByName('chkArq');
  for (i=0; i<checks.length; i++){
    if(checks[i].checked){
      getNumber=checks[i].id.split("_");
      arqZip=document.getElementById('nomeArq_'+getNumber[1]).getAttribute('arqZip');
      if (confirm(lista_frases.msg33+'\n'+lista_frases.msg34+'\n'+lista_frases.msg35)){
        xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_ant);
        window.location='acoes.php?cod_curso='+cod_curso+'&cod_item='+cod_item+'&cod_topico_raiz='+cod_topico_ant+'&cod_usuario_portfolio='+cod_usuario_portfolio+'&acao=descompactar&arq='+arqZip;
      }
    } 
  }

}

function Apagar(){
  checks = document.getElementsByName('chkArq');
  if (confirm(lista_frases.msg210)){
    xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_raiz);
    for (i=0; i<checks.length; i++){
      if(checks[i].checked){
        getNumber=checks[i].id.split("_");
        nomeArq = document.getElementById("nomeArq_"+getNumber[1]).getAttribute('nomeArq');
        xajax_ExcluirArquivo(getNumber[1], nomeArq, cod_curso, cod_item, cod_usuario, lista_frases.msg198);
        js_conta_arq--;
      }
    }
    LimpaBarraArq();
    VerificaChkBox(0);
  }
}

function Ocultar(){
  checks = document.getElementsByName('chkArq');
  j=0;
  var nomesArqs = new Array();

  for (i=0; i<checks.length; i++){
    if(checks[i].checked){

      getNumber=checks[i].id.split("_");
      if ((document.getElementById("nomeArq_"+getNumber[1]).getAttribute('arqOculto'))=='nao'){
        nomesArqs[j] = new Array();
  
        nomeArq = document.getElementById("nomeArq_"+getNumber[1]).getAttribute('nomeArq');
        nomesArqs[j][0]=nomeArq;
        nomesArqs[j][1]=getNumber[1];
        j++;
      }

    }
  }

  xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_ant);
  xajax_OcultarArquivosDinamic(nomesArqs, lista_frases.msg118, cod_curso, cod_item, cod_usuario, lista_frases.msg199);
}

function Desocultar(){
  checks = document.getElementsByName('chkArq');
  j=0;
  var nomesArqs = new Array();

  for (i=0; i<checks.length; i++){
    if(checks[i].checked){
      getNumber=checks[i].id.split("_");
      if ((document.getElementById("nomeArq_"+getNumber[1]).getAttribute('arqOculto'))=='sim'){

        nomesArqs[j] = new Array();
        nomeArq = document.getElementById("nomeArq_"+getNumber[1]).getAttribute('nomeArq');
        nomesArqs[j][0]=nomeArq;
        nomesArqs[j][1]=getNumber[1];
        j++;
      }
    }
  }
  xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_ant);
  xajax_DesocultarArquivosDinamic(nomesArqs, cod_curso, cod_item, cod_usuario, lista_frases.msg200);
}

function CheckTodos(){
  var e;
  var i;
  var CabMarcado = document.getElementById('checkMenu').checked;
  var checks=document.getElementsByName('chkArq');
  for(i = 0; i < checks.length; i++)
  {
    e = checks[i];
    e.checked = CabMarcado;
  }

  VerificaChkBox(0);
}

function Mover(caminhoDestino){

  checks = document.getElementsByName('chkArq');
  for (i=0; i<checks.length; i++){
    if(checks[i].checked){
      numeroArq= checks[i].getAttribute('id');
      numeroArq = numeroArq.split('_');
      IdArquivo = 'nomeArq_'+numeroArq[1];
      caminhoOrigem = document.getElementById(IdArquivo).getAttribute('nomeArq');
      xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_ant);
      xajax_MoverArquivosDinamic(caminhoOrigem, caminhoDestino, cod_curso, cod_item, cod_usuario, lista_frases_geral.msg_ger63);
    }
  }

}

function ApagarItem(){
  CancelaTodos();
  if (confirm(lista_frases.msg18+'\n'+lista_frases.msg179)){
        window.location='acoes.php?cod_curso='+cod_curso+'&cod_item='+cod_item+'&cod_topico_raiz='+cod_topico_ant+'&cod_usuario_portfolio='+cod_usuario_portfolio+'&acao=apagarItem';
  }
}

function LimpaBarraArq(){

  lista = document.getElementById("listFiles");
  if (!js_conta_arq){
    pai_lista=lista.parentNode;
    pai_lista2=pai_lista.parentNode;
    i=3;
    do{
      if (pai_lista.firstChild)
        pai_lista.removeChild(pai_lista.firstChild);
      i--;
    }while(i>0);

  }

  document.getElementById('checkMenu').checked=false;
  CheckTodos();
}

function AssociarAvaliacao(){
  CancelaTodos();
  radios=document.getElementsByName('cod_avaliacao');
  for (i=0; i<radios.length; i++){
    valor_radios[i]=radios[i].checked;
  }
  document.getElementById('tableAvaliacao').style.visibility='visible';
  document.getElementById('divAvaliacao').className='divHidden';
  document.getElementById('divAvaliacaoEdit').className='';

  xajax_AbreEdicao(cod_curso, cod_item, cod_usuario, cod_usuario_portfolio, cod_grupo_portfolio, cod_topico_ant);

  cancelarElemento=document.getElementById('cancAval');

}

function EditaAval(opt){
  //document.getElementById('tableAvaliacao').style.visibility='hidden';
  document.getElementById('divAvaliacao').className='';
  //document.getElementById('divAvaliacaoEdit').className='divHidden';
  if (opt){
    xajax_AssociaAvaliacaoDinamic(cod_curso, cod_usuario, cod_item, cod_avaliacao, lista_frases.msg217, lista_frases.msg212, lista_frases.msg220);
  }else{
    radios = document.getElementsByName('cod_avaliacao');
    for (i=0; i<radios.length; i++){
      radios[i].checked=valor_radios[i].checked;
    }
    if(!cancelarTodos)
      xajax_AcabaEdicaoDinamic(cod_curso, cod_item, cod_usuario, 0);
  }

  cancelarElemento=null;
}

function DesmarcaRadios(){
  radios = document.getElementsByName('cod_avaliacao');
  for (i=0; i<radios.length; i++){
    radios[i].checked=false;
  }
  cod_avaliacao='';
}

