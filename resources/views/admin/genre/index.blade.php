@extends('layouts.master', ['title' => 'Genre'])

@section('content')
    <x-container>
        <div class="col-12">
            <form action="{{ route('admin.genre.store') }}" method="POST" class="mb-3">
                @csrf
                <button class="btn btn-dark" type="submit">Generate Genre</button>
            </form>
            <x-card-action title="Genres" class="card-body p-0" :url="route('admin.genre.index')" :value="$search" name="search">
                <x-table>
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Genre Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($genres as $i => $genre)
                            <tr>
                                <td>{{ $i + $genres->firstItem() }}</td>
                                <td>{{ $genre->name }}</td>
                                <td>
                                    <x-button-modal :id="$genre->id" icon="edit" title=""
                                        class="btn btn-warning btn-sm" />
                                    <x-modal :id="$genre->id" title="Edit">
                                        <form action="{{ route('admin.genre.update', $genre->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <x-input title="Name" name="name" type="text"
                                                placeholder="Input genre name" :value="$genre->name" />
                                            <button type="submit" class="btn btn-dark">Update</button>
                                        </form>
                                    </x-modal>
                                    <x-button-delete :id="$genre->id" title="" :url="route('admin.genre.destroy', $genre->id)"
                                        class="btn btn-danger btn-sm" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
            </x-card-action>
            <div class="d-flex justify-content-end">{{ $genres->links() }}</div>
        </div>
    </x-container>
@endsection
