<?php
namespace App\Controller;

use App\Entity\Cliente;
use App\Repository\Banco;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

class ClienteController extends AbstractController {
	/** 
	* @Route("/cliente/form")
	*/
	public function new(Request $request) {
		$nome = $request->request->get('nome', '');
		$email = $request->request->get('email', '');
		$senha = $request->request->get('senha', '');
		$senhaConfirmacao = $request->request->get('senhaConfirmacao', '');

		$cliente = new Cliente();
		$cliente->setId(1);
		$cliente->setNome($nome);
		$cliente->setEmail($email);
		$cliente->setSenha($senha);
		
		$erros = array();
		$sucessos = array();

		if ($_POST) {
			if (!$nome) {
				$erros[] = 'Digite o nome!';
			}

			if (!$email) {
				$erros[] = 'Digite o e-mail!';
			}

			if (strlen($senha) < 6) {
				$erros[] = 'Digite uma senha com pelo 6 caracteres.';
			}

			if ($senha != $senhaConfirmacao) {
				$erros[] = 'A confirmação está diferente da senha.';
			}

			if (count($erros) == 0) {
				$senha = md5($cliente->getSenha());
				$strsql = "insert into clientes (nome, email, telefone, senha) values ('" . $cliente->getNome() . "', '" . $cliente->getEmail() . "', '', '$senha')";
				echo $strsql;

				
				$host = 'localhost';
				$user = 'root';
				$pass = '';
				$base = 'verao-2019';
				$conn = new \mysqli($host, $user, $pass, $base);
				if (\mysqli_connect_errno()) {
					exit("Não foi possível conectar no banco de dados!");
				}			

				//Verifica se houve erro de conexão:
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				//teste
				$conn->query($strsql);
				//teste


				/*if ($conn->query($sql) === TRUE) {
					echo "<br>Cadastro efetuado com sucesso.<br>";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
				*/

				//Encerra a conexão com o servidor:
				$conn->close();

				//$insert = mysql_query($strsql, $banco);

				//$cliente = new Cliente();
				//$id = $cliente->getId();
				//echo $id;

				/*if ($strsql->execute()) {
					$sucessos[] = 'Cliente cadastrado com sucesso!';
					//header('Location: login.php');
				}*/

				/*if ($insert) {
					echo "Sucesso! Row ID: {$banco->insert_id}";
					$sucessos[] = 'Cliente cadastrado com sucesso!';
				}*/
			}	
		}
		
	


		return $this->render('cliente/form.html.twig', [
			'cliente' => $cliente,
			'senhaConfirmacao' => $senhaConfirmacao,
			'erros' => $erros,
			'sucessos' => $sucessos			
		]);
	}

}
