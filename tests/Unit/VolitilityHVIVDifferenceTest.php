<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VolitilityHVIVDifferenceTest extends TestCase{
    const $date1 = '2000-01-01';
    const $date2 = '2015-05-10'; // Valid test day
    const $date3 = '2015-05-24'; // 14 days later from date2
    const $date4 = '2000-02-20';
    
    // Error prone dates
    const $date5 = '2099-01-01'; // Date DNE
    const $date6 = '20000101'; // Incorrect format (no dashes)
    const $date7 = '01-01-2012'; // Incorrect format
    
    //FSSCLE::VolitilityHVIVDifference($startDate);
    str_getcsv outputCSV = CommandLineExecutor::VolitilityHVIVDifference($date1);
    
    public function testVolitilityResponse(){
        $this -> withoutExceptionHandling(); // Make error codes better for debugging
        //$response -> assertOk(); // Make sure the response is ok
    }
    
}
    
// Example function to test forms later
//public function testNewUserRegistration()
//{
//    $this->visit('/register')
//         ->type('Taylor', 'name')
//         ->check('terms')
//         ->press('Register')
//         ->seePageIs('/dashboard');
//}
