<?php

namespace Tests\Feature\Controller;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_valid_company_data_can_be_created()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('logo.jpg', 200, 200);

        $response = $this->actingAs($user)->from(route('companies.create'))->post('/companies/create', [
            'name' => 'Sample Company',
            'email' => 'samplecompany@example.com',
            'website' => 'https://samplecompany.com',
            'logo' => $file
        ]);

        $response->assertRedirect(route('companies.index'));

        $this->assertTrue(
            Storage::disk('public')->exists('companies/' . $file->hashName())
        );

        $this->assertDatabaseHas('companies', [
            'name' => 'Sample Company',
            'email' => 'samplecompany@example.com',
            'website' => 'https://samplecompany.com',
            'logo' => 'companies/' . $file->hashName()
        ]);
    }

    public function test_invalid_company_data_cant_be_created()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $invalidLogo = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->from(route('companies.create'))->post(route('companies.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'website' => 'invalid-url',
            'logo' => $invalidLogo,
        ]);

        // Assert redirect back to form
        $response->assertRedirect(route('companies.create'));

        // Assert session contains validation errors
        $response->assertSessionHasErrors(['name', 'email', 'website', 'logo']);

        // Assert no company is created
        $this->assertDatabaseCount('companies', 0);

        $this->assertFalse(
            Storage::disk('public')->exists('companies/' . $invalidLogo->hashName())
        );
    }

    public function test_company_can_be_updated_with_new_logo()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        // Create a company with an existing logo
        $oldLogo = UploadedFile::fake()->image('old_logo.jpg');
        $oldLogoPath = $oldLogo->store('companies', 'public');

        $company = Company::factory()->create([
            'name' => 'Old Company',
            'email' => 'old@example.com',
            'website' => 'https://old.com',
            'logo' => $oldLogoPath,
        ]);

        // Create a new logo file for update
        $newLogo = UploadedFile::fake()->image('new_logo.jpg');

        // Perform update as logged-in user
        $response = $this->actingAs($user)->patch(route('companies.update', $company->id), [
            'name' => 'Updated Company',
            'email' => 'updated@example.com',
            'website' => 'https://updated.com',
            'logo' => $newLogo,
        ]);

        // Check redirection
        $response->assertRedirect(route('companies.index'));

        // Assert old logo is deleted
        $this->assertFalse(
            Storage::disk('public')->exists($oldLogoPath)
        );

        // Assert new logo is stored
        $this->assertTrue(
            Storage::disk('public')->exists('companies/' . $newLogo->hashName())
        );

        // Assert updated company data in DB
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Updated Company',
            'email' => 'updated@example.com',
            'website' => 'https://updated.com',
            'logo' => 'companies/' . $newLogo->hashName(),
        ]);
    }

    public function test_company_can_be_updated_without_changing_logo()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        // Store an initial logo
        $initialLogo = UploadedFile::fake()->image('initial.jpg');
        $logoPath = $initialLogo->store('companies', 'public');

        $company = Company::factory()->create([
            'name' => 'Original Company',
            'email' => 'original@example.com',
            'website' => 'https://original.com',
            'logo' => $logoPath,
        ]);

        // Send PATCH request without new logo
        $response = $this->actingAs($user)->patch(route('companies.update', $company->id), [
            'name' => 'Updated Without Logo',
            'email' => 'new@example.com',
            'website' => 'https://new.com'
        ]);

        $response->assertRedirect(route('companies.index'));

        // Assert old logo still exists
        $this->assertTrue(
            Storage::disk('public')->exists($logoPath)
        );

        // Assert DB is updated but logo is unchanged
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Updated Without Logo',
            'email' => 'new@example.com',
            'website' => 'https://new.com',
            'logo' => $logoPath,
        ]);
    }

    public function test_company_update_fails_with_invalid_logo()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $company = Company::factory()->create();

        // Upload invalid file type
        $invalidFile = UploadedFile::fake()->create('file.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->from(route('companies.edit', $company->id))->patch(route('companies.update', $company->id), [
            'name' => 'Invalid Logo Test',
            'email' => 'invalid@example.com',
            'website' => 'https://invalid.com',
            'logo' => $invalidFile,
        ]);

        // Assert redirect back and validation error
        $response->assertRedirect(route('companies.edit', $company->id));
        $response->assertSessionHasErrors(['logo']);

        // Assert company not updated
        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
            'name' => 'Invalid Logo Test',
        ]);
    }

    public function test_company_can_be_deleted_and_logo_removed()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        // Create a fake logo and store it
        $logo = UploadedFile::fake()->image('logo.jpg');
        $logoPath = $logo->store('companies', 'public');

        $company = Company::factory()->create([
            'logo' => $logoPath,
        ]);

        // Ensure the file exists
        $this->assertTrue(
            Storage::disk('public')->exists($logoPath)
        );

        // Send DELETE request as logged-in user
        $response = $this->actingAs($user)->delete(route('companies.delete', $company->id));

        // Assert redirection
        $response->assertRedirect(route('companies.index'));

        // Assert logo is deleted
        $this->assertFalse(
            Storage::disk('public')->exists($logoPath)
        );

        // Assert company is deleted
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    public function test_delete_non_existent_company_gracefully_fails()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('companies.delete', 99999));
        $response->assertStatus(404);
        $response->assertSessionHasNoErrors();
    }

    public function test_delete_non_existent_company_returns_404()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->delete(route('companies.delete', 999999))
            ->assertNotFound();
    }
}
