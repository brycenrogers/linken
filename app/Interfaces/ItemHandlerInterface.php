<?php

namespace App\Interfaces;

interface ItemHandlerInterface
{
    public function create($inputs);
    public function destroy($id);
}