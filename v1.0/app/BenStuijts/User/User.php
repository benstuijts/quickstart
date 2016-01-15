<?php

namespace BenStuijts\User;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent 
{
    protected $table = 'users';
    
    protected $fillable = [
        'email',
        'username',
        'password',
        'first_name',
        'last_name',
        'job',
        'tags',
        'city',
        'logins',
        'active',
        'active_hash',
        'recover_hash',
        'remember_identifier',
        'remember_token',
        'status',
        'gender',
        'club',
        'avatar'
        ];
    
    // Get properties functions
    
    public function getUserName() {
        return $this->username;
    }
    
    public function getNameFromId($id) {
        if($id==0 || !$id) {return 'gast';}
        $user = $this->where('id', $id)->get()->first();
        return $user->getFullNameOrUsername();
    }
    public function getUsernameFromId($id) {
        if($id==0 || !$id) return 'gast';
        $user = $this->where('id', $id)->get()->first();
        return $user->username;
    }
    
    public function getFullName() {
        if(!$this->first_name || !$this->last_name) {
            return null;
        }
        
        return "{$this->first_name} {$this->last_name}";
    }
    
    public function getFullNameOrUsername() {
        return $this->getFullName() ?: $this->username;
    }
    
    public function getFirstNameOrUsername() {
        return $this->first_name ?: $this->username;
    }
    
    public function getTags() {
        $tags = explode(',',$this->tags);
        return $tags;
    }
    
    
    
    public function getAvatarUrl($options = []) {
        // Er zijn drie opties:
        // 1. Gravatar, 2. Facebook 3. Eigen Avatar uploaden
        
        /* Wordt de eigen of een andere avatar opgevraagd? */
        
        $user = isset($options['id']) ? $this->where('id', $options['id'])->get()->first() : $this;
        
        /* uploaded */
        if($user->avatar) {
            
            $baseUrl = isset($options['baseUrl']) ? $options['baseUrl'] : '';
            return $baseUrl . "/uploads/{$user->username}/{$user->avatar}";
        }
        
        
        /* Gravatar */
        
        $size = isset($options['size']) ? $options['size'] : 45;
        return 'http://www.gravatar.com/avatar/' . md5($user->email) . '?s=' . $size . '&d=mm';
    }
    
    // Activate account
    
    public function activateAccount() {
        
        $this->update([
            'active' => true,
            'active_hash' => null
            ]);
    }
    
    public function updateRememberCredentials($identifier, $token) {
        
        $this->update([
            'remember_identifier' => $identifier,
            'remember_token' => $token
            ]);
        
    }
    
    public function removeRememberCredentials() {
        $this->updateRememberCredentials(null, null);
    }
    
    public function hasPermission($permission) {
        return (bool) $this->permissions->{$permission};
    }
    
    public function isAdmin() {
        return $this->hasPermission('is_admin');
    }
    
    public function permissions() {
        return $this->hasOne('BenStuijts\User\UserPermission', 'user_id');
    }
    
    // Search functions
    
    public function searchFor($query) {
        return $this
            ->where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->get();
    }
    
    // Friends -> UNDER CONSTRUCTION!!!
    
    public function getTotalNumberOfFriends() {
        return $this->friends()->count();
    }
    
    public function isMyFriend($id_user, $id_friend) {
        if($this->id == $id_user) {
            return false;
        }
        $myFriends = $this->friends();
        foreach($myFriends as $friend) {
            //echo $friend->pivot;
            if($friend->pivot->friend_id == $id_user || $friend->pivot->user_id == $id_user ) {
                return true;
            }
        }
        return false;
    }
    
    public function isFriend($id) {
        $check = $this->friendsOfMine()->wherePivot('accepted', true)->wherePivot('friend_id', $id);
        
        // Dit zijn alle vrienden van de user:
        $check2 = $this->friends();
        echo '<pre>';
        var_dump($this->friends());
        
        
        echo '</pre>';
        // check of $id onderdeel uitmaakt van de collectie van check2
        
        
        // alle vrienden van de user selecteren
        $friendsOfUser = $this->friendsOfMine()->wherePivot('accepted', true);
        
        $checkCurrentFriend = $friendsOfUser->get()->where('friend_id', $id);
        
        
        echo 'ID = ' . '<strong>' . $id . '</strong><hr>'; 
        
        echo '<hr>This user has ' . $this->getTotalNumberOfFriends() . ' friends.';
        echo '<pre>';
        print_r($check2->where('friend_id', $id));
        echo '</pre>';
        //echo $checkCurrentFriend()->get();
        
        if($check->count() == 0) { return false; }
        return true;
    }
    
    public function checkFriend($id) {
        //var_dump($this->friendsOf()->wherePivot('accepted', true))->get();
        echo (bool) $this->friendsOfMine()->wherePivot('accepted', true)->wherePivot('friend_id', $id);
        return (bool) $this->friendsOfMine()->wherePivot('accepted', true)->wherePivot('friend_id', $id);
    }
    
    public function friends() {
        return $this->friendsOfMine()->wherePivot('accepted', true)->get()->merge($this->friendsOf()->wherePivot('accepted', true)->get());
    }
    
    public function friendsOfMine() {
        // vrienden, die ik heb of uitgenodigd heb
        return $this->belongsToMany('BenStuijts\User\User', 'friends', 'user_id', 'friend_id');
    }
    
    public function getFriendsOfMine() {
        return $this->friendsOfMine()->get();
    }
    
    public function friendsOf() {
        // vrienden, die mij hebben of uitgenodigd hebben
        return $this->belongsToMany('BenStuijts\User\User', 'friends', 'friend_id', 'user_id');
    }
    
    public function friendRequests() {
        return $this->friendsOfMine()->wherePivot('accepted', false)->get();
    }
    
    public function friendRequestsPending() {
        return $this->friendsOf()->wherePivot('accepted', false)->get();
    }
    
    public function hasFriendRequestPending(User $user) {
        return (bool) $this->friendRequestsPending()->where('id', $user->id)->count();
    }
    
    public function hasFriendRequestReceived(User $user) {
        return (bool) $this->friendRequests()->where('id', $user->id)->count();
    }
    
    public function addFriend(User $user) {
        $this->friendsOf()->attach($user->id);
        
    }
    
    public function acceptFriendRequest(User $user) {
        $this->friendRequests()
            ->where('id', $user->id)
            ->first()
            ->pivot
            ->update([
                'accepted' => true
            ]);
    }
    
    public function isFriendsWith(User $user) {
        return (bool) $this->friends()->where('id', $user->id)->count();
    }
    
    
    
    // EINDE JUISTE FUNCTIES (rest kan weg)
    

    

    
    
    
}

?>