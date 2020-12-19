<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repos\KanbanColumnRepo;
use Illuminate\Http\Request;

class KanbanColumnController extends Controller
{

    private $kanbanColumnRepo;

    public function __construct(KanbanColumnRepo $repo) {
        $this->kanbanColumnRepo = $repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse(
            $this->kanbanColumnRepo->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $column = $this->kanbanColumnRepo->create($request->get('title'));

        if ($column) {
            return $this->successResponse($this->kanbanColumnRepo->get());
        }

        return $this->errorResponse('The column could not be created');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $updateAction = $this->kanbanColumnRepo->update($id, $request->get('title'));

        if ($updateAction) {
            return $this->successResponse('success');
        }

        return $this->errorResponse('The column could not be updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse(
            $this->kanbanColumnRepo->delete($id)
        );
    }
}
