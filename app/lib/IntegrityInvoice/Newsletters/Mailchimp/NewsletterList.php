<?php namespace IntegrityInvoice\Newsletters\Mailchimp;

use IntegrityInvoice\Newsletters\NewsletterList as NewsletterListInterface;
use Mailchimp;

class NewsletterList implements NewsletterListInterface{

    /**
     * @var
     */
    protected $mailchimp;

    protected $list = [
        'integritySubscribers' => '11f906b789'
    ];

    /**
     * @param Mailchimp $mailchimp
     */
    function __construct(Mailchimp $mailchimp)
    {
        $this->mailchimp = $mailchimp;
    }

    /*
     * Subscribe a user to a Mailchimp list
     */

    /**
     * @param $listName
     * @param $email
     * @return mixed
     */
    public function subscribeTo($listName, $email)
    {
        return $this->mailchimp->lists->subscribe(
            $this->list[$listName],
            ['email' => $email],
            null, // merge var,
            'html', // email type
            false, // require double opt in?
            true // update existing customers?
        );
    }

    /**
     * @param $listName
     * @param $email
     * @return mixed
     */
    public function unsubscribeFrom($listName, $email)
    {
        return $this->mailchimp->lists->unsubscribe(
            $this->list[$listName],
            ['email' => $email],
            false, // delete the member permanently
            false, // send goodbye email?
            false // send unsubscribe notification email?
        );
    }
}