
<?php
include("conexao.php");

$sql = "SELECT * FROM filmes";
$resultado = $conn->$query($sql);

while($filme = $resultado->fetch_assoc()){
  echo $filme['titulo'];
}