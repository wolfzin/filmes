<?php

require_once(dirname(__FILE__)."/autoload.php");
protegeArquivo(basename(__FILE__));

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

abstract class banco
{
    // =========================================================
    // CONFIGURAÇÕES
    // =========================================================

    public $servidor = DBHOST;
    public $usuario = DBUSER;
    public $senha = DBPASS;
    public $nomebanco = DBNAME;

    // Conexão compartilhada entre os objetos
    protected static $conexaoCompartilhada = NULL;

    public $conexao = NULL;
    public $dataset = NULL;
    public $linhasafetadas = -1;


    // =========================================================
    // CONSTRUTOR
    // =========================================================

    public function __construct()
    {
        $this->conecta();
    }


    // =========================================================
    // DESTRUTOR
    // =========================================================

    public function __destruct()
    {
        /*
         * NÃO fechar a conexão aqui.
         *
         * Como a conexão é compartilhada, outro objeto pode
         * ainda estar utilizando a mesma conexão.
         */
    }


    // =========================================================
    // CONEXÃO
    // =========================================================

    public function conecta()
    {
        // Se já existe uma conexão válida, reutiliza
        if (
            self::$conexaoCompartilhada !== NULL &&
            mysqli_ping(self::$conexaoCompartilhada)
        ) {
            $this->conexao = self::$conexaoCompartilhada;
            return $this->conexao;
        }


        // Cria uma nova conexão somente quando necessário
        self::$conexaoCompartilhada = mysqli_connect(
            $this->servidor,
            $this->usuario,
            $this->senha,
            $this->nomebanco
        );


        // Verifica erro
        if (!self::$conexaoCompartilhada) {

            $erro = mysqli_connect_error();
            $codigo = mysqli_connect_errno();

            die(
                "<strong>Erro ao conectar ao MySQL</strong><br><br>" .
                "<strong>Código:</strong> " . $codigo . "<br>" .
                "<strong>Mensagem:</strong> " . $erro . "<br>"
            );
        }


        // Define charset
        mysqli_set_charset(
            self::$conexaoCompartilhada,
            "utf8mb4"
        );


        // Guarda a conexão no objeto
        $this->conexao = self::$conexaoCompartilhada;

        return $this->conexao;
    }


    // =========================================================
    // INSERIR
    // =========================================================

    public function inserir($objeto)
    {
        $sql = "INSERT INTO ".$objeto->tabela." (";

        for ($i = 0; $i < count($objeto->campos_valores); $i++) {

            $sql .= key($objeto->campos_valores);

            if ($i < (count($objeto->campos_valores) - 1)) {
                $sql .= ", ";
            } else {
                $sql .= ")";
            }

            next($objeto->campos_valores);
        }

        reset($objeto->campos_valores);

        $sql .= " VALUES (";

        for ($i = 0; $i < count($objeto->campos_valores); $i++) {

            $valor = $objeto->campos_valores[
                key($objeto->campos_valores)
            ];

            $sql .= is_numeric($valor)
                ? $valor
                : "'" . $valor . "'";

            if ($i < (count($objeto->campos_valores) - 1)) {
                $sql .= ", ";
            } else {
                $sql .= ")";
            }

            next($objeto->campos_valores);
        }

        return $this->executaSQL($sql);
    }


    // =========================================================
    // ATUALIZAR
    // =========================================================

    public function atualizar($objeto)
    {
        $sql = "UPDATE ".$objeto->tabela." SET ";

        for ($i = 0; $i < count($objeto->campos_valores); $i++) {

            $sql .= key($objeto->campos_valores) . "=";

            $valor = $objeto->campos_valores[
                key($objeto->campos_valores)
            ];

            $sql .= is_numeric($valor)
                ? $valor
                : "'" . $valor . "'";

            if ($i < (count($objeto->campos_valores) - 1)) {
                $sql .= ", ";
            } else {
                $sql .= " ";
            }

            next($objeto->campos_valores);
        }

        $sql .= "WHERE ".$objeto->campopk."=";

        $sql .= is_numeric($objeto->valorpk)
            ? $objeto->valorpk
            : "'" . $objeto->valorpk . "'";

        return $this->executaSQL($sql);
    }


