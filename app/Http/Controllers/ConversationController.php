<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        return view('convos');
    }

    public function get_convo()
    {
        $output = '';
        $convos = Conversation::where('user_1', auth()->user()->id)->orWhere('user_2', auth()->user()->id)->orderBy('updated_at', 'desc')->get();
        $msg =  Message::where('incoming_id', auth()->user()->id)->latest()->first();
        if ($msg && $msg->status == 0) {
            $msg->update([
                'status' => 1
            ]);
        }
        if ($convos->count() > 0) {
            foreach ($convos as $convo) {
                $user =  User::Where(function ($query) use ($convo) {
                    $query->where("id", $convo->user_1)
                        ->orWhere("id", $convo->user_2);
                })
                    ->Where(function ($query) use ($convo) {
                        $query->Where("id", '!=', auth()->user()->id);
                    })->first();
                // $user = User::where('id', $convo->user_1)->orWhere('id', $convo->user_2)->where('id', '!=', auth()->user()->id)->first();
                $user->status == 'Offline' ? $offline = ' offline' : $offline = '';
                $url = asset('uploads/' . $user->profile_pic);
                $href = route('chats', $user->id);

                // get last message
                $message = $convo->message()->latest()->first();
                $last_msg = $message->message;
                if (strlen($last_msg) > 28) {
                    $last_msg = substr($last_msg, 0, 26) . '....';
                }
                if (auth()->user()->id == $message->outgoing_id) {
                    $last_msg = 'You: ' . $last_msg;
                }

                // //get status of sent message
                $msg_count = '';
                $status = '';
                $message_status = $convo->message()->where('outgoing_id', auth()->user()->id)->latest()->first();
                if ($message_status) {
                    if ($message_status->outgoing_id == $message->outgoing_id) {
                        if ($message_status->status == 0) {
                            $status = '<i class="fa fa-check" aria-hidden="true"style="padding-left:10px;"></i>';
                        } elseif ($message_status->status == 1) {
                            $status = '<i class="fa fa-check" aria-hidden="true" style="padding-left:10px;"></i> <i class="fa fa-check" aria-hidden="true"></i>';
                        } else {
                            $status = '<i class="fa fa-check" aria-hidden="true" style="color: blue; padding-left:10px;"></i> <i class="fa fa-check" aria-hidden="true" style="color: blue;"></i>';
                        }
                    } else {
                        $msg =  $convo->message()->where('incoming_id', auth()->user()->id)->where('outgoing_id', $user->id)->where('status', 1)->get();
                        // dd($msg_count);
                        if ($msg->count() > 0) {
                            $msg_count =  '<span class="badge bg-primary badge-number ms-end" style="font-size:11px;margin-left:50px;">' . $msg->count() . ' </span>';
                        }
                    }
                }

                $output .= '
                        <a href="' . $href . '">
                            <div class="content">
                            <img src="' . $url . '">
                            <div class="details">
                                <span>' . $user->fname . " " . $user->lname . '</span>
                                <p>' . $last_msg . $status . $msg_count . '</p>
                            </div>
                            </div>
                            <div class="status-dot' . $offline . '"><i class="fas fa-circle"></i></div>
                        </a>';
            }
            return $output;
        } else {
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
            return $output;
        }
    }
    public function search(Request $request)
    {
        //search query
        $users =  User::Where(function ($query) use ($request) {
            $query->where('fname', 'LIKE', '%' . $request->search . '%')
                ->orWhere('lname', 'LIKE', '%' . $request->search . '%');
        })
            ->where(function ($query) {
                $query->Where("id", '!=', auth()->user()->id);
            })
            ->get();

        $output = '';
        if ($users->count() > 0) {
            foreach ($users as $user) {
                $url = asset('uploads/' . $user->profile_pic);
                $href = route('chats', $user->id);
                // return $user;

                // if ($user->id != auth()->user()->id) {
                //get last message
                $messages =  Message::Where(function ($query) use ($user) {
                    $query->where("incoming_id", $user->id)
                        ->where("outgoing_id", auth()->user()->id);
                })
                    ->orWhere(function ($query) use ($user) {
                        $query->Where("incoming_id", auth()->user()->id)
                            ->Where("outgoing_id", $user->id);
                    })
                    ->latest()->first();
                $msg_count = '';
                $status = '';

                if ($messages) {
                    $message = $messages->message;
                    if (strlen($message) > 28) {
                        $message = substr($message, 0, 26) . '....';
                    }
                    if (auth()->user()->id == $messages->outgoing_id) {
                        $message = 'You: ' . $message;
                    }

                    $message_status = Message::where('outgoing_id', auth()->user()->id)->latest()->first();
                    // dd($messages);
                    if ($message_status) {
                        if ($message_status->outgoing_id == $messages->outgoing_id) {
                            if ($message_status->status == 0) {
                                $status = '<i class="fa fa-check" aria-hidden="true"style="padding-left:10px;"></i>';
                            } elseif ($message_status->status == 1) {
                                $status = '<i class="fa fa-check" aria-hidden="true" style="padding-left:10px;"></i> <i class="fa fa-check" aria-hidden="true"></i>';
                            } else {
                                $status = '<i class="fa fa-check" aria-hidden="true" style="color: blue; padding-left:10px;"></i> <i class="fa fa-check" aria-hidden="true" style="color: blue;"></i>';
                            }
                        } else {
                            $msg =  Message::where('incoming_id', auth()->user()->id)->where('outgoing_id', $user->id)->where('status', 1)->get();
                            if ($msg->count() > 0) {
                                $msg_count =  '<span class="badge bg-primary badge-number ms-end" style="font-size:11px;margin-left:50px;">' . $msg->count() . ' </span>';
                            }
                        }
                    }
                } else {
                    $message = 'No message available';
                }

                // //get status of sent message
                $user->status == 'Offline' ? $offline = ' offline' : $offline = '';
                $output .= '
                <a href="' . $href . '">
                    <div class="content">
                    <img src="' . $url . '">
                    <div class="details">
                        <span>' . $user->fname . " " . $user->lname . '</span>
                        <p>' . $message . $status . $msg_count . '</p>
                    </div>
                    </div>
                    <div class="status-dot' . $offline . '"><i class="fas fa-circle"></i></div>
                </a>';
            }
        } else {
            $output .= 'No user found related to your search term';
        }
        return $output;
    }
}
