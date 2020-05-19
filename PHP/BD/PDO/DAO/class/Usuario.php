<?php 

    clasS Usuario {

        private $idusuario;
        private $deslogin;
        private $dessenha;
        private $dtcadastro;

        public function getIdusuario(){
            return $this->idusuario;
        }

        public function getDeslogin(){
            return $this->deslogin;
        }
        
        public function getDessenha(){
            return $this->dessenha;
        }
        
        public function getDtcadastro(){
            return $this->dtcadastro;
        }     
           
        public function setIdusuario($value){
            $this->idusuario = $value;
        }

        public function setDeslogin($value){
            $this->deslogin = $value;
        }

        public function setDessenha($value){
            $this->dessenha = $value;
        }
        
        public function setDtcadastro($value){
            $this->dtcadastro = $value;
        }


        public function __construct($deslogin = "", $dessenha = ""){

            $this->setDeslogin($deslogin);
            $this->setDessenha($dessenha);

        }

        public function update($deslogin, $dessenha){

            $this->setDeslogin($deslogin);
            $this->setDessenha($dessenha);            

            $sql = new sql();

            $sql->query("UPDATE tb_usuarios set deslogin = :DESLOGIN, dessenha = :DESSENHA WHERE idusuario = :ID",array(
                ":DESLOGIN"=>$this->getDeslogin(),
                ":DESSENHA"=>$this->getDessenha(),
                ":ID"=>$this->getIdusuario()
            ));            


            echo "Usuario atualizado com sucesso!!<br/>";
        }


        public function delete(){

            $sql = new sql();

            $sql->query("DELETE FROM tb_usuarios WHERE idusuario = :ID",array( 
                ":ID"=>$this->getIdusuario()
            ));

            $this->setIdusuario(0);
            $this->setDeslogin("");
            $this->setDessenha(""); 

            echo "Usuário excluído com sucesso!";

        }

        public function setData($row){

            $this->setIdusuario($row['idusuario']);
            $this->setDeslogin($row['deslogin']);
            $this->setDessenha($row['dessenha']);
            $this->setDtcadastro(new DateTime($row['dtcadastro']));      

        }

        public function loadById($id){

            $sql = new sql();

            $results = $sql->select("SELECT * FROM tb_usuarios WHERE idusuario = :ID",array(":ID"=>$id));

            if (count($results) > 0){
                $row = $results[0];

                $this->setData($row);
            }

        }


        public function insert(){
            $sql = new sql();

            $results = $sql->select("CALL sp_usuarios_insert(:LOGIN,:SENHA)",array(
                ":LOGIN"=>$this->getDeslogin(),
                ":SENHA"=>$this->getDessenha()
            ));

            if (count($results) >0 ){
                $this->setData($results[0]);

                echo "Usuário <strong>" . $this->getDeslogin() . "</strong> incluído com sucesso!!!!! <br/>";
            } else {
                echo "Não foi possível incluir o usuário.";
            }
        }
        

        public function login($login, $senha){

            $sql = new sql();

            $results = $sql->select("SELECT * FROM tb_usuarios WHERE deslogin = :LOGIN AND dessenha = :SENHA",array(
                ":LOGIN"=>$login, 
                ":SENHA"=>$senha
            ));

            if (count($results) > 0){
                $row = $results[0];

                $this->setData($row);
                
                echo "Login realizado com sucesso!";               

            } else {
                throw new Exception("Usuário e/ou senha inválidos.");
                
            }         

        }

        
        // MÉTODOS ESTÁTICOS

        public static function getAllUsers(){
            $sql = new sql();

            return $sql->select("SELECT * FROM tb_usuarios ORDER BY deslogin");
        }

        public static function search($login){
            $sql = new sql();

            return $sql->select("SELECT * FROM tb_usuarios WHERE deslogin LIKE :SEARCH ORDER BY deslogin",array(
                ":SEARCH"=>"%".$login."%"
            ));
        }

        public function __toString(){
            if ($this->getIdusuario() != null){
                return json_encode(array(
                    "idusuario"=>$this->getIdusuario(),
                    "deslogin"=>$this->getDeslogin(),
                    "dessenha"=>$this->getDessenha(),
                    "dtcadastro"=>$this->getDtcadastro()->format("d/m/Y")
                ));
            } else {
                return "Não existe registro";
            }
        }



    }

?>