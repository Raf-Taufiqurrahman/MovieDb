<?php

namespace App\Http\Controllers\Admin;

use App\Models\Genre;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class GenreController extends Controller
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

        // get all genre with paginate and search
        $genres = Genre::when($search, function($query) use($search){
            $query = $query->where('name', 'like', '%'.$search.'%');
        })->paginate(6)->withQueryString();

        return view('admin.genre.index', compact('genres', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // get genre from tmdb api
        $genre = Http::withToken(config('services.mdb.token'))->get('https://api.themoviedb.org/3/genre/movie/list');

        // if genre success
        if($genre->successful()){
            // make genre to json
            $genreJson = $genre->json();
            // foreach genre
            foreach($genreJson as $dataGenre){
                // foreach data genre when create
                foreach($dataGenre as $nameGenre){
                    // check if tmbd_id is exist or not
                    $genre = Genre::where('tmdb_id', $nameGenre['id'])->first();
                    // if not exist, create new genre
                    if(!$genre){
                        Genre::create([
                            'tmdb_id' => $nameGenre['id'],
                            'name' => $nameGenre['name'],
                        ]);
                    }
                }
            }
            // return back with success message
            return back()->with('toast_success', 'Genre created successfully');
        }else{
            // if genre failed, return back with error message
            return back()->with('toast_error', 'Genre not found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Genre $genre)
    {
        // update genre by id
        $genre->update($request->all());

        return back()->with('toast_success', 'Genre updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Genre $genre)
    {
        // delete genre by id
        $genre->delete();

        return back()->with('toast_success', 'Genre deleted successfully');
    }
}
