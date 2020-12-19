<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repos\CardRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{

    /**
     * @var
     */
    private $cardRepo;

    public function __construct(CardRepo $cardRepo) {
        $this->cardRepo = $cardRepo;
    }

    public function sort(Request $request) {
        $this->validate($request, [
            'cards' => 'required|array'
        ]);

        $this->cardRepo->sortCards($request->get('cards'));

        return $this->successResponse('sorted');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'title' => 'required',
            'column_id' => 'required|integer'
        ]);

        $card = $this->cardRepo->create($request->get('title'), $request->get('column_id'));
        if (!$card) {
            $this->errorResponse('Could not create a new card.');
        }

        return $this->successResponse($card);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        $this->validate($request, [
            'title' => 'required',
            'column_id' => 'required|integer'
        ]);

        $update = $this->cardRepo->update($id, $request->get('title'), $request->get('description'), $request->get('column_id'));
        if (!$update) {
            $this->errorResponse('Could not update the card.');
        }

        return $this->successResponse('Card updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->successResponse(
            $this->cardRepo->delete($id)
        );
    }
}
