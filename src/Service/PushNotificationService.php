<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class PushNotificationService {

    private $client;
    private $apiUrl;
    private $authorizationKey;

    public function __construct($fcm_pass){
        $this->client = HttpClient::create();
        $this->apiUrl = 'https://fcm.googleapis.com/fcm/send';
        $this->authorizationKey = $fcm_pass;
    }

    function sendNewPurchaseNotification($purchase, $tokens=[]) {
        $user = $purchase->getUser();
        $to = [];
        foreach($tokens as $tok) {
            $to[] = $tok->getTokenStr();
        }

        $response = $this->client->request('POST', $this->apiUrl, [
            'headers' => [
                'Authorization' => 'key='.$this->authorizationKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'to' => $to[0],
                'data' => [
                    'purchaseId' => $purchase->getId(),
                ],
                'notification' => [
                    'title' => 'Nouvelle commande n°'.$purchase->getId(),
                    'body' =>  $user->getFirstname().' a passé une commande pour un montant de '.$purchase->getTotal().'€',
                ]
            ],
        ]);
    }
}

?>
