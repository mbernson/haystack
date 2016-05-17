<?php namespace App\Mixins;

use App\Database\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait WithLoggedInUser
{
    protected $user;

    public function __construct(Authenticatable $user) {
        if($user instanceof User) {
            $this->user = $user;
        } else {
            abort(400, "You must be logged in as a user");
        }
    }
}