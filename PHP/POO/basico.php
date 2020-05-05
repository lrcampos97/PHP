    <?php     
    /* 
        ******************* CLASSES *********************
        static -> Não preciso criar o objeto para utilizar o método, posso invocar apenas ele.

        "::" -> para acessar métodos estáticos

        "__" -> método mágico 
        encapsulamento -> Forma de controlar quem pode acessar determinada informação (public,private,protected,static)
    */
        

    echo "<strong> 1 - CLASSES </strong> <br/>";

    class Pessoa {

        public $nome; // atributo

        public function falar(){
            echo "Meu nome é :".$this->nome;
        }

    }

    $luiz = new Pessoa();

    $luiz->nome = "Luiz Felipe";

    $luiz->falar();

    echo "<br>";

    class Carro {
        
        private $modelo;        
        
        public function getModelo(){
            return $this->modelo;
        }

        public function setModelo($modelo){
            $this->modelo = $modelo;            
        }

        public function exibir(){
            return array(
                "modelo"=>$this->getModelo(),
                "Ano"=>2020    
            );
        }
    }

    $gol = new Carro();
    $gol->setModelo("Gol GT");

    print_r($gol->exibir());

    echo "<br> <strong> 2 - MÉTODOS ESTÁTICOS </strong> <br/>";

    class Documento {
        private $numero;

        public function setNumero($numero){
            $resultado = Documento::validaCPF($numero); // CHAMADA DE MÉTODO ESTÁTICO

            if ($resultado === false){
                throw new Exception("CPF Inválido", 1);                
            }            

            $this->numero = $numero;
        }

        public function getNumero(){
            return $this->numero;
        }

        public static function validaCPF($cpf):bool{            
            
            if(empty($cpf)) {                
                return false;
            }
                        
            $cpf = preg_match('/[0-9]/', $cpf)?$cpf:0;

            $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
                

            if (strlen($cpf) != 11) {
                echo "length";
                return false;
            }

            else if ($cpf == '00000000000' || 
                $cpf == '11111111111' || 
                $cpf == '22222222222' || 
                $cpf == '33333333333' || 
                $cpf == '44444444444' || 
                $cpf == '55555555555' || 
                $cpf == '66666666666' || 
                $cpf == '77777777777' || 
                $cpf == '88888888888' || 
                $cpf == '99999999999') {
                return false;

                } else {                      
                    
                for ($t = 9; $t < 11; $t++) {
                        
                    for ($d = 0, $c = 0; $c < $t; $c++) {
                        $d += $cpf{$c} * (($t + 1) - $c);
                    }
                    $d = ((10 * $d) % 11) % 10;
                    if ($cpf{$c} != $d) {
                        return false;
                    }
                }

                return true;
            }
        }

    }

    echo "<br> Validação CPF <br>";

    $cpf = new Documento();
    $cpf->setNumero("01457390078");

    var_dump($cpf->getNumero());

    var_dump(Documento::validaCPF("03828499066")); // INVOCANDO O MÉTODO ESTÁTICO SEM INSTANCIAR A CLASSE.

    echo "<br> <strong> 3 - MÉTODOS MÁGICOS </strong> <br/>";


    class Endereco {
        private $logradouro;
        private $numero;

        public function __construct($logradouro, $numero){
            $this->logradouro = $logradouro;
            $this->numero = $numero;
        }

        public function __destruct(){ // vai excutar no final do arquivo
           // echo "<br> DESTRUIU A CLASSE <strong> ENDEREÇO </strong> <br>";
        }

    }

    $endereco = new Endereco("Rua caboçu", "31");

    var_dump($endereco);

    echo "<br> <strong> 4 - ENCAPSULAMENTO </strong> <br/>";

    class Animal {
        public $raca = "Pastor alemão";
        protected $especie = "Mamífero";
        private $idade = 25;

        public function exibeDados(){ // vai exibir pq a função da própria classe está chamando.
            echo $this->raca . "<br>";
            echo $this->especie . "<br>";
            echo $this->idade . "<br>";
        }
        
    }

    $objeto = new Animal();

    $objeto->exibeDados();
   


?>