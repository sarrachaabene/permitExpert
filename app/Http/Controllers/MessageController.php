<?php

namespace App\Http\Controllers; // Déplacez le namespace ici

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @OA\Schema(
 *     schema="Message",
 *     title="Message",
 *     description="Message model",
 *     @OA\Property(
 *         property="dateM",
 *         type="string",
 *         format="date",
 *         description="Date of the message"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the message"
 *     ),
 *     @OA\Property(
 *         property="sender_id",
 *         type="integer",
 *         description="ID of the sender user"
 *     ),
 *     @OA\Property(
 *         property="recipient_id",
 *         type="integer",
 *         description="ID of the recipient user"
 *     ),
 * )
 */

class MessageController extends Controller
{
  /**
 * @OA\Get(
 *      path="/api/message/index",
 *      operationId="getMessageIndex",
 *      tags={"Messages"},
 *      summary="Get all messages",
 *      description="Retrieves all messages from the database",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Message")
 *          ),
 *      ),
 * )
 */
    public function index()
    {
        $message = Message::get();
        return response()->json($message, 200);
    }


/**
 * @OA\Post(
 *      path="/api/message/store",
 *      operationId="storeMessage",
 *      tags={"Messages"},
 *      summary="Store a new message",
 *      description="Stores a new message in the database",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/Message")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Message"),
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Unprocessable Entity",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Invalid data provided"
 *          ),
 *      ),
 * )
 */
    public function store(Request $request)
    {  // $user = User::find($request->sender_id);

        $message = Message::create($request->all());
        // Créer une nouvelle notification associée au message créé
        $notification=Notification::create([
          'message_id' => $message->id,
          'message_description' =>$message->description,
          'sender_msg' =>$message->sender_id,
          'receptient_msg'=>$message->recipient_id
        ]);
      /* $user->notification_id = $notification->id;
        $user->save();*/
        return response()->json($message, 200);
    }
/**
 * @OA\Get(
 *      path="/api/message/show/{id}",
 *      operationId="getMessageById",
 *      tags={"Messages"},
 *      summary="Get a message by ID",
 *      description="Retrieves a message by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the Message",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Message"),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Message not found"
 *          ),
 *      ),
 * )
 */
    public function show($id)
    {
        $message = Message::find($id);
        if ($message) {
            return response()->json($message, 200);
        } else {
            $msg = "votre id n'est pas trouve";
            return response()->json($msg, 200);
        }   
    }
}
