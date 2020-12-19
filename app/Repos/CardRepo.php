<?php

namespace App\Repos;

use App\Models\Card;
use Illuminate\Support\Facades\Auth;

class CardRepo {

    /**
     * @var Card
     */
    private $cardModel;

    /**
     * CardRepo constructor.
     * @param Card $columnModel
     */
    public function __construct(Card $cardModel) {
		$this->cardModel = $cardModel;
	}


	public function sortCards($cardIds) {
        $index = 1;
        foreach ($cardIds as $cardId) {
            $this->cardModel->where('id', $cardId)->update([
                'card_order' => $index
            ]);
            $index++;
        }
    }

    /**
     * @param $title
     * @param $columnId
     * @return mixed
     */
    public function create($title, $columnId) {
        return $this->cardModel->create([
            'title' => $title,
            'kanban_column_id' => $columnId,
            'card_order' => $this->getCardOrder($columnId) + 1
        ]);
    }

    private function getCardOrder($columnId) {
        return Card::where('kanban_column_id', $columnId)->max('card_order');
    }

    /**
     * @param $id
     * @param $title
     * @param string $description
     * @param null $columnId
     * @return bool
     */
    public function update($id, $title, $description = '', $columnId = null): bool
    {
        $card = $this->cardModel->find($id);
        if ($card) {
            $card->update([
                'title' => $title,
                'description' => $description,
                'kanban_column_id' => $columnId ?? $card->column_id,
            ]);
            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        $card = $this->cardModel->find($id);
        if ($card) {
            $card->delete();
        }

        return true;
    }
}
