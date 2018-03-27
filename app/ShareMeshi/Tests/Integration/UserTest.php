<?php

namespace Tests\Unit;

use App\ShareMeshi\Tests\ShareMeshiTestCase;
use App\Product;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends ShareMeshiTestCase
{
    // when you insert to the db it will just rollback after test
    //use DatabaseTransactions;
    //use DatabaseMigrations;
    //use WithoutMiddleware;

    private $product;

    public function setUp()
    {
        parent::setUp();

        $this->product = factory(Product::class)->create();

    }

    /** @test */
    public function shouldHaveCustomerIdWhenRoleIsCustomer()
    {

        $timestamp = Carbon::now()->timestamp;

        $request = $this->adminPost('/api/users', [
            'email' => $timestamp . time() . str_random() . '@email.com',
            'password' => 'testing',
            'firstname' => $timestamp . 'Test',
            'lastname' => $timestamp . 'Test',
            'role' => 'member',
            'permissions' => [],
            'branch_id' => $this->branchId
        ]);

        $response = $request->json();

        $request->assertJsonStructure(['customer_id'], $response['data']);

        $customerId = $response['data']['customer_id'];

        $this->assertNotNull($customerId);


    }

    /** @test */
    public function shouldHaveStaffIdWhenRoleIsStaff()
    {

        $timestamp = Carbon::now()->timestamp;

        $request = $this->adminPost('/api/users', [
            'email' => $timestamp . time() . str_random() . '@email.com',
            'password' => 'testing',
            'firstname' => $timestamp . 'Test',
            'lastname' => $timestamp . 'Test',
            'role' => 'staff',
            'permissions' => [],
            'branch_id' => $this->branchId
        ]);

        $response = $request->json();

        $request->assertJsonStructure(['staff_id'], $response['data']);

        $staffId = $response['data']['staff_id'];



        $this->assertNotNull($staffId);


    }

    /** @test */
    public function whenGettingUsersByRoleShouldHaveJsonStructure()
    {

        $request = $this->adminGet('/api/users?role=admin');

        $response = $request->json();

        if (!empty($response['data'])) {

            $request->assertJsonStructure([
                'id',
                'firstname',
                'lastname',
                'staff_id',
                'customer_id',
                'branch_name',
                'branch_id',
                'email',
                'phone',
                'address',
                'city',
                'province',
                'zip',
                'status',
                'branch_id_registered'
            ], $response['data'][0]);
        }

    }

    /** @test */
    public function getAllUsersMustHaveThisJsonStructure()
    {

        $request = $this->adminGet('/api/users');

        $response = $request->json();

        if (!empty($response['data'][0])) {

            $request->assertJsonStructure([
                'id',
                'firstname',
                'lastname',
                'staff_id',
                'customer_id',
                'branch_name',
                'branch_id',
                'email',
                'phone',
                'address',
                'city',
                'province',
                'zip',
                'status',
                'branch_id_registered',
                'permission_names'
            ], $response['data'][0]);
        }

    }

    /** @test */
    public function getUserByIdMustHaveJsonStructure()
    {

        $request = $this->adminGet('/api/users/1');

        $response = $request->json();

        if (!empty($response['data'])) {

            $request->assertJsonStructure([
                'id',
                'firstname',
                'lastname',
                'staff_id',
                'customer_id',
                'branch_name',
                'branch_id',
                'email',
                'phone',
                'address',
                'city',
                'province',
                'zip',
                'status',
                'branch_id_registered'
            ], $response['data']);
        }

    }
}