    // =========================================================
    // DELETAR
    // =========================================================

    public function deletar($objeto)
    {
        $sql = "DELETE FROM ".$objeto->tabela;

        $sql .= " WHERE ".$objeto->campopk."=";

        $sql .= is_numeric($objeto->valorpk)
            ? $objeto->valorpk
            : "'" . $objeto->valorpk . "'";

        return $this->executaSQL($sql);
    }


    // =========================================================
    // SELECIONAR TUDO
    // =========================================================

    public function selecionaTudo($objeto)
    {
        $sql = "SELECT * FROM ".$objeto->tabela;

        if ($objeto->extras_select != NULL) {
            $sql .= " ".$objeto->extras_select;
        }

        return $this->executaSQL($sql);
    }


    // =========================================================
    // SELECIONAR CAMPOS
    // =========================================================

    public function selecionaCampos($objeto)
    {
        $sql = "SELECT ";

        for ($i = 0; $i < count($objeto->campos_valores); $i++) {

            $sql .= key($objeto->campos_valores);

            if ($i < (count($objeto->campos_valores) - 1)) {
                $sql .= ", ";
            } else {
                $sql .= " ";
            }

            next($objeto->campos_valores);
        }

        reset($objeto->campos_valores);

        $sql .= " FROM ".$objeto->tabela;

        if ($objeto->extras_select != NULL) {
            $sql .= " ".$objeto->extras_select;
        }

        return $this->executaSQL($sql);
    }


    // =========================================================
    // EXECUTAR SQL
    // =========================================================

    public function executaSQL($sql = NULL)
    {
        if ($sql == NULL) {

            $this->trataerro(
                __FILE__,
                __FUNCTION__,
                NULL,
                'Comando SQL nao informado na rotina',
                false
            );

            return false;
        }


        // Garante que existe conexão
        if ($this->conexao == NULL) {
            $this->conecta();
        }


        $query = mysqli_query(
            $this->conexao,
            $sql
        );


        // Trata erro SQL
        if ($query === false) {

            $this->trataerro(
                __FILE__,
                __FUNCTION__,
                mysqli_errno($this->conexao),
                mysqli_error($this->conexao),
                false
            );

            return false;
        }


        $this->linhasafetadas = mysqli_affected_rows(
            $this->conexao
        );


        if (
            substr(
                trim(strtolower($sql)),
                0,
                6
            ) == 'select'
        ) {

            $this->dataset = $query;

            return $query;

        } else {

            return $this->linhasafetadas;
        }
    }


    // =========================================================
    // RETORNAR DADOS
    // =========================================================

    public function retornaDados($tipo = NULL)
    {
        switch (strtolower($tipo)) {

            case "array":
                return mysqli_fetch_array($this->dataset);
                break;

            case "assoc":
                return mysqli_fetch_assoc($this->dataset);
                break;

            case "object":
                return mysqli_fetch_object($this->dataset);
                break;

            default:
                return mysqli_fetch_object($this->dataset);
                break;
        }
    }


    // =========================================================
    // TRATAR ERRO
    // =========================================================

    public function trataerro(
        $arquivo = NULL,
        $rotina = NULL,
        $numerro = NULL,
        $msgerro = NULL,
        $geraexcept = false
    ) {

        if ($arquivo == NULL) {
            $arquivo = "nao informado";
        }

        if ($rotina == NULL) {
            $rotina = "nao informada";
        }

        if ($numerro == NULL) {

            if ($this->conexao) {
                $numerro = mysqli_errno($this->conexao);
            } else {
                $numerro = mysqli_connect_errno();
            }
        }

        if ($msgerro == NULL) {

            if ($this->conexao) {
                $msgerro = mysqli_error($this->conexao);
            } else {
                $msgerro = mysqli_connect_error();
            }
        }


        $resultado =
            'Ocorreu um erro com os seguintes detalhes:<br/>
            <b>Arquivo:</b> '.$arquivo.'<br/>
            <b>Rotina:</b> '.$rotina.'<br/>
            <b>Código:</b> '.$numerro.'<br/>
            <b>Mensagem:</b> '.$msgerro;


        if ($geraexcept == false) {

            echo $resultado;

        } else {

            die($resultado);
        }
    }
}
?>