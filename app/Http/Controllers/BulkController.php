<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
class BulkController extends Controller
{
    // get record by id
    public function get(Request $r, $id)
    {
        $tbl = $r->tbl;
        $rec = DB::table($tbl)->find($id);
        
        return json_encode($rec);
    }
    // save item to a table
    public function save(Request $r)
    {
        $per = $r->per;
        $tbl = $r->tbl;
        if(!check($per, 'i')){
            return 0;
        }
        $data = $r->except('_token', 'per', 'tbl');
        $data['created_by'] = Auth::user()->id;
        $i = DB::table($tbl)->insert($data);
        return (int)$i;
    }
    // update item to table
    public function update(Request $r)
    {
        $per = $r->per;
        $tbl = $r->tbl;
        $id = $r->id;
        if(!check($per, 'u')){
            return 0;
        }
        $data = $r->except('_token', 'per', 'tbl', 'id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = Auth::user()->id;
        $i = DB::table($tbl)
            ->where('id', $id)
            ->update($data);
        return (int)$i;
    }
    // delete item
    public function delete(Request $r, $id)
    {
        $per = $r->per;
        $tbl = $r->tbl;
        if(!check($per, 'd'))
        {
            return 0;
        }
        $i = DB::table($tbl)
            ->where('id', $id)
            ->update(['active'=>0]);
        if($i)
        {
            return 1;
        }
        else{
            return 0;
        }
    }
    public function remove(Request $r)
    {
        $arr = $r->ids;
        $per = $r->per;
        $tbl = $r->tbl;
        if(!check($per, 'd'))
        {
            return 0;
        }
        $i = DB::table($tbl)
            ->whereIn('id', $arr)
            ->update(['active'=>0]);
        if($i)
        {
            return 1;
        }
        else{
            return 0;
        }
    }
}
