<?php
namespace App\Traits;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
trait ApiRequestValidationTrait
{
    use ResponseTrait;
    private function processRequest(Request $request, string $formRequestClass)
    {
        $validator = Validator::make($request->all(), (new $formRequestClass())->rules());
         if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY));
        }
        return  $validator->validated();
    }
}
