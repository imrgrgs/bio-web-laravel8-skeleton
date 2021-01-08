<?php namespace Tests\Repositories;

use App\Models\Server;
use App\Repositories\ServerRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ServerRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ServerRepository
     */
    protected $serverRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->serverRepo = \App::make(ServerRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_server()
    {
        $server = Server::factory()->make()->toArray();

        $createdServer = $this->serverRepo->create($server);

        $createdServer = $createdServer->toArray();
        $this->assertArrayHasKey('id', $createdServer);
        $this->assertNotNull($createdServer['id'], 'Created Server must have id specified');
        $this->assertNotNull(Server::find($createdServer['id']), 'Server with given id must be in DB');
        $this->assertModelData($server, $createdServer);
    }

    /**
     * @test read
     */
    public function test_read_server()
    {
        $server = Server::factory()->create();

        $dbServer = $this->serverRepo->find($server->id);

        $dbServer = $dbServer->toArray();
        $this->assertModelData($server->toArray(), $dbServer);
    }

    /**
     * @test update
     */
    public function test_update_server()
    {
        $server = Server::factory()->create();
        $fakeServer = Server::factory()->make()->toArray();

        $updatedServer = $this->serverRepo->update($fakeServer, $server->id);

        $this->assertModelData($fakeServer, $updatedServer->toArray());
        $dbServer = $this->serverRepo->find($server->id);
        $this->assertModelData($fakeServer, $dbServer->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_server()
    {
        $server = Server::factory()->create();

        $resp = $this->serverRepo->delete($server->id);

        $this->assertTrue($resp);
        $this->assertNull(Server::find($server->id), 'Server should not exist in DB');
    }
}
