<?php 
require_once '../controller/DB.php';

	class Setor {

        private $conexao;
        private $setor;
		private $idCoordenador;
		private $ramalTelefonico;
		private $nome;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

       public function __construct($conexao,$setor) {
            $this->conexao = $conexao;
            $this->setor = $setor;
        }

        public function inserir() {

            try {

                $status = 1; //já cadastra ativo.

                $sql = 'INSERT INTO setores (id_coordenador, ramal_telefonico, nome, status) 
                        VALUES (?,?,?,?)';    
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(1, $this->setor['id_coordenador']);
                $stmt->bindValue(2, $this->setor['ramal_telefonico']);
                $stmt->bindValue(3, $this->setor['nome']);
                $stmt->bindValue(4, $status);
                return $stmt->execute();

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        public function listar() {

            try {

                $sql = 'SELECT p.id_pessoa, s.id_setor, s.nome, ramal_telefonico, p.nome as coordenador 
                        FROM setores s 
                        INNER JOIN coordenadores c ON s.id_coordenador = c.id_coordenador
                        INNER JOIN pessoas p ON c.id_pessoa = p.id_pessoa
                        WHERE s.status <> 0';
                $stmt = $this->conexao->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_OBJ);

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        public function editar() {

            try {

                $sql = "UPDATE setores SET id_coordenador = ?, ramal_telefonico = ?, nome = ? 
                        WHERE id_setor = ?";
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(1, $this->setor['id_coordenador']);
                $stmt->bindValue(2, $this->setor['ramal_telefonico']);
                $stmt->bindValue(3, $this->setor['nome']);
                $stmt->bindValue(4, $this->setor['id_setor']);            
                return $stmt->execute();

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        
        public function deletar() {
            
            try {  

                $status = 0; //para cancelar o registro
                $sql = "UPDATE setores SET status = ? WHERE id_setor = ?";
                $stmt = DB::prepare($sql);
                $stmt->bindParam(1, $status);
                $stmt->bindParam(2,$this->setor['id_setor']);
               return $stmt->execute();	

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        
        public function listar_por_id() {

            try {

                $sql = 'SELECT s.id_setor, c.id_coordenador, s.nome, ramal_telefonico
                        FROM setores s 
                        INNER JOIN coordenadores c ON s.id_coordenador = c.id_coordenador
                        INNER JOIN pessoas p ON c.id_pessoa = p.id_pessoa
                        WHERE s.id_setor = ?';
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindParam(1,$this->setor['id_setor']);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_OBJ);

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
	}
 ?>