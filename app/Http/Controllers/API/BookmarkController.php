<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Traits\APITrait;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    use APITrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $bookmarks = Bookmark::All();
//        return $this->returnData(true , 'Get Data Successfully' , $bookmarks ,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->returnData(true , 'Add Bookmark Successes' , Bookmark::create($request->all() ,) , 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->returnData(true , 'get bookmarks for particular user' , Bookmark::where('user_id' , $id)->get() , 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        $bookmark = Bookmark::find($id);
//        $bookmark = $bookmark->update($request->all());
//        return response()->json($bookmark);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Bookmark::destroy($id);
        if($result){
            return $this->returnSuccessMessage('Delete Bookmark Success' , 200);
        }else {
            return $this->returnError('Something is wong',405);
        }
    }
}
