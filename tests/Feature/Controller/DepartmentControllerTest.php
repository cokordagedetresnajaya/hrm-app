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

class DepartmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_department_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $response = $this->actingAs($user)->withSession(['company_id' => $company->id])->get(route('departments.create'));
        $response->assertStatus(200)->assertSeeText('Create Department');
    }

    public function test_user_cannot_access_create_department_page_without_selected_company()
    {
        $user = User::factory()->create();
        $previousUrl = '/dashboard';
        $response = $this->actingAs($user)->from($previousUrl)->get(route('departments.create'));

        $response->assertRedirect($previousUrl);
        $response->assertSessionHas('error', 'Please select a company first.');
    }

    public function test_authenticated_user_can_store_department()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $this->actingAs($user)
            ->withSession(['company_id' => $company->id]);

        $response = $this->post(route('departments.store'), [
            'name' => 'Sample Department',
        ]);

        $this->assertDatabaseHas('departments', [
            'name' => 'Sample Department',
            'company_id' => $company->id,
        ]);

        $response->assertSessionHas('success', 'Department created successfully.');

        $response->assertRedirect(route('departments.index'));
    }

    public function test_validation_fails_if_name_is_missing()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $this->actingAs($user)
            ->withSession(['company_id' => $company->id]);

        $response = $this->post(route('departments.store'), []);

        $response->assertSessionHasErrors('name');
    }

    public function test_guest_cannot_store_department()
    {
        $response = $this->post(route('departments.store'), [
            'name' => 'Sample Department',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_departments_list_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $departments = Department::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        foreach ($departments as $department) {
            $designations = Designation::factory()
                ->count(2)
                ->create(['department_id' => $department->id]);

            foreach ($designations as $designation) {
                Employee::factory()
                    ->count(3)
                    ->create(['designation_id' => $designation->id]);
            }
        }

        $response = $this->actingAs($user)
            ->withSession(['company_id' => $company->id])
            ->get(route('departments.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Departments');

        foreach ($departments as $department) {
            $designationCount = $department->designations()->count();
            $employeeCount = $department->designations()->withCount('employees')->get()->sum('employees_count');

            $response->assertSeeText($department->name);
            $response->assertSeeText((string) $designationCount);
            $response->assertSeeText((string) $employeeCount);
        }
    }

    public function test_user_cannot_access_departments_list_page_without_selected_company()
    {
        $user = User::factory()->create();
        $previousUrl = '/dashboard';
        $response = $this->actingAs($user)->from($previousUrl)->get(route('departments.index'));

        $response->assertRedirect($previousUrl);
        $response->assertSessionHas('error', 'Please select a company first.');
    }

    public function test_edit_department_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        // Create a department under that company
        $department = Department::factory()->create([
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['company_id' => $company->id])
            ->get(route('departments.edit', $department->id));

        $response->assertStatus(200);
        $response->assertSeeText('Edit Department'); // Adjust based on your blade content
        $response->assertSee($department->name);
    }

    public function test_user_cannot_access_edit_department_page_without_selected_company()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $department = Department::factory()->create([
            'company_id' => $company->id,
        ]);

        $previousUrl = '/dashboard';

        $response = $this->actingAs($user)->from($previousUrl)
            ->get(route('departments.edit', $department->id));

        $response->assertRedirect($previousUrl);
        $response->assertSessionHas('error', 'Please select a company first.');
    }

    public function test_department_can_be_updated()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $department = Department::factory()->create([
            'name' => 'Old Name',
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['company_id' => $company->id])
            ->patch(route('departments.update', $department->id), [
                'name' => 'Updated Name',
            ]);

        $response->assertRedirect(route('departments.index'));
        $response->assertSessionHas('success', 'Department updated successfully.');

        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_update_validation_fails_without_name()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        $department = Department::factory()->create([
            'name' => 'HR',
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['company_id' => $company->id])
            ->patch(route('departments.update', $department->id), []); // no name

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
            'name' => 'HR'
        ]);
    }

    public function test_delete_a_department_and_redirects_with_success_message()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();

        
        $user->companies()->attach($company->id);
        
        $department = Department::factory()->create([
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['company_id' => $company->id])
            ->delete(route('departments.delete', $department->id));

        $this->assertDatabaseMissing('departments', [
            'id' => $department->id,
        ]);

        $response->assertRedirect(route('departments.index'));
        $response->assertSessionHas('success', 'Department deleted successfully.');
    }
}
