<?php

namespace Tests\Feature\Controller;

use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DesignationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_designation_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);

        $response = $this->actingAs($user)->withSession(['company_id' => $company->id])->get(route('designations.create'));
        $response->assertStatus(200)->assertSeeText('Create Designation');
    }

    public function test_user_cannot_access_create_designation_page_without_selected_company()
    {
        $user = User::factory()->create();
        $previousUrl = '/dashboard';
        $response = $this->actingAs($user)->from($previousUrl)->get(route('designations.create'));

        $response->assertRedirect($previousUrl);
        $response->assertSessionHas('error', 'Please select a company first.');
    }

    public function test_guess_cannot_access_create_designation_page()
    {
        $response = $this->get(route('designations.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_and_has_selected_company_can_store_designation()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);
        $department = Department::factory()->create([
            'company_id' => $company->id
        ]);

        $response = $this->actingAs($user)
            ->withSession(['company_id' => $company->id])
            ->post(route('designations.store'), [
                'name' => 'Sample Designation',
                'department_id' => $department->id
            ]);

        $this->assertDatabaseHas('designations', [
            'name' => 'Sample Designation',
            'department_id' => $department->id
        ]);

        $response->assertSessionHas('success', 'Designation created successfully.');
        $response->assertRedirect(route('designations.index'));
    }

    public function test_guess_cannot_store_designation()
    {
        $company = Company::factory()->create();
        $department = Department::factory()->create([
            'company_id' => $company->id
        ]);

        $response = $this->withSession(['company_id' => $company->id])->post(route('designations.store'), [
            'name' => 'Sample Designation',
            'department_id' => $department->id
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_cannot_store_designation_when_not_selected_company()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);
        $department = Department::factory()->create([
            'company_id' => $company->id
        ]);

        $response = $this->actingAs($user)->post(route('designations.store'), [
            'name' => 'Sample Designation',
            'department_id' => $department->id
        ]);

        $response->assertSessionHas('error', 'Please select a company first.');
    }

    public function test_validation_fails_when_name_and_department_id_are_missing()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);

        $response = $this->actingAs($user)
            ->withSession(['company_id' => $company->id])
            ->post(route('designations.store'), []);

        $response->assertSessionHasErrors(['name', 'department_id']);
    }

    public function test_designations_list_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);
        $department = Department::factory()->create([
            'company_id' => $company->id
        ]);
        $designations = Designation::factory()->count(4)->create([
            'department_id' => $department->id
        ]);

        foreach ($designations as $key => $designation) {
            Employee::factory()->count(3)->create([
                'designation_id' => $designation->id
            ]);
        }

        $response = $this->actingAs($user)->withSession(['company_id' => $company->id])->get(route('designations.index'));
        $response->assertStatus(200);
        $response->assertSeeText($company->name . ' Designations');

        foreach ($designations as $designation) {
            $employeeCount = $designation->employees->count();

            $response->assertSeeText($designation->name);
            $response->assertSeeText((string)$employeeCount);
        }
    }

    public function test_edit_designation_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);
        $department = Department::factory()->create([
            'company_id' => $company->id
        ]);

        $designation = Designation::factory()->create([
            'department_id' => $department->id
        ]);

        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->get(route('designations.edit', $designation->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.designations.edit');
        $response->assertViewHasAll([
            'title',
            'designation',
            'departments'
        ]);

        $response->assertSee('Edit Designation');
        $this->assertEquals('Edit Designation', $response->viewData('title'));
        $this->assertTrue($response->viewData('designation')->is($designation));
    }

    public function test_edit_page_returns_404_if_designation_not_found()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $invalidId = 9999;
        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->get(route('designations.edit', $invalidId));

        $response->assertStatus(404);
    }

    public function test_edit_page_cannot_accessed_by_guess()
    {
        $company = Company::factory()->create();
        $department = Department::factory()->create([
            'company_id' => $company->id
        ]);
        $designation = Designation::factory()->create([
            'department_id' => $department->id
        ]);

        $response = $this->withSession(['company_id' => $company->id])
            ->get(route('designations.edit', $designation->id));

        $response->assertRedirect(route('login'));
    }

    public function test_edit_page_cannot_accessed_if_user_not_select_company()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $department = Department::factory()->create([
            'company_id' => $company->id
        ]);
        $designation = Designation::factory()->create([
            'department_id' => $department->id
        ]);

        $fromUrl = route('dashboard');

        $response = $this->actingAs($user)->from($fromUrl)->get(route('designations.edit', $designation->id));
        $response->assertRedirect($fromUrl);
    }

    public function test_user_can_update_designation_with_valid_data()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);
        $department = Department::factory()->create([
            'company_id' => $company->id
        ]);
        $department2 = Department::factory()->create([
            'company_id' => $company->id
        ]);

        $designation = Designation::factory()->create([
            'department_id' => $department->id
        ]);

        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])
        ->patch(route('designations.update', $designation->id), [
            'name' => $designation->name . ' Update',
            'department_id' => $department2->id
        ]);

        $response->assertSessionHas('success', 'Designation updated successfully.');
        $response->assertRedirect(route('designations.index'));
        $this->assertDatabaseHas('designations', [
            'name' => $designation->name . ' Update',
            'department_id' => $department2->id
        ]);
    }

    public function test_update_designation_with_fails_validation()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);
        $department = Department::factory()->create([
            'company_id' => $company->id
        ]);

        $designation = Designation::factory()->create([
            'department_id' => $department->id
        ]);

        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])
        ->patch(route('designations.update', $designation->id), []);

        $response->assertSessionHasErrors(['name', 'department_id']);
    }

    public function test_delete_a_designation_and_redirects_with_success_message()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);
        
        $department = Department::factory()->create([
            'company_id' => $company->id,
        ]);

        $designation = Designation::factory()->create([
            'department_id' => $department->id
        ]);

        $response = $this->actingAs($user)
            ->withSession(['company_id' => $company->id])
            ->delete(route('designations.delete', $designation->id));

        $this->assertDatabaseMissing('designations', [
            'id' => $designation->id,
        ]);

        $response->assertRedirect(route('designations.index'));
        $response->assertSessionHas('success', 'Designation deleted successfully.');
    }

    public function test_get_designations_based_on_department()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);

        $department = Department::factory()->create([
            'company_id' => $company->id
        ]);

        $designations = Designation::factory()->count(4)->create([
            'department_id' => $department->id
        ]);

        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->getJson("/designations/by-department/{$department->id}");

        $response->assertStatus(200);

        $response->assertJsonCount(4);

        $response->assertJsonFragment([
            'id' => $designations[0]->id,
            'name' => $designations[0]->name,
        ]);
    }
}
