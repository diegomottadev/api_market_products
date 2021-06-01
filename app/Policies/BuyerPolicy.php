<?php

namespace App\Policies;

use App\Buyer;
use App\Traits\AdminActions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuyerPolicy
{
    use HandlesAuthorization, AdminActions;





    public function view(User $user, Buyer $buyer)
    {
        //
        return $user->id === $buyer->id;
    }

    /**
     * Determine whether the user can purchase the model.
     *
     * @param  \App\User  $user
     * @param  \App\Buyer  $buyer
     * @return mixed
     */
    public function purchase(User $user, Buyer $buyer)
    {
        //
        return $user->id === $buyer->id;

    }


}
