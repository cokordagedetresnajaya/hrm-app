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

class EmployeeControllerTest extends TestCase
{
    public function test_employees_list_screen_can_be_rendered()
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
        $employees = Employee::factory()->count(4)->create([
            'designation_id' => $designation->id
        ]);

        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->get(route('employees.index'));

        $response->assertStatus(200);
        $response->assertSeeText($company->name . ' Employees');

        foreach ($employees as $employee) {
            $response->assertSeeText($employee->name);
        }
    }

    public function test_create_employee_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);

        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->get(route('employees.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.employees.create');
        $response->assertViewHasAll([
            'title',
            'departments'
        ]);

        $this->assertEquals('Create Employee', $response->viewData('title'));
    }

    public function test_user_can_create_new_employee()
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
        ])->post(route('employees.store'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'address' => 'Sample Address',
            'designation_id' => $designation->id
        ]);

        $this->assertDatabaseHas('employees', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'address' => 'Sample Address',
            'designation_id' => $designation->id
        ]);

        $response->assertRedirect(route('employees.index'));
        $response->assertSessionHas('success', 'Employee created successfully.');
    }

    public function test_fails_validation_when_create_new_employee()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);

        // failed in required rule
        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->post(route('employees.store'), []);

        $response->assertSessionHasErrors(['name','email','phone','address','designation_id']);
    
        // failed in required rule
        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->post(route('employees.store'), [
            'name' => 'John Doe',
            'email' => 'johndoe',
            'phone' => '1234567890',
            'address' => 'Sample Address',
            'designation_id' => 9999
        ]);

        $response->assertSessionHasErrors(['email','designation_id']);
    }

    public function test_edit_employee_screen_can_be_rendered()
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

        $employee = Employee::factory()->create([
            'designation_id' => $designation->id
        ]);

        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->get(route('employees.edit', $employee->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.employees.edit');
        $response->assertViewHasAll([
            'title',
            'employee',
            'departments',
            'designations'
        ]);

        foreach ($response->viewData('designations') as $designation) {
            $this->assertEquals($department->id, $designation->department_id);
        }

        $response->assertSee('Edit Employee');
        $this->assertTrue($response->viewData('employee')->is($employee));
    }

    public function test_update_employee_with_valid_data()
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

        $designation2 = Designation::factory()->create([
            'department_id' => $department->id
        ]);

        $employee = Employee::factory()->create([
            'designation_id' => $designation->id
        ]);

        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->patch(route('employees.update', $employee->id), [
            'name' => $employee->name . ' Update',
            'email' => 'sample@example.com',
            'phone' => '0123456789',
            'address' => 'Sample Address',
            'designation_id' => $designation2->id
        ]);

        $response->assertRedirect(route('employees.index'));
        $response->assertSessionHas('success', 'Employee updated successfully.');
        $this->assertDatabaseHas('employees', [
            'name' => $employee->name . ' Update',
            'email' => 'sample@example.com',
            'phone' => '0123456789',
            'address' => 'Sample Address',
            'designation_id' => $designation2->id
        ]);
    }

    public function test_fails_validation_when_update_employee()
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

        $employee = Employee::factory()->create([
            'designation_id' => $designation->id
        ]);

        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->patch(route('employees.update', $employee->id), []);

        $response->assertSessionHasErrors(['name','email','phone','address','designation_id']);

        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->post(route('employees.update', $employee->id), [
            'name' => 'John Doe',
            'email' => 'johndoe',
            'phone' => '1234567890',
            'address' => 'Sample Address',
            'designation_id' => 9999
        ]);

        $response->assertSessionHasErrors(['email','designation_id']);
    }

    public function test_can_delete_employee()
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

        $employee = Employee::factory()->create([
            'designation_id' => $designation->id
        ]);

        $response = $this->actingAs($user)->withSession([
            'company_id' => $company->id
        ])->delete(route('employees.delete', $employee->id));

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);

        $response->assertRedirect(route('employees.index'));
        $response->assertSessionHas('success', 'Employee deleted successfully.');
    }
}
