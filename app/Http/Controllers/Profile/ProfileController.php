<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\UseCases\Profile\ProfileUseCase;
use App\Http\Requests\Profile\ProfileDeleteRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * @var ProfileUseCase
     */
    private $profileUseCase;

    public function __construct(ProfileUseCase $profileUseCase)
    {
        $this->profileUseCase = $profileUseCase;
    }

    public function edit(Request $request): View
    {
        $user = $request->user()->load('college', 'department');

        return view('profile.edit', [
            'user' => $user,
            'college' => $user->college,
            'department' => $user->department,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->profileUseCase->update($request);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(ProfileDeleteRequest $request): RedirectResponse
    {
        $this->profileUseCase->destroy($request);

        return Redirect::to('/');
    }
}
