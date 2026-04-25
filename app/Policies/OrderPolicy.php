<?php
namespace App\Policies;
use App\Models\Order;
use App\Models\User;
class OrderPolicy {
    public function view(User $user, Order $order) {
        return $user->role === 'admin' || $order->customer_id === $user->id;
    }
    public function update(User $user, Order $order) {
        return $user->role === 'admin';
    }
    public function viewAny(User $user) {
        return $user->role === 'admin';
    }
    public function chat(User $user, Order $order) {
        return $user->role === 'admin' || $order->customer_id === $user->id;
    }
}
