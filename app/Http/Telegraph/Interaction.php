<?php

namespace App\Http\Telegraph;

use App\Models\UserProfile as User;
use App\Models\Purchases as Purchases;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;
use Illuminate\Support\Facades\DB;

class Interaction extends WebhookHandler
{
    public function start(): void
    {
        $this->chat->html('Приветствую, доступные команды - /help')->send();

    }

    protected function handleChatMessage(Stringable $text): void
    {
        Log::info((json_encode($this->message->from()->id())));
        Log::info((json_encode($this->message->from()->username())));
        Log::info(json_encode($this->message->toArray(), JSON_UNESCAPED_UNICODE));

    }

    public function register(string $code)
    {
        $chat_id = json_encode($this->message->from()->id());
        $username = json_encode($this->message->from()->username());

        if ($code === 'intensa') {
            if ($this::checkReg($chat_id)) {
                $this::addUser($chat_id, $username);
                $this->reply("Вы успешно зарегистрировались.");
            } else {
                $this->reply("Пользователь уже зарегистрирован");
            }

        } else {
            $this->reply('Код неверный. Доступ запрещен');
        }
    }

    public function profile()
    {
        $chat_id = json_encode($this->message->from()->id());
        $username = json_encode($this->message->from()->username());

        $user = DB::table('user_profiles')
            ->where('chat_id', '=', $chat_id)->first();
        $this->chat->html('Данные профиля пользователя:&#10;
        Имя пользвателя: ' . $username . '&#10;
        ID: ' . $chat_id . '&#10;
        Баланс: ' . $user->balance . ' IntCoin')->send();
    }

    public function buy($id)
    {
        if (!intval($id)) {
            $this->chat->html('Некорректный id товара.&#10Доступный товар /catalog')->send();
        } else {
            $chat_id = json_encode($this->message->from()->id());
            $username = json_encode($this->message->from()->username());

            $user = DB::table('user_profiles')
                ->where('chat_id', '=', $chat_id)->first();
            $item = DB::table('catalog')
                ->where('id', '=', $id)->first();
            if ($item->price <= $user->balance) {
                $balance = $user->balance;
                $price = $item->price;
                $balance = $balance - $price;
                DB::table('user_profiles')
                    ->where('chat_id', '=', $chat_id)->update(['balance' => $balance]);
                $transaction_id = rand(99999, 1000000);
                $this::addTransaction($item->name, $transaction_id);
                $this->chat->html('Вы успешно приобрели товар, за получением обратитесь к менеджеру, ID транзакции - ' . $transaction_id)->send();

            } else {
                $this->chat->html('Недостаточно средств. Для проверки баланса воспользуйтесь командой&#10; /profile')->send();
            }
        }
    }

    public function catalog()
    {
        $catalog = DB::table('catalog')->get();
        foreach ($catalog as $item) {
            $this->chat->photo($item->image)->send();
            $this->chat->html($item->name . '&#10;ID - ' . $item->id . '&#10;' . $item->price . ' IntCoin')->send();
        }
        $this->chat->html(
            'Для совершения покупки воспользуйтесь командой /buy id'
        )->send();
    }

    public function help()
    {
        $this->chat->html(
            '/profile => Данные профиля пользователя&#10/catalog => Каталог товаров&#10/register => Регистрация'
        )->send();

    }

    protected function addUser($chat_id, $name)
    {
        $user = new User;
        $user->name = $name;
        $user->chat_id = $chat_id;
        $user->admin = false;
        $user->save();
    }

    protected function addTransaction($name, $transaction_id)
    {
        $purchase = new Purchases();
        $purchase->transaction_id = $transaction_id;
        $purchase->value = $name;
        $purchase->save();
    }

    protected function checkReg($chat_id)
    {
        $user = DB::table('user_profiles', $chat_id)->first();
        if ($user) {
            return false;
        } else {
            return true;
        }
    }

    public function add($chat_id)
    {
        $chat_id_current = json_encode($this->message->from()->id());
        if ($chat_id_current == 441651507) {
            $user = DB::table('user_profiles', $chat_id)->first();
            $value = $user->balance + 500;
            DB::table('user_profiles')
                ->where('chat_id', '=', $chat_id)->update(['balance' => $value]);
            $this->chat->html('Средства начислены, баланс - ' . $value)->send();
        } else {
            $this->chat->html('Доступ запрещен')->send();
        }
    }
}
