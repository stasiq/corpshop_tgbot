<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\UserProfile as User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('command', function () {
//    $chat_id = json_encode($this->message->from()->id());
//    $username = json_encode($this->message->from()->username());

    $user = DB::table('user_profiles')
        ->where('chat_id', '=', 441651507)->first();
    $item = DB::table('catalog')
        ->where('id', '=', 2)->first();
    dd($item->price);

        if ($item->price <= $user->balance) {
            $balance = $user->balance;
            $price = $item->price;
            $balance = $balance - $price;
//
//
//
//            DB::table('user_profiles')
//                ->where('chat_id', '=', $chat_id)->update(['balance' => $balance]);
//            $chat = TelegraphChat::find(441651507);
//            $chat->message($username . ' купил ' . $id)->send();
        } else {
            $this->chat->html('Недостаточно средств. Для проверки баланса воспользуйтесь командой&#10; /profile')->send();
        }
    /** @var \DefStudio\Telegraph\Models\TelegraphBot $telegraphBot */
    $telegraphBot = \DefStudio\Telegraph\Models\TelegraphBot::find(1);
    $user = DB::table('catalog')->get();
    dd($user);
//    $user = User::where('chat_id', 333)->get();
//    $user = DB::table('user_profiles',441651507)->first();

    $user = DB::table('user_profiles')
        ->where('chat_id', '=', 441651507)->first();


//    $telegraphBot->registerCommands([
//        'profile' => 'Данные профиля пользователя',
//        'catalog' => 'Каталог товаров',
//        'help' => 'Доступные команды',
//    ])->send();
//    dd($user->balance);

});
