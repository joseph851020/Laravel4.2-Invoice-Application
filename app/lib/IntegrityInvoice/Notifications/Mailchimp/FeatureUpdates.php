<?php namespace IntegrityInvoice\Notifications\Mailchimp;

use IntegrityInvoice\Notifications\FeatureUpdates as FeatureUpdatesInterface;

use Mailchimp;

class FeatureUpdates implements FeatureUpdatesInterface{

    const INTEGRITY_SUBSCRIBER_ID = '11f906b789';

    /**
     * @var
     */
    protected $mailchimp;

    /**
     * @param Mailchimp $mailchimp
     */
    function __construct(Mailchimp $mailchimp)
    {
        $this->mailchimp = $mailchimp;
    }


    /**
     * @param $title
     * @param $body
     */
    public function notify($title, $body){

        $options = [
                'list_id' => self::INTEGRITY_SUBSCRIBER_ID,
                'subject' => 'New feature: ' . $title,
                'from_name' => 'Integrity',
                'from_email' => 'updates@sighted.com',
                'to_name' => 'Integrity Subscriber'
        ];

        $content = [
            'html' => $body,
            'text' => strip_tags($body)
        ];

        $campaign =  $this->mailchimp->campaigns->create('regular', $options, $content);

        $this->mailchimp->campaigns->send($campaign['id']);

    }

}