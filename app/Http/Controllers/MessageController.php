<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function message($id, Request $request)
    {
        $request->validate([
            'message' => 'required'
        ]);

        // check if convo exists
        $convo =  Conversation::Where(function ($query) use ($id) {
            $query->Where("user_1", $id)
                ->where("user_2", auth()->user()->id);
        })
            ->orWhere(function ($query) use ($id) {
                $query->Where("user_1", auth()->user()->id)
                    ->Where("user_2", $id);
            })
            ->latest()->first();

        // function to send message 
        function sendmsg($convo, $request, $id)
        {
            $convo->message()->create([
                'message' => $request->message,
                'incoming_id' => $id,
                'outgoing_id' => auth()->user()->id
            ]);
        }
        if ($convo) {
            sendmsg($convo, $request, $id);
            $convo->update([
                'updated_at' => now()
            ]);
        } else {
            $convo = Conversation::create([
                'user_1' => auth()->user()->id,
                'user_2' => $id
            ]);
            $a = sendmsg($convo, $request, $id);
        }
    }

    public function getmessage($id)
    {
        Message::where('incoming_id', auth()->user()->id)->update([
            'status' => 2
        ]);
        $convo =  Conversation::Where(function ($query) use ($id) {
            $query->where("user_1", $id)
                ->where("user_2", auth()->user()->id);
        })
            ->orWhere(function ($query) use ($id) {
                $query->Where("user_1", auth()->user()->id)
                    ->Where("user_2", $id);
            })
            ->first();

        $msg_status = '';
        $output = '';
        $user = User::where('id', $id)->first();
        $last_seen = Carbon::parse($user->last_seen)->diffForHumans();
        $user->status == 'Online' ? $status = 'Online' : $status = 'Last seen: ' . $last_seen;

        if ($convo) {
            foreach ($convo->message as $message) {
                // //get status of sent message

                $msg_time = Carbon::parse($message->created_at);

                $date = new DateTime();
                $match_date = new DateTime($msg_time);
                $interval = $date->diff($match_date);

                if ($interval->days == 0) {
                    $msg_time = $msg_time->format('g:i a');
                } elseif ($interval->days == 1) {
                    if ($interval->invert == 0) {
                        $msg_time = 'Yesterday, ' . $msg_time->format('g:i a');
                    } else {
                        $msg_time = $msg_time->format('d/m/Y g:i a');
                    }
                } else {
                    $msg_time = $msg_time->format('d/m/Y g:i a');
                }

                if ($message->status == 0) {
                    $msg_status = '<i class="fa fa-check" aria-hidden="true"style="padding-left:10px;"></i>';
                } elseif ($message->status == 1) {
                    $msg_status = '<i class="fa fa-check" aria-hidden="true" style="padding-left:10px;"></i> <i class="fa fa-check" aria-hidden="true"></i>';
                } else {
                    $msg_status = '<i class="fa fa-check" aria-hidden="true" style="color: blue; padding-left:10px;"></i> <i class="fa fa-check" aria-hidden="true" style="color: blue;"></i>';
                }

                if ($message->outgoing_id == auth()->user()->id) {
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>' . $message->message . '<br>' . '<span style="font-size:10px;">' . $msg_time . '</span>' . $msg_status . '</p>
                                    
                                </div>
                                </div>
                                ';
                } else {
                    $output .= '<div class="chat incoming">
                                <div class="details">
                                    <p>' . $message->message . '<br>' . '<span style="font-size:10px;">' . $msg_time . '</span> </p>
                                

                                </div>
                                </div>';
                }
            }
        } else {
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }
        return ([
            'convo' => $output,
            'status' => $status
        ]);
    }
}
