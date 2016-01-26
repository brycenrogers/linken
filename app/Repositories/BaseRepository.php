<?php

namespace App\Repositories;

abstract class BaseRepository {

    /**
	 * The Model instance.
	 *
	 * @var \Illuminate\Database\Eloquent\Model
	 */
	protected $model;

	/**
	 * Destroy a model.
	 *
	 * @param  int $id
	 * @throws \Exception
	 */
	public function destroy($id)
	{
        // Make sure they own it
		$obj = $this->getById($id);
        if ($obj->user != Auth::user()) {
            throw new \Exception('ACL error while deleting');
        }

		// Delete subclass
		$obj->itemable->delete();

        // Delete
        $obj->delete();
	}

	/**
	 * Get Model by id.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function getById($id)
	{
		return $this->model->findOrFail($id);
	}

	/**
     * Get an Item from storage with optional relations
     *
     * @param $id
     * @param array $with
     * @return Item
     */
    public function get($id, $with = [])
    {
        if (count($with)) {
            return $this->model->where('id', '=', $id)->with($with)->first();
        }
        return $this->model->find($id);
    }

}