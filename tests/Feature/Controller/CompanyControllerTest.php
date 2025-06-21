<?php

namespace Tests\Feature\Controller;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_company_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/companies/create');
        $response->assertStatus(200)->assertSeeText('Create Company');
    }

    public function test_companies_list_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/companies');
        $response->assertStatus(200)->assertSeeText('Companies');
    }

    public function test_edit_company_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $response = $this->actingAs($user)->get('/companies/' . $company->id . '/edit');
        $response->assertStatus(200)->assertSeeText('Edit Company');
    }
}
