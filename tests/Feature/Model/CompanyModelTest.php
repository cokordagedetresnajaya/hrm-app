<?php

namespace Tests\Feature\Model;

use App\Models\Company;
use App\Models\User;
use Database\Seeders\CompanySeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DesignationSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyModelTest extends TestCase
{
    public function testCompanyHasManyDepartments()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class]);

        $company = Company::where("email", "samplecompany1@example.com")->first();
        self::assertNotNull($company);
        self::assertCount(2, $company->departments);

        foreach ($company->departments as $department) {
            $this->assertEquals(
                $company->id,
                $department->company_id
            );
        }
    }

    public function testBelongsToManyUsers()
    {
        $this->seed([UserSeeder::class, CompanySeeder::class]);

        $company = Company::where("email", "samplecompany1@example.com")->first();
        self::assertNotNull($company);

        $user = User::where("email", "user1@example.com")->first();
        self::assertNotNull($user);

        $company->users()->attach($user->id);

        $user = User::where("email", "user2@example.com")->first();
        self::assertNotNull($user);

        $company->users()->attach($user->id);
        $users = $company->users;
        self::assertCount(2, $users);
        self::assertEquals("user1@example.com", $users[0]->email);
        self::assertEquals("user2@example.com", $users[1]->email);
    }

    public function testHasManyDesignations()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class, DesignationSeeder::class]);

        $company = Company::where("email", "samplecompany1@example.com")->first();
        self::assertNotNull($company);

        $designations = $company->designations;
        self::assertCount(4, $designations);
        self::assertEquals("Auditor", $designations[0]->name);
        self::assertEquals("Accountant", $designations[1]->name);
        self::assertEquals("Software Engineer", $designations[2]->name);
        self::assertEquals("Project Manager", $designations[3]->name);
    }

    public function testGetLogoAttribute()
    {
        $this->seed(CompanySeeder::class);

        $company = Company::where("email", "samplecompany1@example.com")->first();

        // company with logo test
        self::assertEquals(asset("storage/default-logo.png"), $company->getLogoUrlAttribute());
    
        $company = Company::where("email", "samplecompany2@example.com")->first();

        // company without logo test
        self::assertEquals(asset("images/default-logo.png"), $company->getLogoUrlAttribute());
    }
}
