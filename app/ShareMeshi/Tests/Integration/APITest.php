<?php

namespace Tests\Unit;

use App\ShareMeshi\Tests\ShareMeshiTestCase;
use App\Product;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APITest extends ShareMeshiTestCase
{
    // when you insert to the db it will just rollback after test
    //use DatabaseTransactions;
    //use DatabaseMigrations;
    //use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

    }

    /** @test */
    public function solution()
    {

        $A = [1,3,6,4,2];

        $uniqueArray = array_unique($A); // 1,3,6,4,2

        sort($uniqueArray); // 1,2,3,4,6

        $sortedArray = $uniqueArray;

        $length = count($sortedArray);

        $result = 0;

        $i = 0;
        while ($i < $length) {

            $currentItem = $sortedArray[$i]; // 1 2 4
            $nextPossibleItem = $currentItem + 1; // 2 3 5

            if ($i == $length - 1) { // 0 == 4, 1 == 4, 3==4
                break;
            }

            $nextItem = $sortedArray[$i + 1]; // 2 3 6

            if ($nextItem == $nextPossibleItem) { // 2 == 2, 3 == 3, 6 == 5
                $i++;
                continue;
            }

            //if(!$result){
            $result = $nextPossibleItem;
            break;
            //}
        }

        $this->assertEquals(5,$result);

    }

    /** @test */
    public function error500WhenNoToken()
    {
        $this->get('/api/products')
            ->assertStatus(400);
    }

    /** @test */
    public function error404WhenWrongUrl()
    {
        $request = $this->get('/api/alsjfdlsdf');

        $request->assertStatus(404);
    }

    /** @test */
    public function error500WhenUrlMethodNotPresent()
    {
        $request = $this->post('/api/products/asdf');

        $request->assertStatus(500);
    }

    /** @test */
    public function confirmJsonStructureWhenWrongUrl()
    {
        $request = $this->get('/api/alsjfdlsdf');
        $request->assertJsonStructure(['type', 'data', 'message'], $request->json());

        $request = $this->post('/api/products');
        $request->assertJsonStructure(['type', 'data', 'message'], $request->json());

        $request = $this->post('/api/products/test');
        $request->assertJsonStructure(['type', 'data', 'message'], $request->json());
    }

    /** @test */
    public function confirmJsonStructureWhenUrlMethodNotPresent()
    {
        $request = $this->post('/api/products/asdf');

        $request->assertJsonStructure(['type', 'data'], $request->json());
    }

}