<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *     schema="Notification",
 *     title="Notification",
 *     description="Notification model",
 *     @OA\Property(
 *         property="message_id",
 *         type="integer",
 *         description="ID of the message associated with the notification"
 *     ),
 *     @OA\Property(
 *         property="message_description",
 *         type="string",
 *         description="Description of the message"
 *     ),
 *     @OA\Property(
 *         property="sender_msg",
 *         type="string",
 *         description="Sender of the message"
 *     ),
 *     @OA\Property(
 *         property="receptient_msg",
 *         type="string",
 *         description="Recipient of the message"
 *     ),
 * )
 */

class NotificationController extends Controller
{
  /**
 * @OA\Get(
 *      path="/api/Notification/index",
 *      operationId="getNotifications",
 *      tags={"Notifications"},
 *      summary="Get all notifications",
 *      description="Retrieves all notifications from the database",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Notification")
 *          ),
 *      ),
 * )
 */
public function index()
{
    try {
        $notifications = Notification::get();
        if ($notifications->isEmpty()) {
            return response()->json(["error" => "Aucune notification trouvée."], 404);
        }      
        return response()->json($notifications, 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération des notifications: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}
/**
 * @OA\Get(
 *      path="/api/Notification/ShowNotificationsByReceptientId/{ReceptientId}",
 *      operationId="getNotificationsByReceptientId",
 *      tags={"Notifications"},
 *      summary="Get notifications by Recipient ID",
 *      description="Retrieves notifications associated with a specific Recipient by their ID",
 *      @OA\Parameter(
 *          name="ReceptientId",
 *          in="path",
 *          description="ID of the Recipient",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Notification")
 *          ),
 *      ),
 * )
 */
public function showNotificationsByReceptientId()
{
    try {
        if (!Auth::check()) {
            return response()->json(["error" => "Utilisateur non authentifié."], 401);
        }  
        $userId = Auth::id();
        $notifications = Notification::where('receptient_msg', $userId)->get();    
        if ($notifications->isEmpty()) {
            return response()->json(["error" => "Aucune notification trouvée pour le destinataire spécifié."], 404);
        }
                $notificationsDetails = [];
        foreach ($notifications as $notification) {
            $senderDetails = User::find($notification->sender_msg);
            $notificationDetails = [
                "id" => $notification->id,
                "id_user"=>$notification->sender_msg,
                "message_description" => $notification->message_description,
                "sender_name" => $senderDetails ? $senderDetails->user_name : "Utilisateur supprimé",
            ];
            $notificationsDetails[] = $notificationDetails;
        }  
        return response()->json($notificationsDetails, 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération des notifications: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}

/**
 * @OA\Get(
 *      path="/api/Notification/show/{id}",
 *      operationId="getNotificationById",
 *      tags={"Notifications"},
 *      summary="Get a notification by ID",
 *      description="Retrieves a notification by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the Notification",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Notification"),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Notification not found"
 *          ),
 *      ),
 * )
 */
public function show($id)
{
    try {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(["error" => "Aucune notification trouvée pour l'ID spécifié."], 404);
        }
        return response()->json($notification, 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération de la notification: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}
}
