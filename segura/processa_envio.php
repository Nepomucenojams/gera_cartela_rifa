<?php

    //REQUIERE ARQUIVOS PHP
    require '../public_html/app_send_mail//lib/PHPMailer/PHPMailer.php';
    require '../public_html/app_send_mail//lib/PHPMailer/SMTP.php';
    require '../public_html/app_send_mail//lib/PHPMailer/Exception.php';
    require '../public_html/app_send_mail//lib/PHPMailer/OAuth.php';
    require '../public_html/app_send_mail//lib/PHPMailer/POP3.php';

    //NAMESPACE
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //print_r($_POST);

    class Mensagem{
        private $numero = null;
        private $codigo = null;
        private $email = null;

        public $status = array ('codigo_status'=>null, 'descricao_status'=>'');

        //METODOS GETTERS E SETTERS
        public function __get($atributo)
        {
            return $this->$atributo;
        }

        public function __set($atributo, $valor)
        {
            $this->$atributo = $valor;
        }

        public function mensagemValida()
        {               //verificando se algum esta vazio
            if(empty($this->nome) || empty($this->assunto) || empty($this->mensagem)){
                return false;
            }

            return true;
        }
    }

    $mensagem = new Mensagem();

    //setando os valores no objeto
    $mensagem->__set('numero',$_POST['lastId']);
    $mensagem->__set('codigo',$_POST['senha']);
    $mensagem->__set('email',$_POST['email']);


    //print_r($mensagem);

    //se retorna true entra no if
    if(!$mensagem->mensagemValida()){
        echo 'Mensagem nao  é valida';
      //  header('Location: index.php');
        //die(); //tudo para frente sera descartado
    }

    ###########################################################################

        //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->Encoding = 'base64';
        $mail->SMTPDebug = false;                                      //Enable verbose debug output
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();                                           //Send using SMTP
        $mail->Host       = 'smtp.hostinger.com';                      //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                  //Enable SMTP authentication
        $mail->Username   = '****Email****';                     //SMTP username
        $mail->Password   = '*****Senha****';                               //SMTP password
        $mail->SMTPSecure = 'TLS';            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('suport@gerarifa.online', 'Codigo da Rifa');
        $mail->addAddress($mensagem->__get('email'));     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('', 'Information'); //email para resposta
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments ANEXOS
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Codigo da Rifa';
        $mail->Body    = '<style>
                        	.demo {
                        		border:1px sólido #C0C0C0;
                        		border-collapse:colapso;
                        		padding:5px;
                        	}
                        	.demo th {
                        		border:1px sólido #C0C0C0;
                        		padding:5px;
                        		background:#F0F0F0;
                        	}
                        	.demo td {
                        		border:1px sólido #C0C0C0;
                        		padding:5px;
                        	}
                        </style>
                        <table class="demo">
                        	<tbody>
                        	<tr>
                        		<td>Numero da rifa</td>
                        		<td>'.$mensagem->__get('numero').'</td>
                        	</tr>
                        	<tr>
                        		<td>Código :</td>
                        		<td>'.$mensagem->__get('codigo').'</td>
                        	</tr>
                        	<tr>
                        		<td>Baixar rifa:</td>
                        		<td><a href="https://gerarifa.online/baixar?id='.$mensagem->__get('numero').'">Baixar</a></td>
                        	</tr>
                        	<tr>
                        		<td>Para sortear acesse:</td>
                        		<td><a href="https://gerarifa.online/realizarSorteio">Sortear</a></td>
                        	</tr>
                        	<tr>
                        		<td>Rifa gerada por</td>
                        		<td><a href="https://gerarifa.online/">Gera Rifa online</a></td>
                        	</tr>
                        	<tbody>
                        </table>'; //com html
        $mail->AltBody = 'E necessário utilizar um clint com suporte a html para visualizar essa mensagem'; //sem html

        $mail->send();
        $mensagem->status['codigo_status']= 1;
        $mensagem->status['descricao_status']= 'E-mail enviado com sucesso';
    } catch (Exception $e) {
        $mensagem->status['codigo_status']= 2;
        $mensagem->status['descricao_status']= 'Nao foi possível enviar este email :::DETALHES:::'.$mail->ErrorInfo;
        
    }

    //header('Location: ../index.php?email='.$mensagem->status['descricao_status'].'#contato')
?>