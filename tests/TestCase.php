<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from company_user");
        DB::delete("delete from payments");
        DB::delete("delete from salaries");
        DB::delete("delete from payrolls");
        DB::delete("delete from contracts");
        DB::delete("delete from employees");
        DB::delete("delete from designations");
        DB::delete("delete from departments");
        DB::delete("delete from companies");
        DB::delete("delete from users");
    }
}
