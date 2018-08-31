<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponse {

      protected function successResponse($data, $code) {

            return response()->json($data,
                            $code);
      }

      protected function errorResponse($message, $code) {

            return response()->json(['error' => $message, 'code' => $code],
                            $code);
      }

      protected function showAll(Collection $collection, $code = 200) {
            return $this->successResponse(['data' => $collection],
                            $code);
      }

      protected function showOne(Model $model, $code=201) {
            return $this->successResponse(['data' => $model],
                            $code);
      }

}
