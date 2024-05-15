<?php

namespace App\Http\Controllers; // Déplacez le namespace ici

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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
    try {
        $messages = Message::get();

        if ($messages->isEmpty()) {
            return response()->json(["error" => "Aucun message trouvé."], 404);
        }
        return response()->json($messages, 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération des messages: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
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
{
    try {
        $validatedData = $request->validate([
            'message_description' => 'required|string',
            'recipient_id' => 'required|exists:users,id|different:sender_msg',
        ]);

        $sender_id = auth()->id(); 

        if ($validatedData['recipient_id'] === $sender_id) {
            return response()->json(["error" => "Le destinataire et l'expéditeur ne peuvent pas être les mêmes."], 400);
        }

        $message = Message::create([
            'description' => $validatedData['message_description'],
            'sender_id' => $sender_id, 
            'recipient_id' => $validatedData['recipient_id'],
            'dateM' => now(), 
        ]);

        Notification::create([
            'message_id' => $message->id,
            'message_description' => $message->description,
            'sender_msg' => $message->sender_id,
            'receptient_msg' => $message->recipient_id,
        ]);

        return response()->json($message, 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la création du message: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
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
    try {
        $message = Message::find($id);
        if ($message) {
            return response()->json($message, 200);
        } else {
            $msg = "Le message avec l'ID spécifié n'a pas été trouvé.";
            return response()->json(["error" => $msg], 404);
        }
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération du message: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}


public function storeForMobile(Request $request)
{
    try {
        $sender_id = auth()->id(); 
        $userAutoEcoleId = User::findOrFail($sender_id)->auto_ecole_id;
        $recipients = DB::table('users')
            ->where('auto_ecole_id', $userAutoEcoleId)
            ->where(function($query) {
                $query->where('role', 'admin')
                      ->orWhere('role', 'secretaire');
            })
            ->get();
        if ($recipients->isEmpty()) {
            return response()->json(["error" => "Aucun administrateur ou secrétaire trouvé pour cette auto-école."], 404);
        }
        foreach ($recipients as $recipient) {
            $message = Message::create([
                'description' => $request->message_description,
                'sender_id' => $sender_id, 
                'recipient_id' => $recipient->id,
                'dateM' => now(), 
            ]);
            Notification::create([
                'message_id' => $message->id,
                'message_description' => $message->description,
                'sender_msg' => $message->sender_id,
                'receptient_msg' => $message->recipient_id,
            ]);
        }
        return response()->json(["success" => "Message envoyé avec succès à l'administrateur et au secrétaire."], 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de l'envoi du message: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}
}
