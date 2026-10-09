<?php 
include("funcoes.php");
//verificaLogin();
recuperarSenha('fernando.pufe@gmail.com');
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <title>Painel</title>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  
  <link rel="icon" type="image/png" href="<?= BASEURL ?>/img/favicon.png">
  <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
  <?php 
  loadCSS('materialize');


  loadCSS('style');
  //loadJS('jquery-2.1.4');
  loadJS('jquery-3.6');



  //loadJS('jquery-mask-1.14');
  ?>

  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <script type="text/javascript" src="https://pillarmind.com/admin-assets/plugins/materialNote/js/ckMaterializeOverrides.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
  <script src="https://www.youtube.com/player_api"></script>
  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
      var elems = document.querySelectorAll('.chips');
      var instances = M.Chips.init(elems, options);
    });
  </script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://kit.fontawesome.com/6fa2c6bdb7.js" crossorigin="anonymous"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>
<body>
