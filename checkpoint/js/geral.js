    $(document).ready(function() {
      // Adicione campos extras
      $(".add-campo").click(function(e) {
        e.preventDefault();
        var max_fields = 10; // Máximo de campos extras
        var wrapper = $(this).closest('.col.s12').find('.input-extra'); // Wrapper para os campos extras
        if (wrapper.children().length < max_fields) {
          wrapper.append('<div class="col s4 input-field"><input type="text" name="extra[]"><label>Detalhes extras</label><a href="#" class="remove-field">Remove</a></div>');
        }
      });

      // Remova campos extras
      $(".input-extra").on("click", ".remove-field", function(e) {
        e.preventDefault();
        $(this).parent('div').remove();
      });

      // Adicione opções
     $("#add-campo2").click(function(e) {
        e.preventDefault();
        var max_fields2 = 10; // Máximo de campos extras
        var wrapper2 = $(this).closest('.col.s12').find('#input-opcao'); // Wrapper2 para os campos extras
        if (wrapper2.children().length < max_fields2) {
          wrapper2.append('<div class="remove"><div class="col s4"><label>Opções</label><select name="tipo_opcao[]"><option value="1">Texto</option><option value="5">Data</option><option value="2">Upload</option><option value="3">Cor</option></select></div><div class="col s8 input-field"><input type="text" id="nome_opcao" name="valor_opcao[]"><label for="nome_opcao">Valor</label></div><a href="#" class="remove-field2">Remove</a></div>');
        }
      });

      // Remova opções
      $("#input-opcao").on("click", ".remove_field2", function(e) {
        e.preventDefault();
        $(this).parent('div.remove').remove();
      });
    });


//contador parceiro
$(document).ready(function() {
  var max_fields      = 10; //maximum input boxes allowed
  var wrapper_abas       = $(".input_fields_wrap_contador"); //Fields wrapper
  var add_button_abas      = $(".add_field_contador_button"); //Add button ID
  
  var x = 1; //initlal text box count
  $(add_button_abas).click(function(e){ //on add input button click
    e.preventDefault();
    if(x < max_fields){ //max input box allowed
      x++; //text box increment
      $(wrapper_abas).append('<div> <input type="text" class="input" name="titulo_contador[]"><input type="text" class="input" name="descricao[]"><input type="text" class="input" name="link[]"><input type="text" class="input" name="titulo_link[]"><a href="#" class="remove_field">Remove</a></div>'); //add input box
    }
  });
  
  $(wrapper_abas).on("click",".remove_field", function(e){ 
    e.preventDefault(); $(this).parent('div').remove(); x--;
  });
});


$('.naoenviar').keypress(function(e) {
    if(e.which == 13) {
      e.preventDefault();
    }
});


function tabs(id){
  $("#tabs .bloco").hide();
  $('#tabs li').removeClass('active');
  var tab = $('.bloco'+id);
  tab.toggle(); 
  $('#tabs li.label-'+id).addClass('active');
}

window.onload=function(){
    //pegar valores get 
  function getUrlVars()
  {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  }


  $("#destaque").on('change', function () {
    if (typeof (FileReader) != "undefined") {
      var image_holder = $(".image");
      image_holder.empty();

      var reader = new FileReader();
      reader.onload = function (e) {
        $("<img />", {
          "src": e.target.result,
          "class": "thumb-image"
        }).appendTo(image_holder);
      }
      image_holder.show();
      reader.readAsDataURL($(this)[0].files[0]);
    } else{
      alert("Este navegador nao suporta FileReader.");
    }
  });
};

//verificar se usuario esta logado
function verificaLogin(){
  var usuario = sessionStorage.getItem('login');
  if(!usuario){
    window.location.assign("/painel/index.php");
  }
}

function logoff(){
  var usuario = sessionStorage.getItem('login');
  if(usuario){
    var usuario = sessionStorage.removeItem('login');
    window.location.assign("/painel/index.php");
  }
}

function saveOk(){
  setTimeout(function(){ 
   $("#aviso").fadeTo(200, 1); 
 }, 800);

  setTimeout(function(){ 
    $("#aviso").fadeTo(600, 0); 

  }, 8000);

}




function duplicarCampos(){
  var clone = document.getElementById('origem').cloneNode(true);
  var destino = document.getElementById('destino');
  destino.appendChild (clone);
  
  var camposClonados = clone.getElementsByTagName('input');
  
  for(i=0; i<camposClonados.length;i++){
    camposClonados[i].value = '';
  }
  
  
  
}

function removerCampos(id){
  var node1 = document.getElementById('destino');
  node1.removeChild(node1.childNodes[0]);
}


function recuperarSenha(){
  $('#telaRecuperarSenha').show();
}

// ATIVA O SELECT COM SEARCH
$(document).ready(function(){
  $('.select2').select2({
    placeholder: "Selecione...",
    allowClear: true
  });
});




// MASCARA PARA INPUTS
$(document).ready(function(){
  $('.maskCPF').mask('000.000.000-00', {reverse: true});
  $('.maskData').mask('00-00-0000');
  $('.maskTelefone').mask('(00) 00000-0000');

});


//CONFIRMAR INATIVAR ALUNO
function confirmarExclusao(id, destino) {
  var confirmacao = confirm("Você deseja excluir");

  if (confirmacao == true) {
    window.location.href = destino;
  } else {
    return false;
  }
}

//ADICIONANDO E ATIVANDO O EDITOR DE TEXTO QUILL
  var toolbarOptions = [    ['bold', 'italic', 'underline', 'strike'],          // Opções de formatação de texto
    
   // [{ 'font': [] }, { 'size': [] }],                   // Opções de fonte e tamanho do texto
   // [{ 'align': [] }],                                  // Opções de alinhamento do texto
    ['image', 'video', 'link'],                         // Opções de mídia
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],      // Opções de lista ordenada e não ordenada
    [{ 'indent': '-1' }, { 'indent': '+1' }],           // Opções de recuo do texto
    ['blockquote', 'code-block'],                       // Opções de bloco de citação e código
    ['clean']                                           // Limpar formatação
    ];

  var editor = new Quill('#editor', {
    modules: {
      toolbar: toolbarOptions                            // Opções da barra de ferramentas
    },
    theme: 'snow'                                         // Tema do editor
  });


  

//ENVIANDO O CONTEÚDO PARA O INPUT NO FORMULARIO
  var formulario = document.querySelector('form');
  formulario.addEventListener('submit', function(evento) {
    // Obtenha o conteúdo do editor Quill
    var conteudoQuill = document.getElementById('editor').querySelector('.ql-editor').innerHTML;

    // Atualize o valor do campo de entrada oculto
    var campoConteudoQuill = document.getElementById('conteudo-quill');
    campoConteudoQuill.value = conteudoQuill;
  });
  
  
  function verificarSenha() {
            // Obtém os valores dos campos de senha e confirmação de senha
            var senha = document.getElementById('senha').value;
            var confirmacaoSenha = document.getElementById('confirmacaoSenha').value;

            // Verifica se a senha é igual à confirmação de senha
            if (senha === confirmacaoSenha) {
            } else {
                alert('As senhas não coincidem. Tente novamente.');
            }
        }

