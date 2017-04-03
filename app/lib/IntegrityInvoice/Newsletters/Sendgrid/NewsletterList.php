<?php namespace IntegrityInvoice\Newsletters\Sendgrid;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use IntegrityInvoice\Newsletters\NewsletterList as NewsletterListInterface;
use SendGrid;

class NewsletterList implements NewsletterListInterface{

    /**
     * @var
     */
    protected $sendgrid;

    protected $list = [
        'sightedSubscribers' => '858101'
    ];

    /**
     * @param SendGrid Key $key
     */
    function __construct()
    {
        $this->sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
    }

    /*
     * Subscribe a user to a Sendgrid list
     */

    /**
     * @param $listName
     * @param $email
     * @return mixed
     */
    public function subscribeTo($listName, $email)
    {
        $body = json_decode(sprintf('[{"email": "%s"}]', $email));
        // add to all contacts
        $res = $this->sendgrid
                     ->client
                     ->contactdb()
                     ->recipients()
                     ->post($body);

        
        if($res->statusCode() !== SymfonyResponse::HTTP_CREATED)
            return FALSE;

        $res = $this->sendgrid
                    ->client
                    ->contactdb()
                    ->lists()
                    ->_($this->list[$listName])
                    ->recipients()
                    ->_(base64_encode($email))
                    ->post();

        return $res->statusCode() === SymfonyResponse::HTTP_CREATED;
    }

    /**
     * @param $listName
     * @param $email
     * @return mixed
     */
    public function unsubscribeFrom($listName, $email)
    {

        $res = $this->sendgrid
                    ->client
                    ->contactdb()
                    ->lists()
                    ->_($this->list[$listName])
                    ->recipients()
                    ->_(base64_encode($email))
                    ->delete();
        return $res->statusCode() === SymfonyResponse::HTTP_NO_CONTENT;
    }
}