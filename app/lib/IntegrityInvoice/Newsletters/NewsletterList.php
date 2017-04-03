<?php namespace IntegrityInvoice\Newsletters;

/**
 * Interface NewsletterList
 * @package IntegrityInvoice\Newsletters
 */
interface NewsletterList{

    /**
     * @param $listName
     * @param $email
     * @return mixed
     */
    public function subscribeTo($listName, $email);

    /**
     * @param $listName
     * @param $email
     * @return mixed
     */
    public function unsubscribeFrom($listName, $email);

}