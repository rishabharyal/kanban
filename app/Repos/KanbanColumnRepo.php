<?php

namespace App\Repos;

use App\Models\KanbanColumn;
use Illuminate\Support\Facades\Auth;

class KanbanColumnRepo {

    /**
     * @var KanbanColumn
     */
    private $kanbanColumnModel;

    /**
     * KanbanColumnRepo constructor.
     * @param KanbanColumn $columnModel
     */
    public function __construct(KanbanColumn $columnModel) {
		$this->kanbanColumnModel = $columnModel;
	}

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->kanbanColumnModel
            ->with('cards:id,card_order,title,description,kanban_column_id')
            ->where('user_id', Auth::id())
            ->get(['id', 'title'])
            ->toArray();

	}

    /**
     * @param $title
     * @return mixed
     */
    public function create($title) {
        return $this->kanbanColumnModel->create([
            'title' => $title,
            'user_id' => Auth::id()
        ]);
    }

    /**
     * @param $id
     * @param $title
     * @return bool
     */
    public function update($id, $title): bool
    {
        $column = $this->kanbanColumnModel->find($id);
        if ($column) {
            $column->update([
                'title' => $title
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
        $column = $this->kanbanColumnModel->find($id);
        if ($column) {
            $column->cards()->delete();
            $column->delete();
        }

        return true;
    }
}
