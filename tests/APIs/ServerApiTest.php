<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Server;

class ServerApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_server()
    {
        $server = Server::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/servers', $server
        );

        $this->assertApiResponse($server);
    }

    /**
     * @test
     */
    public function test_read_server()
    {
        $server = Server::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/servers/'.$server->id
        );

        $this->assertApiResponse($server->toArray());
    }

    /**
     * @test
     */
    public function test_update_server()
    {
        $server = Server::factory()->create();
        $editedServer = Server::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/servers/'.$server->id,
            $editedServer
        );

        $this->assertApiResponse($editedServer);
    }

    /**
     * @test
     */
    public function test_delete_server()
    {
        $server = Server::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/servers/'.$server->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/servers/'.$server->id
        );

        $this->response->assertStatus(404);
    }
}
