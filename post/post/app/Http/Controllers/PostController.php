<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    public function create()
    {
        return view('auth.user-register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        // Create a new user record
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => md5($request->password),
        ]);
        if($user){
            return redirect('/')->with('success', 'Registration successful!');
        }else{
            return redirect()->back()->with('error','Error !');
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (md5($request->password) == $user->password) {
                $request->session()->put('id', $user->id);
                return redirect()->route('user.home')->with('success', 'Login Successfully !');;
            } else {
                return redirect()->back()->with('error', 'Incorrect password');
            }
        } else {
            return redirect()->back()->with('error', 'Email not found');
        }
    }


    public function home()
    {
        $data['user'] = User::where('id', session('id'))->first();
        $post = Post::where('user_id', session('id'))->get();
        return view('post.index', compact('data', 'post'));
    }

    public function logout()
    {
        session()->flush();
        return redirect('/')->with('msg', "Logout !");
    }


    // POST FUNCTION 

    public function createpost()
    {
        return view('post.create-post');
    }

    public function poststore(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        $request->all();

        $file = $request->image;
        $extenstion = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extenstion;
        $file->move('images/', $filename);

        $post = new Post;
        $post->user_id = session('id');
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->image = $filename;
        $post->save();

        return redirect('/home');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('post.edit', compact('post'));
    }


    public function postedit(Request $request)
    {

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $id= $request->id;
        $post = Post::findOrFail($id);

        if ($request->image == '') {
            $filename = $request->old_image;
        }else{
        $file = $request->image;
        $extenstion = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extenstion;
        $file->move('images/', $filename);
        }
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->image = $filename;
        $post->save();

        return redirect('/home');
    }

    public function postdelete($id)
    {
        $post = Post::findOrFail($id);
    
        if ($post->image) {
            $imagePath = public_path('images/' . $post->image); // Use public_path to get the full path to the image
    
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image using unlink
            }
        }
    
        $post->delete(); // Delete the post
    
        return redirect('/home')->with('success', 'Post and associated image deleted successfully');
    }
    
    
}
