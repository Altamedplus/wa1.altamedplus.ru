<?php

namespace APP\Module\Max;
use APP\Module\Max\Entity;
use Exception;

class Messenger extends Entity
{
    public function __construct() {
        if (defined('TOKEN_MAX')) {
            parent::__construct(TOKEN_MAX);
        } else {
            throw new Exception('is not token max');
        }
    }
}
