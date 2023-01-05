<?php

namespace App\Http\Controllers;

use App\Mail\ChirpCreated;
use App\Models\Chirp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        return 'Hello, World!';
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //dd($request);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
        //dd($validated);
        //dd($request->user()->id);
        $chirp=$request->user()->chirps()->create($validated);

// 其他儲存方式
//       auth()->user()->chirps()->create($validated);

//        $chirp= new Chirp();
//        $chirp->user_id = $request->user()->id;
//        $chirp->message = $validated['message'];
//        $chirp->save();

//        Chirp::create([
//            'user_id' => $request->user()->id,
//            'message' => $validated['message'],
//        ]);
        foreach (User::whereNot('id', $chirp->user_id)->cursor() as $user) {
            Mail::to($user)->send(new ChirpCreated($chirp));
        }
        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function edit(Chirp $chirp)
    {
        //
        $this->authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp'=>$chirp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chirp $chirp)
    {
        //
        $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);

        return redirect(route('chirps.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chirp $chirp)
    {
        //
        $this->authorize('delete', $chirp);

        $chirp->delete();

        return redirect(route('chirps.index'));
    }
}
