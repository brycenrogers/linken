<?php

namespace App\Interfaces;

/**
 * Interface ItemHandlerInterface
 * @package App\Interfaces
 * @provider App\Providers\ItemHandlerServiceProvider
 */
interface ItemHandlerInterface
{
    public function create($inputs, $user);
    public function destroy($id);
    public function email($inputs);
    public function update($inputs);
}