<?php
namespace App\Http\Controllers\Api\Admin;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
class StatisticsController extends AppBaseController
{
    public function index()
    {
      $statistics = DB::table('members')
      ->selectRaw('COUNT(*) as total_members')
      ->whereNotNull('score')
      ->whereNotNull('time_taken')
      ->whereNotNull('total_attempts')
      ->whereNotNull('successful_attempts')
      ->whereNotNull('failed_attempts')
      ->selectRaw('AVG(score) as score')
      ->selectRaw('AVG(time_taken) as time_taken')
      ->selectRaw('COUNT(total_attempts) as total_attempts')
      ->selectRaw('COUNT(successful_attempts) as successful_attempts')
      ->selectRaw('COUNT(failed_attempts) as failed_attempts')
      ->first();
        return $this->sendResponse($statistics);
    }
}
