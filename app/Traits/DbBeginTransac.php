<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait DbBeginTransac{
    public function executeTransaction(callable $callback){
        DB::beginTransaction();
        try {
            $result = $callback();
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}