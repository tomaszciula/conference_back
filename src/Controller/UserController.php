<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route("/add/user", name: "add_user", methods: ["POST"])]
    public function addUser(Request $request, MailerInterface $mailerInterface): Response
    {
        $req = $request->getContent();
        $req = json_decode($req, true);
        $email = $req['email'];

        $user = new User();
        // $user->setUsername($req['username']);
        $user->setEmail($req['email']);
        $user->setPassword($req['password']);
        $user->setAbstract($req['abstract']);
        $user->setUsername($req['username']);
        $user->setPhone($req['phone']);
        $user->setTitle($req['title']);
        $user->setAffiliation($req['affiliation']);
        $user->setRoles(['ROLE_ADMIN']);

        // ... set other properties

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $mail = new PHPMailer();

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
        $mail->setFrom('ddt13@wp.pl', 'Administrator');
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
        $bodyContent = '<h1>Your registration is confirmed!</h1>';
        $bodyContent .= '<p>This email is sent to confirm that you have registered for <b>UI/UX as future professions conferency</b></p>';
        $bodyContent .= '<p>Now you can log in</p>';
        $bodyContent .= '<p>We are looking forward to see you there!</p>';
        $bodyContent .= '<p>Best regards,</p>';
        $bodyContent .= '<p>UI/UX Conferency Organizers</p>';
        $mail->Body    = $bodyContent;

        // Send email
        if(!$mail->send()) {
            echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
        } else {
            echo 'Message has been sent.';
        }

        $response = new JsonResponse();
        $response->headers->set("Access-Control-Allow-Origin", "*");
        // $response->headers->set("Access-Control-Allow-Credentials", "true");
        $response->headers->set("Access-Control-Allow-Methods", "GET, POST, PATCH, PUT, DELETE
, OPTIONS");
        $response->headers->set("Access-Control-Allow-Headers", "Origin, Content-Type, X-Auth-Token
");
        // $response->headers->set('Access-Control-Allow-Headers', 'AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2');

        // $response->headers->set("Access-Control-Max-Age", "172800");
        // $response->headers->set("Content-Type", "application/json");
        // $response->headers->set("Content-Disposition", "attachment");
        // $response->headers->set("Content-Transfer-Encoding", "binary");
        // $response->headers->set("Connection", "keep-alive");

        // return new JsonResponse(['message' => 'User added successfully']);
        return $response;

    }
}
