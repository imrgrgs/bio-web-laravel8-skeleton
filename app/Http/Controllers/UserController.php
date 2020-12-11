<?php

namespace App\Http\Controllers;

use Response;
use Laratrust\Laratrust;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\AppBaseController;

class UserController extends AppBaseController
{
    /** @var $userRepository UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->all();

        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        if (!Auth::user()->hasPermission('users-create')) {
            Flash::error('Usuário ' . Auth::user()->name . ' não possui permissão de adicionar usuários');

            return redirect(route('users.index'));
        }

        return view('users.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        if (!Auth::user()->hasPermission('users-create')) {
            Flash::error('Usuário ' . Auth::user()->name . ' não possui permissão de adicionar usuários');

            return redirect(route('users.index'));
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = $this->userRepository->create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        if (!Auth::user()->hasPermission('users-read')) {
            Flash::error('Usuário ' . Auth::user()->name . ' não possui permissão de visualizar usuários');

            return redirect(route('users.index'));
        }
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        if (!Auth::user()->hasPermission('users-update')) {
            Flash::error('Usuário ' . Auth::user()->name . ' não possui permissão de editar usuários');

            return redirect(route('users.index'));
        }
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        if (!Auth::user()->hasPermission('users-update')) {
            Flash::error('Usuário ' . Auth::user()->name . ' não possui permissão de alterar usuários');

            return redirect(route('users.index'));
        }
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }
        $input =  $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }
        $user = $this->userRepository->update($input, $id);

        Flash::success('User updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->hasPermission('users-delete')) {
            Flash::error('Usuário ' . Auth::user()->name . ' não possui permissão de excluir usuários');

            return redirect(route('users.index'));
        }
        if (Auth::user()->id == $id) {
            Flash::error('Usuário ' . Auth::user()->name . ' não possui permissão de excluir a si mesmo');

            return redirect(route('users.index'));
        }
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }
}
