<?php

namespace App\Http\Controllers\Admin;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
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

        // get all genres
        $genres = Genre::get();

        // get all cast with paginate and search
        $movies = Movie::with('genres')->when($search, function($query) use($search){
            $query = $query->where('name', 'like', '%'.$search.'%');
        })->paginate(6)->withQueryString();

        return view('admin.movie.index', compact('movies', 'search', 'genres'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check movie is exist or not
        $movie = Movie::where('tmdb_id', $request->tmdb_id)->first();

        // if exist, return error
        if($movie){
            return back()->with('toast_error', 'Movie already exist');
        }
        // get movie from tmdb api where movie tmdb_id same $request->tmdb_id
        $apiMovie = Http::withToken(config('services.mdb.token'))->get('https://api.themoviedb.org/3/movie/'.$request->tmdb_id);

        // if movie success, create new movie
        if($apiMovie->successful()){
            $createMovie = Movie::create([
                'tmdb_id' => $apiMovie['id'],
                'title' => $apiMovie['title'],
                'slug' => Str::slug($apiMovie['title']),
                'runtime' => $apiMovie['runtime'],
                'lang' => $apiMovie['original_language'],
                'overview' => $apiMovie['overview'],
                'poster' => $apiMovie['poster_path'],
            ]);

            // get movie genres
            $movieGenres = $apiMovie['genres'];
            // collect movie genres and pluck into id
            $movieGenreId = collect($movieGenres)->pluck('id');
            // get genre by tmbd_id same as movie genre id
            $genres = Genre::whereIn('tmdb_id', $movieGenreId)->get();
            // attach genre to movie
            $createMovie->genres()->attach($genres);

            return back()->with('toast_success', 'Movie created successfully');
        }else{
            return back()->with('toast_error', 'Movie not found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        // if request poster update poster
        if($request->file('poster')){
            $image = $request->file('poster');
            $image->storeAs('public/movies/', $image->hashName());

            $movie->update([
                'poster' => $image->hashName()
            ]);
        }

        // update movie by id
        $movie->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'runtime' => $request->runtime,
            'lang' => $request->lang,
            'overview' => $request->overview,
        ]);

        $movie->genres()->sync($request->genres);

        return back()->with('toast_success', 'Movie updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        // delete movie by id
        $movie->delete();

        // delete poster from storage
        Storage::disk('local')->delete('public/movies/'. basename($movie->poster));

        $movie->genres()->detach();

        return back()->with('toast_success', 'Cast deleted successfully');
    }
}
