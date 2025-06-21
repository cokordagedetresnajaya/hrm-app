<?php

namespace Tests\Feature\Model;

use App\Models\Company;
use App\Models\User;
use Database\Seeders\CompanySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public function testBelongsToManyCompanies()
    {
        $this->seed([CompanySeeder::class, UserSeeder::class]);

        $user = User::where("email", "user1@example.com")->first();
        self::assertNotNull($user);

        $company = Company::where("email", "samplecompany1@example.com")->first();
        self::assertNotNull($company);

        $user->companies()->attach($company->id);

        $company = Company::where("email", "samplecompany2@example.com")->first();
        self::assertNotNull($company);

        $user->companies()->attach($company->id);
        $companies = $user->companies;
        self::assertCount(2, $companies);
        self::assertEquals("samplecompany1@example.com", $companies[0]->email);
        self::assertEquals("samplecompany2@example.com", $companies[1]->email);
    }
}
