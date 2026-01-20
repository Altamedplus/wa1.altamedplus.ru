<?php

namespace Tests\Unit\Module;

use APP\Model\NalogClinicModel;
use Pet\Command\Console\Console;
use Pet\Model\Model;
use PHPUnit\Framework\TestCase;


class NalogClinicTest extends TestCase
{

    public function testGetClinic(){
        $clinics =  (new NalogClinicModel())->findM(
            ['nalog_id' => 80],
            callback: function (Model $m) {
                $m->select(
                    'clinic.legal_name',
                    'clinic.owner',
                    'nalog_clinic.*'
                );
                $m->join('clinic')->on('nalog_clinic.clinic_id = clinic.id');
                file_put_contents(__DIR__.'/data.sql' , $m->toString());
                // return $m;
            }
        );
        Console::print($clinics, Console::GREEN);
    }
}