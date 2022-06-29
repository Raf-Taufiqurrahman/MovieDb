<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cast;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CastRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // define request search
        $search = $request->search;

        // get all cast with paginate and search
        $casts = Cast::when($search, function($query) use($search){
            $query = $query->where('name', 'like', '%'.$search.'%');
        })->paginate(6)->withQueryString();

        return view('admin.cast.index', compact('casts', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check cast tmdb_id is exist or not
        $data = Cast::where('tmdb_id', $request->tmdb_id)->first();

        // if exist, return back with error message
        if($data){
            return back()->with('toast_error', 'Cast already exists');
        }

        // get cast from tmdb api where person tmdb_id same $request->tmdb_id
        $cast = Http::withToken(config('services.mdb.token'))->get('https://api.themoviedb.org/3/person/'.$request->tmdb_id);

        // if cast success, create new cast
        if($cast->successful()){
            Cast::Create([
                'tmdb_id' => $cast['id'],
                'name' => $cast['name'],
                'slug' => Str::slug($cast['name']),
                'photo' => $cast['profile_path']
            ]);

            return back()->with('toast_succes', 'Cast created successfully');
        }else{
            // if cast failed, return back with error message
            return back()->with('toast_error', 'Cast not found');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CastRequest $request, Cast $cast)
    {
        // if request photo update photo and name
        if($request->file('photo')){
            $image = $request->file('photo');
            $image->storeAs('public/casts/', $image->hashName());

            $cast->update([
                'photo' => $image->hashName()
            ]);
        }

        // update name by id
        $cast->update([
            'name' => $request->name,
        ]);

        return back()->with('toast_success', 'Cast updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cast $cast)
    {
        // delete cast by id
        $cast->delete();

        // delete photo from storage
        Storage::disk('local')->delete('public/casts/'. basename($cast->photo));

        return back()->with('toast_success', 'Cast deleted successfully');
    }
}
