<?php

namespace APP\Model;

use Pet\Model\Model;

class Contact extends Model
{
    protected string $table = 'contact';

    public function reContact($phone): Contact
    {
        $contact = (new self(['phone' => $phone]));
        if ($contact->exist()) {
            $this->delete();
            return $contact;
        }
        return $this;
    }
}
