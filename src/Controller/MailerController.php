<?php


namespace App\Controller;
// Include library files
// require 'PHPMailer/Exception.php';
// require 'PHPMailer/PHPMailer.php';
// require 'PHPMailer/SMTP.php';


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';



class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function index(): Response
    {
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }

    #[Route('/email', name: 'send_email')]
    public function sendEmail(Request $request): Response
    {
        $req = $request->getContent();
        $req = json_decode($req);
        $email = $req->email;
        // Create an instance; Pass `true` to enable exceptions
        $mail = new PHPMailer;

        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;    //Enable verbose debug output
        $mail->isSMTP();                            // Set mailer to use SMTP
        $mail->Host = 'smtp.wp.pl';           // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = 'ddt13@wp.pl';       // SMTP username
        $mail->Password = 'L20s10r76';         // SMTP password
        $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                          // TCP port to connect to

        // Sender info
        $mail->setFrom('ddt13@wp.pl', 'SenderName');
        // $mail->addReplyTo('reply@example.com', 'SenderName');

        // Add a recipient
        $mail->addAddress($email);

        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        // Set email format to HTML
        $mail->isHTML(true);

        // Mail subject
        $mail->Subject = 'Your registration to UI/UX Conferency';

        // Mail body content
        $bodyContent = '<h1>How to Send Email from Localhost using PHP by CodexWorld</h1>';
        $bodyContent .= '<p>This HTML email is sent from the localhost server using PHP by <b>CodexWorld</b></p>';
        $mail->Body    = $bodyContent;

        // Send email
        if(!$mail->send()) {
            echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
        } else {
            echo 'Message has been sent.';
        }

        // $req = $request->getContent();
        // $req = json_decode($req, true);
        // $email = (new Email())
        // ->from('cetex.tc@gmail.com')
        // ->to('cetex.tc@gmail.com')
        // ->subject('Test Email')
        // ->text('Sending emails is fun again!')
        // ;

        // $mailerInterface->send($email);
        return new Response('Your registration has been send!');
    }
}
