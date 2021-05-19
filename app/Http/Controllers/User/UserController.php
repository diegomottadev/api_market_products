<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\User;
class UserController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.input'. UserTransformer::class)->only(['store','update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        return $this->showAll($users);

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
        $rules = [
            'name'=> 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request,$rules);

        $campos = $request->all();

        $campos['password'] = bcrypt($request->get('password'));
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;
        $user = User::create($campos);

        return $this->showOne($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        $rules = [
            'email' => 'email|unique:users,email,' ,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR. ',' . User::USUARIO_REGULAR,
        ];

        $this->validate($request, $rules);

        if($request->statushas('name')){
            $user->name = $request->get('name');
        }

        if($request->has('email') && $user->email !=  $request->get('email')){

                $user->verified  = User::USUARIO_NO_VERIFICADO;
                $user->verification_token = User::generarVerificationToken();
                $user->email = $request->get('email');


        }

        if($request->has('password')){
            $user->password = bcrypt($request->get('password'));
        }

        if($request->has('admin')){
            if(!$user->esVerificado()){
                return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor',409);
            }

            $user->admin = $request->get('admin');
        }

        if(!$user->isDirty()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar',422);
        }

        $user->save();

        return $this->showOne($user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //

        $user->delete();

        return $this->showOne($user);
    }
}
