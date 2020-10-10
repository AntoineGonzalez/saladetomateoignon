<?php
namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailSenderService{

    private $parts;
    private $recipient;
    private $mailer;
    private $templating;

    public function __construct(MailerInterface $mailer){
        $this->parts = [
            "title" => "",
            "content" => "",
            "footer" => "",
            "recipient" => ""

        ];
        $this->mailer = $mailer;
    }

    function sendEmail(){
        $mail = (new TemplatedEmail())
            ->from('contact.saladetomateoignons@gmail.com')
            ->to($this->recipient)
            // path of the Twig template to render
            ->htmlTemplate($this->template)
            ->subject($this->parts["title"])
            // pass variables (name => value) to the template
            ->context($this->parts);

        $this->mailer->send($mail);
    }

    function buildSuccessfullPurchase($purchase){
        $user = $purchase->getUser();
        $this->recipient = $user->getEmail();
        $this->template = "emails/email_validation_payment.html.twig";

        $this->parts = [
            "user" => $user,
            "date" => $purchase->getDate(),
            "recipient" => $this->recipient,
            "menu_content" => "",
            "title" => "Commande enregistrée",
            "menus" => $purchase->getPurshaseMenuses(),
            "supplement" => $purchase->getPurshaseProducts(),
            "total" => $purchase->getTotalAmount(),
            "purchase_hour" => date('H:i', $purchase->getDeliveryHour()->getTimestamp())
        ];
    }

    function buildContactmail(){
        $this->template = "contact_template.html.twig";
        $this->part = [
            "mail_message" => ""
        ];
    }

    function testMail() {
      $message = (new \Swift_Message('Hello Email'))
       ->setFrom('saladetomateoignons.contact@gmail.com')
       ->setTo('21500894@etu.unicaen.fr')
       ->setBody('You should see me from the profiler!')
       ;

       $this->mailer->send($message);
    }

    function buildCommandStatus($purchase) {
        $user = $purchase->getUser();
        $this->recipient = $user->getEmail();
        $this->template = "emails/email_status_update.html.twig";

        $statusStr = [
            "waiting" => "En attente de validation",
            "ready" => "Préparée",
            "canceled" => "Refusée",
            "in_preparation" => "En préparation",
            "delivered" => "Délivrée",
            "validate" => "Validée"
        ];

        $this->parts = [
            "title" => "Changement de status",
            "user" => $user,
            "total" => $purchase->getTotalAmount(),
            "status" => $statusStr[$purchase->getStatus()]
        ];
    }
}

?>
