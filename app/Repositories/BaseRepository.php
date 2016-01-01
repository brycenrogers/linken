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
     * Current User instance
     *
     * @var \App\Models\User
     */
    protected $user;

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
        if ($obj->user != $this->user) {
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

}