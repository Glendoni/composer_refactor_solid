<?php

namespace App\Repositories;

use App\Study_item;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudyItemRepository
{

    protected $studyItem;

    public function __construct(Study_item $studyItem)
    {
        $this->studyItem = $studyItem;
    }

    public function create($attributes)
    {
        return $this->studyItem->create($attributes);
    }
    public function update($id, array $attributes)
    {
        return $this->studyItem->find($id)->update($attributes);
    }

    public function where_study_name($id, $name)
    {
        return $this->studyItem->where([['name', $name], ['study_id', $id]])->get();
    }
    public function where_study_id($id)
    {
        return $this->study->find($id);
    }

    public function studyItemListing($id)
    {

        return DB::table('study_items')
            ->whereRaw('study_id =' . $id .
                ' and id not in (select study_items_id from study_item_accesses  where  value = false and study_id= ' . $id . ')')
            ->select('name', 'id', 'study_id')
            ->get();
    }

    public function formStudyItemListings($id)
    {
        return DB::table('study_items')
            ->whereRaw('id  in (select sia.study_items_id 
                   from study_item_accesses sia
                   join studies s
ON sia.study_id = s.id 
                   where sia.user_id= ' . Auth::id() . ' 
                   and sia.value= true 
                   and sia.study_id= ' . $id . ' and s.start_date  < (CURRENT_DATE+1)
and (s.end_date   >  (CURRENT_DATE+1) or s.end_date  is null))')
            ->select('name', 'id', 'study_id')
            ->get();
    }

    public function formStudyItemListing()
    {
        return User::find(Auth::id())->sublist()
            ->where('accesses.user_id', Auth::id())
            ->get();
    }


    function study_item_access($attributes)
    {
        // try  catch
        foreach ($attributes['options'] as $item) {
            unset($item['name']);
            DB::table('study_item_accesses')
                ->updateOrInsert(
                    ['study_items_id' => $item['study_items_id'],
                        'study_id' => $item['study_id'],
                        'user_id' => $item['user_id']],
                    $item
                );
        }

        return true;
    }

    function study_item_accesses_delete_null()
    {
        DB::table('study_item_accesses')
            ->where('value', '=', null)->delete();
    }

    function study_item_accesses_delete_false()
    {
        DB::table('study_item_accesses')
            ->where('value', '=', false)->delete();
    }
}
