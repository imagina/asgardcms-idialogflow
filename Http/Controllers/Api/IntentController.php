<?php

namespace Modules\Idialogflow\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Idialogflow\Services\IntentService;
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;

class IntentController extends BaseApiController
{

  private $intentService;

  public function __construct(IntentService $intentService)
  {
    $this->intentService = $intentService;
  }
  /**
   * Display a listing of the resource.
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $params = $this->getParamsRequest($request);
      $data = $this->intentService->getIntents($params);
      $response = ["data" => $data];
    } catch (\Exception $e) {
      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }
    return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
  }

  /**
  * Show the specified resource.
  * @return Response
  */
  public function show($intentId, Request $request)
  {
    try {
      $params = $this->getParamsRequest($request);
      $data = $this->intentService->getIntent($intentId, $params);
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
      $params = $this->getParamsRequest($request);
      $intent = $this->intentService->createIntent($data, $params);
      $response = ["data" => $intent];
    } catch (\Exception $e) {
      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }
    return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
  }

  /**
   * Update the specified resource in storage.
   * @param  Request $request
   * @return Response
   */
  public function update($intentId, Request $request)
  {
    try {
      $data = $request->input('attributes') ?? [];//Get data
      $params = $this->getParamsRequest($request);
      $intent = $this->intentService->updateIntent($intentId, $data, $params);
      $response = ["data" => $intent];
    } catch (\Exception $e) {
      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }
    return response()->json($response, $status ?? 200);
  }

  /**
   * Remove the specified resource from storage.
   * @return Response
   */
  public function destroy($intentId,  Request $request)
  {
    try {
      $params = $this->getParamsRequest($request);
      $this->intentService->DeleteIntent($intentId, $params);
      $response = ['data' => ''];
    } catch (\Exception $e) {
      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }
      return response()->json($response, $status ?? 200);
  }
}
