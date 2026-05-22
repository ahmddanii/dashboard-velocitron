<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TransactionRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TransactionHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_transaction_history()
    {
        Role::firstOrCreate(['name' => 'procurement-director', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('procurement-director');

        TransactionRequest::create([
            'requester_id' => $user->id,
            'request_type' => 'procurement',
            'title' => 'Test History Request',
            'sales' => 1000,
            'quantity' => 10,
            'discount' => 0.1,
            'shipping_days' => 5,
            'category' => 'Technology',
            'segment' => 'Corporate',
            'region' => 'West',
            'ship_mode' => 'Standard Class',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('transactions.history'));

        $response->assertStatus(200);
        $response->assertViewIs('transactions.history');
        $response->assertSee('Test History Request');
    }

    public function test_head_analytics_can_export_transactions()
    {
        Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $response = $this->actingAs($admin)->get(route('transactions.export'));

        $response->assertStatus(200);
        $this->assertStringContainsString('attachment; filename=transaction-report-', $response->headers->get('content-disposition'));
    }

    public function test_head_analytics_can_download_import_template()
    {
        Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $response = $this->actingAs($admin)->get(route('transactions.template'));

        $response->assertStatus(200);
        $this->assertStringContainsString('attachment; filename=transaction-import-template.csv', $response->headers->get('content-disposition'));
    }

    public function test_head_analytics_can_export_analytics()
    {
        Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $response = $this->actingAs($admin)->get(route('analytics.export'));

        $response->assertStatus(200);
        $this->assertStringContainsString('attachment; filename=dss-monitoring-report-', $response->headers->get('content-disposition'));
    }

    public function test_head_analytics_can_import_transactions()
    {
        Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $csvContent = "Type,Title,Description,Sales,Quantity,Discount,Shipping Days,Category,Segment,Region,Ship Mode\n"
                    . "procurement,Test Import,Desc,1000,10,0.1,5,Technology,Corporate,West,Standard Class";
        
        $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

        $response = $this->actingAs($admin)->from(route('transactions.history'))->post(route('transactions.import'), [
            'csv_file' => $file,
        ]);

        if (session('error')) {
            dump(session('error'));
        }
        $response->assertSessionHasNoErrors();
        $response->assertSessionMissing('error');
        $response->assertRedirect(route('transactions.history', ['tab' => 'imported']));
        $this->assertDatabaseHas('transaction_requests', [
            'title' => 'Test Import',
            'is_imported' => true,
        ]);
    }

    public function test_head_analytics_can_clear_imported_transactions()
    {
        Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        TransactionRequest::create([
            'requester_id' => $admin->id,
            'request_type' => 'procurement',
            'title' => 'Imported Request to clear',
            'sales' => 1000,
            'quantity' => 10,
            'discount' => 0.1,
            'shipping_days' => 5,
            'category' => 'Technology',
            'segment' => 'Corporate',
            'region' => 'West',
            'ship_mode' => 'Standard Class',
            'status' => 'pending',
            'is_imported' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('transactions.import.clear'));

        $response->assertRedirect(route('transactions.history', ['tab' => 'imported']));
        $this->assertDatabaseMissing('transaction_requests', [
            'title' => 'Imported Request to clear',
        ]);
    }

    // ==========================================
    // CSV Upload Edge Case Tests
    // ==========================================

    public function test_import_rejects_non_csv_file()
    {
        Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($admin)->from(route('transactions.history'))->post(route('transactions.import'), [
            'csv_file' => $file,
        ]);

        $response->assertRedirect(route('transactions.history'));
        $response->assertSessionHasErrors('csv_file');
    }

    public function test_import_rejects_empty_csv()
    {
        Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        // CSV with only headers, no data rows
        $csvContent = "Title,Type,Sales,Quantity,Discount,Shipping Days,Category,Segment,Region,Ship Mode\n";
        $file = UploadedFile::fake()->createWithContent('empty.csv', $csvContent);

        $response = $this->actingAs($admin)->from(route('transactions.history'))->post(route('transactions.import'), [
            'csv_file' => $file,
        ]);

        $response->assertRedirect(route('transactions.history'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('transaction_requests', 0);
    }

    public function test_import_rejects_csv_missing_required_columns()
    {
        Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        // CSV missing the required 'Title' and 'Sales' columns
        $csvContent = "Quantity,Discount,Shipping Days\n10,0.1,5";
        $file = UploadedFile::fake()->createWithContent('bad_headers.csv', $csvContent);

        $response = $this->actingAs($admin)->from(route('transactions.history'))->post(route('transactions.import'), [
            'csv_file' => $file,
        ]);

        $response->assertRedirect(route('transactions.history'));
        $response->assertSessionHas('error');
    }

    public function test_import_without_file_returns_validation_error()
    {
        Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $response = $this->actingAs($admin)->from(route('transactions.history'))->post(route('transactions.import'), []);

        $response->assertRedirect(route('transactions.history'));
        $response->assertSessionHasErrors('csv_file');
    }

    public function test_import_csv_with_multiple_rows()
    {
        Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        $csvContent = "Type,Title,Description,Sales,Quantity,Discount,Shipping Days,Category,Segment,Region,Ship Mode\n"
                    . "procurement,Item A,Desc A,500,5,0.05,3,Technology,Corporate,West,Standard Class\n"
                    . "procurement,Item B,Desc B,750,8,0.10,4,Furniture,Consumer,East,Second Class\n"
                    . "procurement,Item C,Desc C,1200,15,0.15,2,Office Supplies,Home Office,Central,First Class\n";

        $file = UploadedFile::fake()->createWithContent('multi_row.csv', $csvContent);

        $response = $this->actingAs($admin)->from(route('transactions.history'))->post(route('transactions.import'), [
            'csv_file' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionMissing('error');
        $response->assertRedirect(route('transactions.history', ['tab' => 'imported']));

        $this->assertDatabaseHas('transaction_requests', ['title' => 'Item A', 'is_imported' => true]);
        $this->assertDatabaseHas('transaction_requests', ['title' => 'Item B', 'is_imported' => true]);
        $this->assertDatabaseHas('transaction_requests', ['title' => 'Item C', 'is_imported' => true]);
        $this->assertEquals(3, TransactionRequest::where('is_imported', true)->count());
    }

    public function test_import_csv_with_semicolon_delimiter()
    {
        Role::firstOrCreate(['name' => 'head-analytics', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('head-analytics');

        // Semicolon-delimited CSV (common in European locales)
        $csvContent = "Type;Title;Description;Sales;Quantity;Discount;Shipping Days;Category;Segment;Region;Ship Mode\n"
                    . "procurement;Semicolon Item;Test;800;6;0.1;5;Technology;Corporate;West;Standard Class\n";

        $file = UploadedFile::fake()->createWithContent('semicolon.csv', $csvContent);

        $response = $this->actingAs($admin)->from(route('transactions.history'))->post(route('transactions.import'), [
            'csv_file' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionMissing('error');
        $this->assertDatabaseHas('transaction_requests', ['title' => 'Semicolon Item', 'is_imported' => true]);
    }

    public function test_guest_cannot_import_csv()
    {
        $csvContent = "Type,Title,Sales,Quantity\nprocurement,Guest Item,100,1\n";
        $file = UploadedFile::fake()->createWithContent('guest.csv', $csvContent);

        $response = $this->post(route('transactions.import'), [
            'csv_file' => $file,
        ]);

        $response->assertRedirect('/login');
    }
}
