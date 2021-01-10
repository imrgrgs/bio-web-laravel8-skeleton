<?php

namespace App\Http\Controllers\API;

use Response;
use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Resources\ServerResource;
use App\Repositories\ServerRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ServerResourceCollection;
use App\Http\Requests\API\CreateServerAPIRequest;
use App\Http\Requests\API\UpdateServerAPIRequest;
use App\Services\CreateServerService;

/**
 * Class ServerController
 * @package App\Http\Controllers\API
 */

class ServerAPIController extends AppBaseController
{
    /** @var  ServerRepository */
    private $serverRepository;

    public function __construct(ServerRepository $serverRepo)
    {
        $this->serverRepository = $serverRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/servers",
     *      summary="Get a listing of the Servers.",
     *      tags={"Server"},
     *      description="Get all Servers",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Server")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {

        $servers = $this->serverRepository->all(
            $request,
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(
            new ServerResourceCollection($servers),
            __('messages.retrieved', ['model' => __('models/servers.plural')])
        );
    }

    /**
     * @param CreateServerAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/servers",
     *      summary="Store a newly created Server in storage",
     *      tags={"Server"},
     *      description="Store Server",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Server that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Server")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Server"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateServerAPIRequest $request, CreateServerService $createServer)
    {
        $input = $request->all();

        $server = $createServer->make($input);

        return $this->sendResponse(
            new ServerResource($server),
            __('messages.saved', ['model' => __('models/servers.singular')])
        );
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/servers/{id}",
     *      summary="Display the specified Server",
     *      tags={"Server"},
     *      description="Get Server",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Server",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Server"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {


        /** @var Server $server */
        $server = $this->serverRepository->find($id);

        if (empty($server)) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/servers.singular')])
            );
        }

        return $this->sendResponse(
            new ServerResource($server),
            __('messages.retrieved', ['model' => __('models/servers.singular')])
        );
    }

    /**
     * @param int $id
     * @param UpdateServerAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/servers/{id}",
     *      summary="Update the specified Server in storage",
     *      tags={"Server"},
     *      description="Update Server",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Server",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Server that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Server")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Server"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateServerAPIRequest $request)
    {
        $input = $request->all();

        /** @var Server $server */
        $server = $this->serverRepository->find($id);

        if (empty($server)) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/servers.singular')])
            );
        }

        $server = $this->serverRepository->update($input, $id);

        return $this->sendResponse(
            new ServerResource($server),
            __('messages.updated', ['model' => __('models/servers.singular')])
        );
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/servers/{id}",
     *      summary="Remove the specified Server from storage",
     *      tags={"Server"},
     *      description="Delete Server",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Server",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Server $server */
        $server = $this->serverRepository->find($id)->delete();

        if (empty($server)) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/servers.singular')])
            );
        }



        return $this->sendResponse(
            $id,
            __('messages.deleted', ['model' => __('models/servers.singular')])
        );
    }
}
