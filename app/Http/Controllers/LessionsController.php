<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Providers\UtilityServiceProvider as u;

class LessionsController extends Controller
{
    public function list(Request $request)
    {
        $date = isset($request->date) ? $request->date : '';
        
        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        if($date!==''){
            $cond .= " AND l.date= '$date'";
        }
        $total = u::first("SELECT count(DISTINCT t.id) AS total 
            FROM law_teacher_info AS t 
                LEFT JOIN  law_lessions AS l ON t.id=l.teacher_id
            WHERE l.status=1 AND $cond");
        $list = u::query("SELECT DISTINCT t.id, t.title, t.intro_text ,t.stt
            FROM law_teacher_info AS t 
                LEFT JOIN  law_lessions AS l ON t.id=l.teacher_id
            WHERE l.status=1 AND $cond ORDER BY t.stt DESC $limitation");
        $data = u::makingPagination($list, $total->total, $page, $limit);
        return response()->json($data);
    }
}
