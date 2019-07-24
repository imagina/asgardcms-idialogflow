<?php

namespace Modules\Idialogflow\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Idialogflow\Services\MessageService;
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;

class MessageController extends BaseApiController
{

  private $messageService;

  public function __construct(MessageService $messageService)
  {
    $this->messageService = $messageService;
  }

  /**
   * Display a listing of the resource.
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $params = $this->getParamsRequest($request);
      $data = $this->messageService->getMessages($params);
      $response = ["data" => $data];
    } catch (\Exception $e) {
      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }
    return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
  }

  /**
   * Store a newly created resource in storage.
   * @param  Request $request
   * @return Response
   */
  public function store(Request $request)
  {
    try {
      $data = $request->input('attributes') ?? [];
      $item = $this->messageService->createMessage($data);
      $response = ["data" => ''];
    } catch (\Exception $e) {
      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }
    return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
  }

}
