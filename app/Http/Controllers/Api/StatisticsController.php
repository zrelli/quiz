<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
class StatisticsController extends AppBaseController
{
    public function index()
    {
      $statistics = DB::table('members')
      ->selectRaw('COUNT(*) as total_members')
      ->whereNotNull('average_score')
      ->whereNotNull('average_spent_time')
      ->whereNotNull('total_attempts')
      ->whereNotNull('successful_attempts')
      ->whereNotNull('failed_attempts')
      ->selectRaw('AVG(average_score) as average_score')
      ->selectRaw('AVG(average_spent_time) as average_spent_time')
      ->selectRaw('COUNT(total_attempts) as total_attempts')
      ->selectRaw('COUNT(successful_attempts) as successful_attempts')
      ->selectRaw('COUNT(failed_attempts) as failed_attempts')
      ->first();
        return $this->sendResponse($statistics);
    }
}
